<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'book'])->latest();

        if ($request->filled('status')) {
            $query->where('is_approved', $request->status === 'approved' ? true : false);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('book', fn($b) => $b->where('title', 'like', "%{$search}%"))
                  ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleApproval(Review $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);

        $status = $review->is_approved ? 'duyệt' : 'ẩn';

        return back()->with('success', "Đánh giá đã được {$status}.");
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Đánh giá đã được xóa.');
    }
}
