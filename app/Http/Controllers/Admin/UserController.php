<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? true : false);
        }

        $users = $query->withCount('orders')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function toggleActive(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Không thể vô hiệu hóa tài khoản admin.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'kích hoạt' : 'vô hiệu hóa';

        return back()->with('success', "Tài khoản \"{$user->name}\" đã được {$status}.");
    }
}
