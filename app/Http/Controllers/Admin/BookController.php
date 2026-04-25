<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $books      = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('admin.books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'author'       => 'required|string|max:100',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'cover_image'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except(['cover_image', '_token']);

        // slug
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Book::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $data['slug'] = $slug;

        // 🔥 Ưu tiên upload, không có thì auto lấy ảnh
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->uploadImage($request->file('cover_image'));
        } else {
            $data['cover_image'] = $this->getBookImage($request->title, $request->author);
        }

        Book::create($data);

        return redirect()->route('admin.books.index')->with('success', 'Sách đã được thêm.');
    }

    public function edit(Book $book)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->except(['cover_image', '_token', '_method']);

        // upload mới
        if ($request->hasFile('cover_image')) {
            if ($book->cover_image && str_starts_with($book->cover_image, 'uploads/')) {
                $oldPath = public_path($book->cover_image);
                if (file_exists($oldPath)) unlink($oldPath);
            }

            $data['cover_image'] = $this->uploadImage($request->file('cover_image'));
        }

        $book->update($data);

        return redirect()->route('admin.books.index')->with('success', 'Cập nhật thành công.');
    }

    public function destroy(Book $book)
    {
        if ($book->cover_image && str_starts_with($book->cover_image, 'uploads/')) {
            $oldPath = public_path($book->cover_image);
            if (file_exists($oldPath)) unlink($oldPath);
        }

        $book->delete();

        return back()->with('success', 'Đã xóa.');
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

            $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
                'q' => $query,
                'maxResults' => 1
            ]);

            $data = $response->json();

            if (!empty($data['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
                return $data['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
            }

        } catch (\Exception $e) {
            // lỗi thì fallback
        }

        return 'https://via.placeholder.com/200x300?text=No+Image';
    }

    // =========================
    // 🔥 AUTO UPDATE 100 SÁCH
    // =========================
    public function autoUpdateImages()
    {
        $books = Book::all();

        foreach ($books as $book) {
            if (!$book->cover_image || str_contains($book->cover_image, 'placeholder')) {

                $book->cover_image = $this->getBookImage($book->title, $book->author);
                $book->save();

                sleep(1); // tránh bị block API
            }
        }

        return "DONE update ảnh!";
    }

    // =========================
    private function uploadImage($file): string
    {
        $uploadDir = public_path('uploads/books');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($uploadDir, $filename);

        return 'uploads/books/' . $filename;
    }
}