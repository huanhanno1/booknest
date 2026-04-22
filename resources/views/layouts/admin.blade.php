<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị') — BookHaven Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* ===== VARIABLES ===== */
        :root {
            --primary:       #2563EB;
            --primary-dark:  #1D4ED8;
            --primary-light: #EFF6FF;
            --sidebar-bg:    #1E293B;
            --sidebar-hover: #273549;
            --sidebar-active:#1E3A5F;
            --sidebar-text:  #94A3B8;
            --sidebar-white: #F1F5F9;
            --bg:            #F1F5F9;
            --card:          #FFFFFF;
            --text:          #0F172A;
            --text-muted:    #64748B;
            --border:        #E2E8F0;
            --shadow:        0 1px 3px rgba(0,0,0,0.08);
        }

        /* ===== BASE ===== */
        *, *::before, *::after { box-sizing: border-box; }
        body {
            background: var(--bg);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--text);
            margin: 0;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 256px;
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform 0.25s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 20px 18px;
            border-bottom: 1px solid #334155;
            text-decoration: none;
        }
        .sidebar-brand-icon {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-brand-icon i { color: #fff; font-size: 1.1rem; }
        .sidebar-brand-name { color: #F8FAFC; font-weight: 700; font-size: 1.05rem; line-height: 1.2; }
        .sidebar-brand-sub  { color: var(--sidebar-text); font-size: 0.7rem; font-weight: 400; }

        .sidebar-section {
            padding: 20px 12px 8px;
        }
        .sidebar-section-label {
            color: #475569;
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0 8px;
            margin-bottom: 4px;
        }

        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 6px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background 0.15s, color 0.15s, border-color 0.15s;
            border-left: 3px solid transparent;
            margin-bottom: 2px;
            position: relative;
        }
        .sidebar-nav-item i {
            font-size: 1rem;
            width: 18px;
            text-align: center;
            flex-shrink: 0;
        }
        .sidebar-nav-item:hover {
            background: var(--sidebar-hover);
            color: var(--sidebar-white);
        }
        .sidebar-nav-item.active {
            background: var(--sidebar-active);
            color: #fff;
            border-left-color: var(--primary);
            font-weight: 600;
        }
        .sidebar-nav-item.active i {
            color: #60A5FA;
        }

        .sidebar-divider {
            height: 1px;
            background: #334155;
            margin: 8px 20px;
        }

        .sidebar-footer {
            padding: 12px;
            margin-top: auto;
            border-top: 1px solid #334155;
        }

        /* ===== SIDEBAR OVERLAY (mobile) ===== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1039;
        }
        .sidebar-overlay.show { display: block; }

        /* ===== MAIN CONTENT ===== */
        .main-wrapper {
            margin-left: 256px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-toggle {
            display: none;
            background: none;
            border: 1px solid var(--border);
            border-radius: 6px;
            width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 1.1rem;
        }
        .topbar-breadcrumb {
            font-size: 0.82rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .topbar-breadcrumb .bc-current {
            color: var(--text);
            font-weight: 600;
        }
        .topbar-right { display: flex; align-items: center; gap: 8px; }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text);
            font-size: 0.875rem;
            font-weight: 500;
            border: 1px solid var(--border);
            transition: background 0.15s;
        }
        .topbar-user:hover { background: var(--bg); color: var(--text); }
        .topbar-user-avatar {
            width: 30px;
            height: 30px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .topbar-user-avatar i { color: var(--primary); font-size: 0.9rem; }

        /* ===== PAGE CONTENT ===== */
        .page-content { padding: 24px; flex: 1; }

        /* ===== PAGE HEADER ===== */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .page-header-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text);
            margin: 0 0 2px;
        }
        .page-header-sub {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* ===== CARDS ===== */
        .card {
            background: var(--card);
            border: 1px solid var(--border) !important;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }
        .card-header {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 14px 20px;
            border-radius: 8px 8px 0 0 !important;
        }
        .card-header-title {
            font-size: 0.925rem;
            font-weight: 600;
            color: var(--text);
            margin: 0;
        }
        .card-body { padding: 20px; }
        .card-footer {
            background: #FAFBFC;
            border-top: 1px solid var(--border);
            padding: 10px 20px;
            border-radius: 0 0 8px 8px !important;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: var(--shadow);
        }
        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.35rem;
        }
        .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.1;
        }
        .stat-label {
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-top: 3px;
        }

        /* ===== TABLES ===== */
        .table {
            margin: 0;
        }
        .table thead th {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border);
            background: #FAFBFC;
            padding: 11px 16px;
            white-space: nowrap;
        }
        .table thead th:first-child { border-radius: 0; }
        .table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #F1F5F9;
            font-size: 0.875rem;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table-hover tbody tr:hover td { background: #F8FAFC; }
        .table tfoot td {
            padding: 12px 16px;
            font-size: 0.875rem;
        }

        /* ===== BADGES ===== */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
            line-height: 1;
        }
        .badge-status-pending  { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
        .badge-status-confirmed{ background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; }
        .badge-status-shipping { background: #F0FFFE; color: #065F46; border: 1px solid #A7F3D0; }
        .badge-status-delivered{ background: #F0FDF4; color: #166534; border: 1px solid #86EFAC; }
        .badge-status-cancelled{ background: #FFF1F2; color: #9F1239; border: 1px solid #FCA5A5; }
        .badge-active  { background: #F0FDF4; color: #166534; border: 1px solid #86EFAC; }
        .badge-inactive{ background: #FFF1F2; color: #9F1239; border: 1px solid #FCA5A5; }
        .badge-admin   { background: #FFF7ED; color: #9A3412; border: 1px solid #FDBA74; }
        .badge-user    { background: #F5F3FF; color: #5B21B6; border: 1px solid #C4B5FD; }
        .badge-category{ background: #F0F9FF; color: #075985; border: 1px solid #BAE6FD; }
        .badge-paid    { background: #F0FDF4; color: #166534; border: 1px solid #86EFAC; }
        .badge-unpaid  { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
        .badge-approved{ background: #F0FDF4; color: #166534; border: 1px solid #86EFAC; }
        .badge-pending-review { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }

        /* ===== BUTTONS ===== */
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: #fff;
        }
        .btn-outline-primary { border-color: var(--primary); color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); color: #fff; }

        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        /* ===== FORMS ===== */
        .form-label { font-size: 0.85rem; font-weight: 600; color: var(--text); margin-bottom: 5px; }
        .form-control, .form-select {
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 0.875rem;
            padding: 8px 12px;
            color: var(--text);
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        textarea.form-control { resize: vertical; min-height: 100px; }
        .form-text { font-size: 0.78rem; color: var(--text-muted); margin-top: 4px; }
        .form-check-input:checked { background-color: var(--primary); border-color: var(--primary); }

        /* ===== FILTER BAR ===== */
        .filter-bar {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 14px 20px;
            margin-bottom: 16px;
        }

        /* ===== STATUS TABS ===== */
        .status-tabs {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }
        .status-tab {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid var(--border);
            color: var(--text-muted);
            background: var(--card);
            transition: all 0.15s;
        }
        .status-tab:hover { border-color: var(--primary); color: var(--primary); }
        .status-tab.active { background: var(--primary); border-color: var(--primary); color: #fff; }
        .status-tab .tab-count {
            background: rgba(255,255,255,0.25);
            border-radius: 10px;
            padding: 1px 6px;
            font-size: 0.7rem;
        }
        .status-tab:not(.active) .tab-count {
            background: #F1F5F9;
            color: var(--text-muted);
        }

        /* ===== ALERTS ===== */
        .alert {
            border-radius: 8px;
            border: none;
            font-size: 0.875rem;
            padding: 12px 16px;
            margin-bottom: 16px;
        }
        .alert-success { background: #F0FDF4; color: #166534; border-left: 3px solid #22C55E; }
        .alert-danger  { background: #FFF1F2; color: #9F1239; border-left: 3px solid #F87171; }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
        }
        .empty-state-icon {
            width: 56px;
            height: 56px;
            background: #F1F5F9;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            font-size: 1.5rem;
            color: var(--text-muted);
        }
        .empty-state-title { font-size: 0.925rem; font-weight: 600; color: var(--text); margin-bottom: 4px; }
        .empty-state-sub   { font-size: 0.82rem; color: var(--text-muted); }

        /* ===== STOCK INDICATOR ===== */
        .stock-low  { color: #DC2626; font-weight: 600; }
        .stock-ok   { color: var(--text-muted); }

        /* ===== IMAGE PREVIEW ===== */
        .img-cover {
            border-radius: 5px;
            border: 1px solid var(--border);
            object-fit: cover;
        }
        .img-placeholder {
            border-radius: 5px;
            background: #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94A3B8;
        }

        /* ===== DROPDOWN ===== */
        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            font-size: 0.875rem;
            padding: 6px;
        }
        .dropdown-item {
            border-radius: 6px;
            padding: 8px 12px;
            color: var(--text);
        }
        .dropdown-item:hover { background: #F8FAFC; }
        .dropdown-divider { margin: 4px 0; }

        /* ===== PAGINATION ===== */
        .pagination { margin: 0; }
        .page-link {
            border-color: var(--border);
            color: var(--primary);
            font-size: 0.82rem;
            padding: 5px 10px;
            border-radius: 5px !important;
            margin: 0 2px;
        }
        .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }

        /* ===== REVIEW STARS ===== */
        .star-fill { color: #F59E0B; }
        .star-empty { color: #CBD5E1; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .topbar-toggle { display: inline-flex; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar Overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- Sidebar --}}
<aside class="sidebar" id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="bi bi-book-half"></i>
        </div>
        <div>
            <div class="sidebar-brand-name">BookHaven</div>
            <div class="sidebar-brand-sub">Bảng điều khiển</div>
        </div>
    </a>

    <div class="sidebar-section">
        <div class="sidebar-section-label">Tổng quan</div>
        <a class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
           href="{{ route('admin.dashboard') }}">
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-label">Quản lý nội dung</div>
        <a class="sidebar-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
           href="{{ route('admin.categories.index') }}">
            <i class="bi bi-folder2-open"></i>
            <span>Danh mục</span>
        </a>
        <a class="sidebar-nav-item {{ request()->routeIs('admin.books.*') ? 'active' : '' }}"
           href="{{ route('admin.books.index') }}">
            <i class="bi bi-book"></i>
            <span>Sách</span>
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-label">Bán hàng</div>
        <a class="sidebar-nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
           href="{{ route('admin.orders.index') }}">
            <i class="bi bi-bag-check"></i>
            <span>Đơn hàng</span>
        </a>
        <a class="sidebar-nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
           href="{{ route('admin.reviews.index') }}">
            <i class="bi bi-chat-square-text"></i>
            <span>Đánh giá</span>
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-label">Hệ thống</div>
        <a class="sidebar-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
           href="{{ route('admin.users.index') }}">
            <i class="bi bi-people"></i>
            <span>Người dùng</span>
        </a>
    </div>

    <div class="sidebar-divider"></div>

    <div class="sidebar-footer">
        <a class="sidebar-nav-item" href="{{ url('/') }}" target="_blank">
            <i class="bi bi-house"></i>
            <span>Xem trang web</span>
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-nav-item w-100 text-start" style="border:none;background:none;cursor:pointer;">
                <i class="bi bi-box-arrow-left"></i>
                <span>Đăng xuất</span>
            </button>
        </form>
    </div>
</aside>

{{-- Main Wrapper --}}
<div class="main-wrapper">
    {{-- Topbar --}}
    <header class="topbar">
        <div class="topbar-left">
            <button class="topbar-toggle" id="sidebarToggle" onclick="openSidebar()" aria-label="Mở menu">
                <i class="bi bi-list"></i>
            </button>
            <nav class="topbar-breadcrumb" aria-label="Đường dẫn">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none" style="color:var(--text-muted);">
                    <i class="bi bi-house-door" style="font-size:0.8rem;"></i>
                </a>
                <i class="bi bi-chevron-right" style="font-size:0.65rem;"></i>
                <span class="bc-current">@yield('title', 'Dashboard')</span>
            </nav>
        </div>
        <div class="topbar-right">
            <div class="dropdown">
                <a href="#" class="topbar-user dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="topbar-user-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" style="min-width:180px;">
                    <li>
                        <div class="px-3 py-2" style="border-bottom:1px solid var(--border);margin-bottom:4px;">
                            <div style="font-size:0.82rem;font-weight:600;color:var(--text);">{{ auth()->user()->name }}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">{{ auth()->user()->email }}</div>
                        </div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ url('/') }}" target="_blank">
                            <i class="bi bi-house me-2"></i> Xem trang web
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    {{-- Page Content --}}
    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" style="padding:10px;"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" style="padding:10px;"></button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('show');
        document.getElementById('sidebarOverlay').classList.add('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('show');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }
</script>
@stack('scripts')
</body>
</html>
