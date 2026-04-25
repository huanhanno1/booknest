<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::active()->with('category');

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->firstOrFail();
            $query->where('category_id', $category->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

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
        $book = Book::active()
            ->where('slug', $slug)
            ->with([
                'category',
                'reviews' => function ($q) {
                    $q->where('is_approved', true)->with('user')->latest();
                }
            ])
            ->firstOrFail();

        $book->increment('view_count');

        $relatedBooks = Book::active()
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->take(4)
            ->get();

        return view('books.show', compact('book', 'relatedBooks'));
    }

    // =========================
    // 🔥 GOOGLE BOOKS API
    // =========================
    private function getBookImage($title, $author = null)
    {
        try {
            $query = 'intitle:' . $title;

            if ($author) {
                $query .= '+inauthor:' . $author;
            }

            $response = \Illuminate\Support\Facades\Http::get(
                'https://www.googleapis.com/books/v1/volumes',
                ['q' => $query, 'maxResults' => 1]
            );

            $data = $response->json();

            if (!empty($data['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
                return str_replace(
                    'http://',
                    'https://',
                    $data['items'][0]['volumeInfo']['imageLinks']['thumbnail']
                );
            }

        } catch (\Exception $e) {
        }

        // 🔥 fallback: tạo ảnh bìa có chữ (đẹp + đúng tên sách)
        return 'https://via.placeholder.com/200x300?text=' . urlencode($title);
    }

    // =========================
    // 🔥 AUTO UPDATE 100 SÁCH
    // =========================
    public function autoUpdateImages()
    {
        $books = Book::all();

        foreach ($books as $book) {

            // 🔥 BỎ điều kiện -> update toàn bộ
            $book->cover_image = $this->getBookImage($book->title, $book->author);
            $book->save();

            sleep(1);
        }

        return "DONE update ảnh!";
    }
}