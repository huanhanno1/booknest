<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%")
                  ->orWhere('receiver_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        $statusOptions = [
            'pending'   => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'shipping'  => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
        ];

        return view('admin.orders.index', compact('orders', 'statusOptions'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.book');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipping,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Nếu hủy đơn đã giao thì không cho phép
        if ($oldStatus === 'delivered' && $newStatus === 'cancelled') {
            return back()->with('error', 'Không thể hủy đơn hàng đã giao.');
        }

        // Nếu hủy đơn, hoàn lại tồn kho
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->book) {
                    $item->book->increment('stock', $item->quantity);
                    $item->book->decrement('sold_count', $item->quantity);
                }
            }
        }

        $order->update(['status' => $newStatus]);

        return back()->with('success', 'Trạng thái đơn hàng đã được cập nhật.');
    }
}
