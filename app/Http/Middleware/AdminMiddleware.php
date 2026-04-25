<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra nếu đăng nhập và có quyền admin (giả sử cột role của bạn là 1 hoặc 'admin')
        if (auth()->check() && auth()->user()->role == 'admin') {
            return $next($request);
        }

        // Nếu không phải admin thì đuổi về trang chủ hoặc báo lỗi 403
        abort(403, 'Bạn không có quyền truy cập trang này.');
    }
}
