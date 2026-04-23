<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::active()->with('category');

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->firstOrFail();
            $query->where('category_id', $category->id);
        }

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // Sắp xếp
        switch ($request->get('sort', 'newest')) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'bestselling':
                $query->orderBy('sold_count', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $books = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->withCount('activeBooks')->get();
        $selectedCategory = $request->category;

        return view('books.index', compact('books', 'categories', 'selectedCategory'));
    }

    public function show(string $slug)
    {
        $book = Book::active()->where('slug', $slug)->with(['category', 'reviews' => function ($q) {
            $q->where('is_approved', true)->with('user')->latest();
        }])->firstOrFail();

        // Tăng lượt xem
        $book->increment('view_count');

        // Sách liên quan cùng danh mục
        $relatedBooks = Book::active()
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->take(4)
            ->get();

        return view('books.show', compact('book', 'relatedBooks'));
    }
}
