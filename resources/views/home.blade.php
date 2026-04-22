@extends('layouts.app')

@section('title', 'BookHaven - Nhà Sách Trực Tuyến')

@push('styles')
<style>
    /* ===== HERO ===== */
    .hero-section {
        background: var(--primary);
        padding: 64px 0 56px;
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: -80px;
        right: -80px;
        width: 360px;
        height: 360px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
    }
    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -100px;
        left: -60px;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        background: rgba(255,255,255,0.03);
    }
    .hero-title {
        color: #fff;
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -0.5px;
        margin-bottom: 12px;
    }
    .hero-subtitle {
        color: #BFDBFE;
        font-size: 1.05rem;
        margin-bottom: 32px;
        line-height: 1.6;
    }
    .hero-search-wrap {
        max-width: 520px;
        margin: 0 auto;
    }
    .hero-search-wrap .input-group {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(0,0,0,0.15);
    }
    .hero-search-wrap .form-control {
        border: none;
        padding: 14px 18px;
        font-size: 1rem;
        background: transparent;
        box-shadow: none !important;
    }
    .hero-search-wrap .btn-hero-search {
        background: var(--accent);
        color: #fff;
        border: none;
        padding: 0 28px;
        font-weight: 700;
        font-size: 0.95rem;
        transition: background 0.15s;
        white-space: nowrap;
    }
    .hero-search-wrap .btn-hero-search:hover { background: #D97706; }
    .hero-stats {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin-top: 40px;
    }
    .hero-stat { text-align: center; }
    .hero-stat .stat-num {
        display: block;
        font-size: 1.5rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }
    .hero-stat .stat-label {
        font-size: 0.8rem;
        color: #BFDBFE;
        margin-top: 4px;
    }
    .hero-stat-divider {
        width: 1px;
        background: rgba(255,255,255,0.15);
        align-self: stretch;
    }

    /* ===== FEATURES BAR ===== */
    .features-bar {
        background: var(--card);
        border-bottom: 1px solid var(--border);
        padding: 0;
    }
    .feature-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px 20px;
        color: var(--text);
    }
    .feature-item i {
        color: var(--primary);
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .feature-item .feat-title {
        font-size: 0.85rem;
        font-weight: 600;
        line-height: 1.2;
    }
    .feature-item .feat-sub {
        font-size: 0.75rem;
        color: var(--text-muted);
    }
    .feature-divider {
        width: 1px;
        background: var(--border);
        align-self: stretch;
        margin: 12px 0;
    }

    /* ===== CATEGORY CARDS ===== */
    .cat-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 20px 16px;
        text-align: center;
        color: var(--text);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        transition: border-color 0.2s, transform 0.2s;
        cursor: pointer;
    }
    .cat-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        color: var(--primary);
        text-decoration: none;
    }
    .cat-icon {
        width: 48px;
        height: 48px;
        background: #EFF6FF;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: var(--primary);
        transition: background 0.2s;
    }
    .cat-card:hover .cat-icon { background: var(--primary); color: #fff; }
    .cat-name {
        font-size: 0.85rem;
        font-weight: 600;
        line-height: 1.3;
    }
    .cat-count {
        font-size: 0.75rem;
        color: var(--text-muted);
    }
    .cat-card:hover .cat-count { color: #93C5FD; }

    /* ===== SECTION HEADER ===== */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 20px;
    }
    .section-header .section-title { margin-bottom: 0; }
    .section-header .see-all {
        font-size: 0.825rem;
        color: var(--primary);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: gap 0.15s;
    }
    .section-header .see-all:hover { gap: 8px; }

    /* ===== PROMO BANNER ===== */
    .promo-banner {
        background: #1E40AF;
        border-radius: 8px;
        padding: 32px 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
    }
    .promo-banner .promo-title {
        color: #fff;
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 8px;
        line-height: 1.3;
    }
    .promo-banner .promo-sub {
        color: #BFDBFE;
        font-size: 0.9rem;
    }
    .promo-banner .btn-promo {
        background: var(--accent);
        color: #fff;
        font-weight: 700;
        padding: 12px 28px;
        border-radius: 6px;
        border: none;
        white-space: nowrap;
        transition: background 0.15s;
    }
    .promo-banner .btn-promo:hover { background: #D97706; color: #fff; }

    @media (max-width: 767.98px) {
        .hero-title { font-size: 1.75rem; }
        .hero-subtitle { font-size: 0.9rem; }
        .hero-stats { gap: 20px; }
        .hero-stat .stat-num { font-size: 1.2rem; }
        .feature-item { padding: 12px 16px; }
        .promo-banner { flex-direction: column; text-align: center; padding: 24px 20px; }
        .promo-banner .promo-title { font-size: 1.2rem; }
    }
    @media (max-width: 575.98px) {
        .hero-section { padding: 40px 0 36px; }
        .hero-stats { gap: 12px; }
    }
</style>
@endpush

@section('content')

{{-- ===== HERO ===== --}}
<section class="hero-section">
    <div class="container text-center position-relative" style="z-index:1;">
        <h1 class="hero-title">Khám Phá Kho Sách Phong Phú</h1>
        <p class="hero-subtitle">
            Hàng ngàn đầu sách chất lượng &mdash; Giao hàng nhanh toàn quốc &mdash; Giá tốt mỗi ngày
        </p>

        <div class="hero-search-wrap">
            <form action="{{ route('books.index') }}" method="GET">
                <div class="input-group">
                    <input class="form-control"
                           type="search"
                           name="search"
                           placeholder="Tìm kiếm theo tên sách, tác giả, nhà xuất bản..."
                           value="{{ request('search') }}"
                           autocomplete="off">
                    <button class="btn-hero-search" type="submit">
                        <i class="bi bi-search me-2"></i>Tìm kiếm
                    </button>
                </div>
            </form>
        </div>

        <div class="hero-stats">
            <div class="hero-stat">
                <span class="stat-num">10,000+</span>
                <span class="stat-label">Đầu sách</span>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
                <span class="stat-num">50,000+</span>
                <span class="stat-label">Khách hàng</span>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
                <span class="stat-num">100+</span>
                <span class="stat-label">Danh mục</span>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
                <span class="stat-num">4.9</span>
                <span class="stat-label">Đánh giá</span>
            </div>
        </div>
    </div>
</section>

{{-- ===== FEATURES BAR ===== --}}
<div class="features-bar d-none d-md-block">
    <div class="container">
        <div class="d-flex align-items-center">
            <div class="feature-item flex-fill">
                <i class="bi bi-truck"></i>
                <div>
                    <div class="feat-title">Giao hàng miễn phí</div>
                    <div class="feat-sub">Đơn từ 299.000đ</div>
                </div>
            </div>
            <div class="feature-divider"></div>
            <div class="feature-item flex-fill">
                <i class="bi bi-shield-check"></i>
                <div>
                    <div class="feat-title">Đảm bảo chất lượng</div>
                    <div class="feat-sub">Sách chính hãng 100%</div>
                </div>
            </div>
            <div class="feature-divider"></div>
            <div class="feature-item flex-fill">
                <i class="bi bi-arrow-repeat"></i>
                <div>
                    <div class="feat-title">Đổi trả dễ dàng</div>
                    <div class="feat-sub">Trong vòng 30 ngày</div>
                </div>
            </div>
            <div class="feature-divider"></div>
            <div class="feature-item flex-fill">
                <i class="bi bi-headset"></i>
                <div>
                    <div class="feat-title">Hỗ trợ 24/7</div>
                    <div class="feat-sub">Luôn sẵn sàng phục vụ</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== DANH MỤC SÁCH ===== --}}
