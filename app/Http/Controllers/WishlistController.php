<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with('book.category')
            ->latest()
            ->paginate(12);

        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle(Book $book)
    {
        $existing = Wishlist::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Đã xóa sách khỏi danh sách yêu thích.';
            $inWishlist = false;
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'book_id' => $book->id,
            ]);
            $message = 'Đã thêm sách vào danh sách yêu thích.';
            $inWishlist = true;
        }

        if (request()->wantsJson()) {
            return response()->json(['in_wishlist' => $inWishlist, 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}
