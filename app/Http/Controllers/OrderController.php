<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function checkout()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with(['items.book'])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $user = auth()->user();

        return view('orders.checkout', compact('cart', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_name'    => 'required|string|max:100',
            'receiver_phone'   => 'required|string|max:20',
            'receiver_address' => 'required|string|max:255',
            'payment_method'   => 'required|in:cod,vnpay',
            'note'             => 'nullable|string|max:500',
        ]);

        $cart = Cart::where('user_id', auth()->id())
            ->with(['items.book'])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Kiểm tra tồn kho
        foreach ($cart->items as $item) {
            if ($item->book->stock < $item->quantity) {
                return back()->with('error', "Sách \"{$item->book->title}\" không đủ số lượng trong kho.");
            }
        }

        $totalAmount = $cart->total;

        try {
            DB::beginTransaction();

            $order = Order::create([
                'order_code'       => Order::generateOrderCode(),
                'user_id'          => auth()->id(),
                'receiver_name'    => $request->receiver_name,
                'receiver_phone'   => $request->receiver_phone,
                'receiver_address' => $request->receiver_address,
                'total_amount'     => $totalAmount,
                'status'           => 'pending',
                'payment_method'   => $request->payment_method,
                'payment_status'   => 'unpaid',
                'note'             => $request->note,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'book_id'    => $item->book_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->book->display_price,
                ]);

                // Giảm tồn kho, tăng số lượng đã bán
                $item->book->decrement('stock', $item->quantity);
                $item->book->increment('sold_count', $item->quantity);
            }

            // Xóa giỏ hàng
            $cart->items()->delete();
            $cart->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đặt hàng thất bại. Vui lòng thử lại.');
        }

        // Xử lý thanh toán VNPay
        if ($request->payment_method === 'vnpay') {
            return $this->redirectToVnpay($order);
        }

        return redirect()->route('orders.show', $order)->with('success', 'Đặt hàng thành công! Cảm ơn bạn đã mua hàng.');
    }

    private function redirectToVnpay(Order $order)
    {
        $vnpayUrl   = config('vnpay.url');
        $tmnCode    = config('vnpay.tmn_code');
        $hashSecret = config('vnpay.hash_secret');
        $returnUrl  = config('vnpay.return_url');

        $inputData = [
            'vnp_Version'    => '2.1.0',
            'vnp_Command'    => 'pay',
            'vnp_TmnCode'    => $tmnCode,
            'vnp_Amount'     => (int) ($order->total_amount * 100),
            'vnp_CurrCode'   => 'VND',
            'vnp_TxnRef'     => $order->order_code,
            'vnp_OrderInfo'  => 'Thanh toan don hang ' . $order->order_code,
            'vnp_OrderType'  => 'billpayment',
            'vnp_Locale'     => 'vn',
            'vnp_ReturnUrl'  => $returnUrl,
            'vnp_IpAddr'     => request()->ip() ?: '127.0.0.1',
            'vnp_CreateDate' => now()->format('YmdHis'),
        ];

        ksort($inputData);

        $hashData = '';
        $query = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashData .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }

        $vnpSecureHash = hash_hmac('sha512', $hashData, $hashSecret);
        $paymentUrl = $vnpayUrl . '?' . $query . 'vnp_SecureHash=' . $vnpSecureHash;

        return redirect($paymentUrl);
    }

    public function vnpayReturn(Request $request)
    {
        $hashSecret = config('vnpay.hash_secret');
        $vnpSecureHash = $request->input('vnp_SecureHash');

        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'vnp_') && $key !== 'vnp_SecureHash' && $key !== 'vnp_SecureHashType') {
                $inputData[$key] = $value;
            }
        }
        ksort($inputData);

        $hashData = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashData .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
        }

        $checkHash = hash_hmac('sha512', $hashData, $hashSecret);

        if ($checkHash !== $vnpSecureHash) {
            return redirect()->route('home')->with('error', 'Chữ ký không hợp lệ từ VNPay.');
        }

        $orderCode  = $request->vnp_TxnRef;
        $responseCode = $request->vnp_ResponseCode;

        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng.');
        }

        if ($responseCode === '00') {
            $order->update([
                'payment_status'       => 'paid',
                'vnpay_transaction_id' => $request->vnp_TransactionNo,
            ]);
            return redirect()->route('orders.show', $order)->with('success', 'Thanh toán VNPay thành công!');
        } else {
            $order->update(['payment_status' => 'unpaid']);
            return redirect()->route('orders.show', $order)->with('error', 'Thanh toán VNPay thất bại. Mã lỗi: ' . $responseCode);
        }
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.book');

        return view('orders.show', compact('order'));
    }
}