@if($categories->count())
<section class="container" style="margin-top: 56px;">
    <div class="section-header">
        <h2 class="section-title">Danh Mục Sách</h2>
        <a href="{{ route('books.index') }}" class="see-all">
            Xem tất cả <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="row row-cols-3 row-cols-sm-4 row-cols-md-6 g-3">
        @foreach($categories as $cat)
        <div class="col">
            <a href="{{ route('books.index', ['category' => $cat->slug]) }}" class="cat-card text-decoration-none">
                <div class="cat-icon">
                    @php
                        $catIcons = [
                            'van-hoc' => 'bi-book',
                            'kinh-te' => 'bi-graph-up',
                            'thieu-nhi' => 'bi-stars',
                            'lich-su' => 'bi-hourglass-split',
                            'khoa-hoc' => 'bi-flask',
                            'tam-ly' => 'bi-brain',
                            'ngon-ngu' => 'bi-translate',
                            'the-thao' => 'bi-trophy',
                        ];
                        $icon = $catIcons[$cat->slug] ?? 'bi-bookmark';
                    @endphp
                    <i class="bi {{ $icon }}"></i>
                </div>
                <span class="cat-name">{{ $cat->name }}</span>
                @if(isset($cat->active_books_count))
                    <span class="cat-count">{{ number_format($cat->active_books_count) }} cuốn</span>
                @endif
            </a>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ===== SÁCH NỔI BẬT ===== --}}
@if($featuredBooks->count())
<section class="container" style="margin-top: 56px;">
    <div class="section-header">
        <h2 class="section-title">Sách Nổi Bật</h2>
        <a href="{{ route('books.index') }}" class="see-all">
            Xem tất cả <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-4 g-3">
        @foreach($featuredBooks as $book)
        <div class="col">
            <x-book-card :book="$book" />
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ===== PROMO BANNER ===== --}}
<section class="container" style="margin-top: 56px;">
    <div class="promo-banner">
        <div>
            <div class="promo-title">Ưu đãi dành cho thành viên mới</div>
            <div class="promo-sub">Đăng ký tài khoản ngay hôm nay và nhận voucher giảm 15% cho đơn hàng đầu tiên</div>
        </div>
        @guest
            <a href="{{ route('register') }}" class="btn-promo">
                Đăng ký ngay <i class="bi bi-arrow-right ms-1"></i>
            </a>
        @else
            <a href="{{ route('books.index') }}" class="btn-promo">
                Mua sắm ngay <i class="bi bi-arrow-right ms-1"></i>
            </a>
        @endguest
    </div>
</section>

{{-- ===== SÁCH MỚI NHẤT ===== --}}
@if($newBooks->count())
<section class="container" style="margin-top: 56px;">
    <div class="section-header">
        <h2 class="section-title">Sách Mới Nhất</h2>
        <a href="{{ route('books.index', ['sort' => 'newest']) }}" class="see-all">
            Xem tất cả <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-4 g-3">
        @foreach($newBooks as $book)
        <div class="col">
            <x-book-card :book="$book" />
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ===== SÁCH BÁN CHẠY ===== --}}
@if($bestSellingBooks->count())
<section class="container" style="margin-top: 56px; margin-bottom: 72px;">
    <div class="section-header">
        <h2 class="section-title">Sách Bán Chạy</h2>
        <a href="{{ route('books.index', ['sort' => 'bestselling']) }}" class="see-all">
            Xem tất cả <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-4 g-3">
        @foreach($bestSellingBooks as $book)
        <div class="col">
            <x-book-card :book="$book" />
        </div>
        @endforeach
    </div>
</section>
@endif

@endsection
