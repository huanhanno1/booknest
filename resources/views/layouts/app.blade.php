<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BookHaven - Nhà Sách Trực Tuyến')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* ===== BIẾN MÀU ===== */
        :root {
            --primary:       #2563EB;
            --primary-dark:  #1E40AF;
            --accent:        #F59E0B;
            --bg:            #F8FAFC;
            --card:          #FFFFFF;
            --text:          #1E293B;
            --text-muted:    #64748B;
            --success:       #16A34A;
            --danger:        #DC2626;
            --border:        #E2E8F0;
            --shadow-xs:     0 1px 3px rgba(0,0,0,0.08);
        }

        /* ===== BASE ===== */
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-size: 15px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        a { color: var(--primary); text-decoration: none; transition: color 0.15s; }
        a:hover { color: var(--primary-dark); }
        img { max-width: 100%; }

        /* ===== NAVBAR ===== */
        .navbar-main {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            border-top: 3px solid var(--primary);
            box-shadow: var(--shadow-xs);
            padding: 0;
        }
        .navbar-main .container {
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--primary) !important;
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -0.3px;
            transition: opacity 0.15s;
        }
        .navbar-brand:hover { opacity: 0.85; }
        .navbar-brand .brand-icon {
            width: 34px;
            height: 34px;
            background: var(--primary);
            color: #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        /* Search bar */
        .search-wrap {
            flex: 1;
            max-width: 440px;
            margin: 0 24px;
        }
        .search-wrap .input-group {
            border: 1.5px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            transition: border-color 0.2s;
        }
        .search-wrap .input-group:focus-within {
            border-color: var(--primary);
        }
        .search-wrap .form-control {
            border: none;
            font-size: 0.9rem;
            padding: 8px 14px;
            background: #fff;
            color: var(--text);
            box-shadow: none !important;
        }
        .search-wrap .form-control::placeholder { color: var(--text-muted); }
        .search-wrap .btn-search {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 0 16px;
            font-size: 0.9rem;
            transition: background 0.15s;
        }
        .search-wrap .btn-search:hover { background: var(--primary-dark); }

        /* Nav links */
        .navbar-nav .nav-link {
            color: var(--text) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 6px 12px !important;
            border-radius: 6px;
            transition: background 0.15s, color 0.15s;
        }
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--primary) !important;
            background: #EFF6FF;
        }

        /* Cart icon */
        .nav-cart {
            position: relative;
            display: inline-flex;
            align-items: center;
        }
        .nav-cart .cart-count {
            position: absolute;
            top: -6px;
            right: -8px;
            background: var(--danger);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            line-height: 1;
        }

        /* User dropdown */
        .user-avatar {
            width: 30px;
            height: 30px;
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            margin-right: 6px;
        }
        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 6px;
            min-width: 200px;
        }
        .dropdown-item {
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 0.875rem;
            color: var(--text);
            transition: background 0.15s;
        }
        .dropdown-item:hover { background: #EFF6FF; color: var(--primary); }
        .dropdown-divider { margin: 4px 0; border-color: var(--border); }

        /* Auth buttons */
        .btn-nav-login {
            font-size: 0.875rem;
            color: var(--text);
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 6px;
            border: 1.5px solid var(--border);
            transition: border-color 0.15s, color 0.15s;
        }
        .btn-nav-login:hover { border-color: var(--primary); color: var(--primary); }
        .btn-nav-register {
            font-size: 0.875rem;
            background: var(--primary);
            color: #fff !important;
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 6px;
            border: none;
            transition: background 0.15s;
        }
        .btn-nav-register:hover { background: var(--primary-dark); }

        /* ===== FLASH MESSAGES ===== */
        .flash-wrap { padding-top: 12px; padding-bottom: 0; }
        .flash-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            border: 1px solid transparent;
        }
        .flash-alert.flash-success {
            background: #F0FDF4;
            border-color: #BBF7D0;
            color: #15803D;
        }
        .flash-alert.flash-error {
            background: #FEF2F2;
            border-color: #FECACA;
            color: #B91C1C;
        }
        .flash-alert .flash-icon { font-size: 1rem; flex-shrink: 0; }
        .flash-alert .btn-close { margin-left: auto; font-size: 0.75rem; opacity: 0.6; }

        /* ===== BUTTONS ===== */
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            font-weight: 600;
            transition: background 0.15s, border-color 0.15s;
        }
        .btn-primary:hover, .btn-primary:focus {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
            font-weight: 500;
        }
        .btn-outline-primary:hover {
            background: var(--primary);
            border-color: var(--primary);
        }
        .btn-accent {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
            font-weight: 600;
            transition: background 0.15s;
        }
        .btn-accent:hover { background: #D97706; border-color: #D97706; color: #fff; }

        /* ===== BOOK CARD ===== */
        .book-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .book-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .book-card .card-img-wrap {
            position: relative;
            overflow: hidden;
        }
        .book-card .card-img-top {
            width: 100%;
            height: 260px;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }
        .book-card:hover .card-img-top { transform: scale(1.02); }
        .book-card .card-body {
            padding: 14px 16px 16px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .book-card .book-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
            min-height: 40px;
            margin-bottom: 4px;
        }
        .book-card .book-title:hover { color: var(--primary); }
        .book-card .book-author {
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-bottom: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .book-card .book-price-wrap { margin-top: auto; }
        .book-card .price-sale {
            color: var(--danger);
            font-weight: 700;
            font-size: 1.05rem;
        }
        .book-card .price-original {
            color: var(--text-muted);
            font-size: 0.8rem;
            text-decoration: line-through;
            margin-left: 5px;
        }
        .book-card .price-normal {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.05rem;
        }

        /* Sale badge */
        .badge-sale {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--danger);
            color: #fff;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            line-height: 1.4;
        }

        /* Book placeholder */
        .book-placeholder {
            width: 100%;
            height: 260px;
            background: #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #CBD5E1;
            font-size: 3rem;
        }

        /* ===== SIDEBAR CATEGORY ===== */
        .sidebar-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .sidebar-card .sidebar-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            background: #F8FAFC;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sidebar-card .sidebar-body { padding: 8px; }

        .category-nav-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            border-radius: 6px;
            color: var(--text);
            font-size: 0.875rem;
            transition: background 0.15s, color 0.15s;
            cursor: pointer;
        }
        .category-nav-item:hover { background: #EFF6FF; color: var(--primary); }
        .category-nav-item.active {
            background: #EFF6FF;
            color: var(--primary);
            font-weight: 600;
            border-left: 3px solid var(--primary);
            padding-left: 7px;
        }
        .category-nav-item .cat-count {
            margin-left: auto;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* ===== PAGINATION ===== */
        .pagination { gap: 4px; }
        .page-link {
            color: var(--primary);
            border: 1px solid var(--border);
            border-radius: 6px !important;
            padding: 6px 12px;
            font-size: 0.875rem;
            transition: background 0.15s, color 0.15s;
        }
        .page-link:hover { background: #EFF6FF; border-color: var(--primary); color: var(--primary); }
        .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }
        .page-item.disabled .page-link { color: #CBD5E1; border-color: var(--border); }

        /* ===== SECTION TITLE ===== */
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text);
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--primary);
            border-radius: 2px;
        }

        /* ===== STAR RATING ===== */
        .star-rating .bi-star-fill { color: var(--accent); }
        .star-rating .bi-star-half { color: var(--accent); }
        .star-rating .bi-star { color: #CBD5E1; }

        /* ===== BREADCRUMB ===== */
        .breadcrumb { font-size: 0.825rem; }
        .breadcrumb-item + .breadcrumb-item::before { color: #CBD5E1; }
        .breadcrumb-item.active { color: var(--text-muted); }

        /* ===== FORM CONTROLS ===== */
        .form-control, .form-select {
            border-color: var(--border);
            border-radius: 6px;
            font-size: 0.875rem;
            color: var(--text);
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
        }
        .form-label {
            font-size: 0.825rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 5px;
        }

        /* ===== TABLE ===== */
        .table-info-row td {
            padding: 6px 0;
            vertical-align: top;
            font-size: 0.875rem;
        }
        .table-info-row td:first-child {
            color: var(--text-muted);
            width: 140px;
            padding-right: 12px;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: #1E293B;
            color: #94A3B8;
            padding: 48px 0 0;
            margin-top: 64px;
        }
        .footer-brand {
            font-size: 1.2rem;
            font-weight: 800;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }
        .footer-brand .brand-icon {
            width: 30px;
            height: 30px;
            background: var(--primary);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            color: #fff;
        }
        .footer p { font-size: 0.875rem; line-height: 1.7; }
        .footer-heading {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #fff;
            margin-bottom: 16px;
        }
        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 8px; }
        .footer-links a {
            color: #94A3B8;
            font-size: 0.875rem;
            transition: color 0.15s;
        }
        .footer-links a:hover { color: #fff; }
        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.875rem;
            margin-bottom: 10px;
        }
        .footer-contact-item i { color: var(--primary); margin-top: 2px; flex-shrink: 0; }
        .footer-bottom {
            border-top: 1px solid #334155;
            padding: 16px 0;
            margin-top: 40px;
        }
        .footer-bottom p { font-size: 0.8rem; margin: 0; color: #64748B; }

        /* ===== UTILITIES ===== */
        .text-primary-custom { color: var(--primary); }
        .text-muted-custom { color: var(--text-muted); }
        .text-danger-custom { color: var(--danger); }
        .bg-card { background: var(--card); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991.98px) {
            .search-wrap { max-width: 100%; margin: 10px 0; order: 3; width: 100%; }
            .navbar-main .container { flex-wrap: wrap; }
        }
        @media (max-width: 575.98px) {
            .book-card .card-img-top,
            .book-placeholder { height: 200px; }
            .section-title { font-size: 1.1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ===== NAVBAR ===== --}}
<nav class="navbar navbar-expand-lg navbar-main sticky-top">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand" href="{{ url('/') }}">
            <span class="brand-icon"><i class="bi bi-book-half"></i></span>
            BookHaven
        </a>

        {{-- Toggle mobile --}}
        <button class="navbar-toggler border-0 ms-auto me-2" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarMain"
                style="padding: 4px 8px;">
            <i class="bi bi-list fs-5" style="color: var(--text);"></i>
        </button>

        {{-- Collapse --}}
        <div class="collapse navbar-collapse" id="navbarMain">
            {{-- Search --}}
            <div class="search-wrap mx-auto">
                <form action="{{ route('books.index') }}" method="GET">
                    <div class="input-group">
                        <input class="form-control"
                               type="search"
                               name="search"
                               placeholder="Tìm kiếm sách, tác giả..."
                               value="{{ request('search') }}"
                               autocomplete="off">
                        <button class="btn-search" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Nav items --}}
            <ul class="navbar-nav align-items-center gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('books.index') ? 'active' : '' }}"
                       href="{{ route('books.index') }}">
                        Kho sách
                    </a>
                </li>

                @auth
                    {{-- Giỏ hàng --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <span class="nav-cart">
                                <i class="bi bi-bag fs-5"></i>
                                @php $cartCount = auth()->user()->cart?->items?->sum('quantity') ?? 0; @endphp
                                @if($cartCount > 0)
                                    <span class="cart-count">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                                @endif
                            </span>
                        </a>
                    </li>

                    {{-- User dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center"
                           href="#" data-bs-toggle="dropdown" style="gap:0;">
                            <span class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="d-none d-md-inline" style="font-size:0.875rem; max-width:120px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ auth()->user()->name }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(auth()->user()->isAdmin())
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2 text-primary-custom"></i>Quản trị
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    <i class="bi bi-bag-check me-2 text-muted-custom"></i>Đơn hàng của tôi
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('wishlist.index') }}">
                                    <i class="bi bi-heart me-2 text-muted-custom"></i>Danh sách yêu thích
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-gear me-2 text-muted-custom"></i>Thông tin tài khoản
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item" type="submit"
                                            style="color: var(--danger);">
                                        <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="btn-nav-login" href="{{ route('login') }}">Đăng nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn-nav-register ms-1" href="{{ route('register') }}">Đăng ký</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- ===== FLASH MESSAGES ===== --}}
@if(session('success') || session('error'))
<div class="container flash-wrap">
    @if(session('success'))
        <div class="flash-alert flash-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill flash-icon"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="flash-alert flash-error alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill flash-icon"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif
</div>
@endif

{{-- ===== MAIN CONTENT ===== --}}
<main>
    @yield('content')
</main>

{{-- ===== FOOTER ===== --}}
<footer class="footer">
    <div class="container">
        <div class="row g-4">
            {{-- Cột 1: Giới thiệu --}}
            <div class="col-md-4">
                <div class="footer-brand">
                    <span class="brand-icon"><i class="bi bi-book-half"></i></span>
                    BookHaven
                </div>
                <p>Nhà sách trực tuyến hàng đầu Việt Nam. Hàng ngàn đầu sách chất lượng, giao hàng nhanh toàn quốc với giá tốt nhất mỗi ngày.</p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="d-flex align-items-center justify-content-center"
                       style="width:32px; height:32px; background:#334155; border-radius:6px; color:#94A3B8; transition:background 0.15s;"
                       onmouseover="this.style.background='var(--primary)'; this.style.color='#fff';"
                       onmouseout="this.style.background='#334155'; this.style.color='#94A3B8';">
                        <i class="bi bi-facebook" style="font-size:0.875rem;"></i>
                    </a>
                    <a href="#" class="d-flex align-items-center justify-content-center"
                       style="width:32px; height:32px; background:#334155; border-radius:6px; color:#94A3B8; transition:background 0.15s;"
                       onmouseover="this.style.background='var(--primary)'; this.style.color='#fff';"
                       onmouseout="this.style.background='#334155'; this.style.color='#94A3B8';">
                        <i class="bi bi-instagram" style="font-size:0.875rem;"></i>
                    </a>
                    <a href="#" class="d-flex align-items-center justify-content-center"
                       style="width:32px; height:32px; background:#334155; border-radius:6px; color:#94A3B8; transition:background 0.15s;"
                       onmouseover="this.style.background='var(--primary)'; this.style.color='#fff';"
                       onmouseout="this.style.background='#334155'; this.style.color='#94A3B8';">
                        <i class="bi bi-youtube" style="font-size:0.875rem;"></i>
                    </a>
                </div>
            </div>

            {{-- Cột 2: Liên kết nhanh --}}
            <div class="col-6 col-md-2 offset-md-1">
                <p class="footer-heading">Danh mục</p>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}">Trang chủ</a></li>
                    <li><a href="{{ route('books.index') }}">Kho sách</a></li>
                    <li><a href="{{ route('books.index', ['sort' => 'bestselling']) }}">Bán chạy nhất</a></li>
                    <li><a href="{{ route('books.index', ['sort' => 'newest']) }}">Sách mới nhất</a></li>
                </ul>
            </div>

            {{-- Cột 3: Tài khoản --}}
            <div class="col-6 col-md-2">
                <p class="footer-heading">Tài khoản</p>
                <ul class="footer-links">
                    @auth
                        <li><a href="{{ route('orders.index') }}">Đơn hàng</a></li>
                        <li><a href="{{ route('wishlist.index') }}">Yêu thích</a></li>
                        <li><a href="{{ route('profile.edit') }}">Hồ sơ</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                        <li><a href="{{ route('register') }}">Đăng ký</a></li>
                    @endauth
                </ul>
            </div>

            {{-- Cột 4: Liên hệ --}}
            <div class="col-md-3">
                <p class="footer-heading">Liên hệ</p>
                <div class="footer-contact-item">
                    <i class="bi bi-envelope"></i>
                    <span>contact@bookhaven.vn</span>
                </div>
                <div class="footer-contact-item">
                    <i class="bi bi-telephone"></i>
                    <span>0123 456 789</span>
                </div>
                <div class="footer-contact-item">
                    <i class="bi bi-geo-alt"></i>
                    <span>54 Nguyễn Lương Bằng, Đà Nẵng</span>
                </div>
                <div class="footer-contact-item">
                    <i class="bi bi-clock"></i>
                    <span>Thứ 2 – Thứ 7: 8:00 – 20:00</span>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <p>&copy; {{ date('Y') }} BookHaven. Tất cả quyền được bảo lưu.</p>
            <p>Thiết kế bởi đội ngũ BookHaven</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
</script>
@stack('scripts')
</body>
</html>
