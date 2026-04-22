<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with(['items.book'])
            ->first();

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'book_id'  => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $book = Book::active()->findOrFail($request->book_id);

        if ($book->stock < $request->quantity) {
            return back()->with('error', 'Số lượng tồn kho không đủ.');
        }

        // Lấy hoặc tạo giỏ hàng
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        // Kiểm tra sách đã có trong giỏ chưa
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('book_id', $book->id)
            ->first();

        if ($cartItem) {
            $newQty = $cartItem->quantity + $request->quantity;
            if ($newQty > $book->stock) {
                return back()->with('error', 'Số lượng trong giỏ hàng vượt quá tồn kho.');
            }
            $cartItem->update(['quantity' => $newQty]);
        } else {
            CartItem::create([
                'cart_id'  => $cart->id,
                'book_id'  => $book->id,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', 'Đã thêm sách vào giỏ hàng.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        // Kiểm tra quyền sở hữu
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        if ($request->quantity > $cartItem->book->stock) {
            return back()->with('error', 'Số lượng vượt quá tồn kho.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
}
