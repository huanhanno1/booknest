<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;


// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sach', [BookController::class, 'index'])->name('books.index');
Route::get('/sach/{slug}', [BookController::class, 'show'])->name('books.show');
Route::get('/auto-images', [BookController::class, 'autoUpdateImages']);

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/dang-nhap', [LoginController::class, 'login']);
    Route::get('/dang-ky', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/dang-ky', [RegisterController::class, 'register']);
});

Route::post('/dang-xuat', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Cart
    Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/gio-hang/them', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/gio-hang/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/gio-hang/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    // Orders
    Route::get('/don-hang', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/thanh-toan', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/dat-hang', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/don-hang/{order}', [OrderController::class, 'show'])->name('orders.show');

    // VNPay return
    Route::get('/vnpay-return', [OrderController::class, 'vnpayReturn'])->name('vnpay.return');

    // Reviews
    Route::post('/danh-gia/{book}', [ReviewController::class, 'store'])->name('reviews.store');

    // Wishlist
    Route::get('/yeu-thich', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/yeu-thich/{book}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Profile
    Route::get('/tai-khoan', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/tai-khoan', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/doi-mat-khau', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('books', AdminBookController::class);
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggleActive');
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/toggle-approval', [AdminReviewController::class, 'toggleApproval'])->name('reviews.toggleApproval');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
});
