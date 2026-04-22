<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredBooks = Book::active()->featured()->with('category')->take(8)->get();
        $newBooks = Book::active()->with('category')->latest()->take(8)->get();
        $bestSellingBooks = Book::active()->bestSelling()->with('category')->take(8)->get();
        $categories = Category::where('is_active', true)->withCount('activeBooks')->get();

        return view('home', compact('featuredBooks', 'newBooks', 'bestSellingBooks', 'categories'));
    }
}
