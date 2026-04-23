<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks  = Book::count();
        $totalOrders = Order::count();
        $totalUsers  = User::where('role', 'customer')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');

        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        $topSellingBooks = Book::orderBy('sold_count', 'desc')
            ->with('category')
            ->take(5)
            ->get();

        $orderStats = [
            'pending'   => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'shipping'  => Order::where('status', 'shipping')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalBooks',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'recentOrders',
            'topSellingBooks',
            'orderStats'
        ));
    }
}
