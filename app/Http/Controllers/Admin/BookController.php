<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'publisher'    => 'nullable|string|max:100',
            'publish_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'sale_price'   => 'nullable|numeric|min:0|lt:price',
            'stock'        => 'required|integer|min:0',
            'isbn'         => 'nullable|string|max:20|unique:books,isbn',
            'pages'        => 'nullable|integer|min:1',
            'is_active'    => 'boolean',
            'is_featured'  => 'boolean',
            'cover_image'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except(['cover_image', '_token']);
        $data['is_active']   = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);

        // Tạo slug
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Book::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $data['slug'] = $slug;

        // Upload ảnh bìa
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->uploadImage($request->file('cover_image'));
        }

        Book::create($data);

        return redirect()->route('admin.books.index')->with('success', 'Sách đã được thêm thành công.');
    }

    public function show(Book $book)
    {
        $book->load('category', 'reviews.user');
        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'author'       => 'required|string|max:100',
            'publisher'    => 'nullable|string|max:100',
            'publish_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'sale_price'   => 'nullable|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'isbn'         => 'nullable|string|max:20|unique:books,isbn,' . $book->id,
            'pages'        => 'nullable|integer|min:1',
            'is_active'    => 'boolean',
            'is_featured'  => 'boolean',
            'cover_image'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except(['cover_image', '_token', '_method']);
        $data['is_active']   = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);

        // Cập nhật slug nếu title thay đổi
        if ($request->title !== $book->title) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $count = 1;
            while (Book::where('slug', $slug)->where('id', '!=', $book->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            $data['slug'] = $slug;
        }

        // Upload ảnh bìa mới
        if ($request->hasFile('cover_image')) {
            // Xóa ảnh cũ nếu là ảnh local
            if ($book->cover_image && str_starts_with($book->cover_image, 'uploads/')) {
                $oldPath = public_path($book->cover_image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $data['cover_image'] = $this->uploadImage($request->file('cover_image'));
        }

        $book->update($data);

        return redirect()->route('admin.books.index')->with('success', 'Sách đã được cập nhật thành công.');
    }

    public function destroy(Book $book)
    {
        if ($book->cover_image && str_starts_with($book->cover_image, 'uploads/')) {
            $oldPath = public_path($book->cover_image);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Sách đã được xóa.');
    }

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
