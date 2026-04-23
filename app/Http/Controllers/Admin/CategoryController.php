<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')->latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'is_active'   => 'boolean',
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        Category::create([
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'is_active'   => 'boolean',
        ]);

        $slug = Str::slug($request->name);
        if ($slug !== $category->slug) {
            $originalSlug = $slug;
            $count = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
        }

        $category->update([
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật.');
    }

    public function destroy(Category $category)
    {
        if ($category->books()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục đang có sách. Hãy chuyển sách sang danh mục khác trước.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa.');
    }
}
