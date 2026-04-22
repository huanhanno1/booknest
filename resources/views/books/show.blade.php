@extends('layouts.app')

@section('title', $book->title . ' - BookHaven')

@push('styles')
<style>
    /* ===== BOOK DETAIL ===== */
    .book-detail-wrap {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
    }

    /* Image panel */
    .book-image-panel {
        padding: 32px 24px;
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
    }
    .book-cover-frame {
        width: 100%;
        max-width: 280px;
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .book-cover-frame img {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        display: block;
    }
    .book-cover-placeholder {
        width: 100%;
        max-width: 280px;
        height: 360px;
        background: #F1F5F9;
        border: 1px solid var(--border);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #CBD5E1;
        font-size: 4rem;
    }
    .book-actions-panel {
        display: flex;
        flex-direction: column;
        gap: 8px;
        width: 100%;
        max-width: 280px;
    }

    /* Info panel */
    .book-info-panel {
        padding: 32px 32px;
    }
    .book-category-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        background: #EFF6FF;
        color: var(--primary);
        border-radius: 4px;
        font-size: 0.775rem;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 12px;
        transition: background 0.15s;
    }
    .book-category-badge:hover { background: #DBEAFE; color: var(--primary); }
    .book-title-main {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--text);
        line-height: 1.3;
        margin-bottom: 8px;
    }
    .book-author-line {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-bottom: 20px;
    }
    .book-author-line a {
        color: var(--primary);
        font-weight: 600;
    }

    /* Rating summary */
    .rating-summary {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 0;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        margin-bottom: 20px;
    }
    .rating-num {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--accent);
        line-height: 1;
    }
    .rating-stars { display: flex; gap: 2px; }
    .rating-stars i { font-size: 0.9rem; color: var(--accent); }
    .rating-count { font-size: 0.8rem; color: var(--text-muted); }

    /* Specs table */
    .specs-table { width: 100%; margin-bottom: 20px; }
    .specs-table tr td {
        padding: 7px 0;
        font-size: 0.875rem;
        vertical-align: top;
    }
    .specs-table tr td:first-child {
        color: var(--text-muted);
        width: 150px;
        padding-right: 16px;
    }
    .specs-table tr td:last-child { font-weight: 500; color: var(--text); }
    .specs-table tr:not(:last-child) td {
        border-bottom: 1px solid #F1F5F9;
    }

    /* Price block */
    .price-block {
        padding: 20px;
        background: #F8FAFC;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid var(--border);
    }
    .price-current {
        font-size: 2rem;
        font-weight: 800;
        color: var(--danger);
        line-height: 1;
    }
    .price-current.no-sale { color: var(--primary); }
    .price-original {
        font-size: 1rem;
        color: var(--text-muted);
        text-decoration: line-through;
        margin-left: 10px;
    }
    .price-discount-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        background: #FEF2F2;
        color: var(--danger);
        border: 1px solid #FECACA;
        border-radius: 4px;
        font-size: 0.775rem;
        font-weight: 700;
        margin-left: 8px;
    }
    .price-save-text {
        font-size: 0.8rem;
        color: var(--success);
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Stock status */
    .stock-in {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--success);
        font-size: 0.875rem;
        font-weight: 600;
    }
    .stock-out {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--danger);
        font-size: 0.875rem;
        font-weight: 600;
    }

    /* Quantity selector */
    .qty-selector {
        display: inline-flex;
        align-items: center;
        border: 1.5px solid var(--border);
        border-radius: 6px;
        overflow: hidden;
        height: 40px;
    }
    .qty-btn {
        width: 36px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #F8FAFC;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
        font-size: 0.9rem;
    }
    .qty-btn:hover { background: #EFF6FF; color: var(--primary); }
    .qty-input {
        width: 52px;
        height: 100%;
        border: none;
        border-left: 1.5px solid var(--border);
        border-right: 1.5px solid var(--border);
        text-align: center;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text);
        background: #fff;
        outline: none;
        -moz-appearance: textfield;
    }
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; }

    /* Add to cart form */
    .cart-form { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .btn-add-cart {
        flex: 1;
        min-width: 180px;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 10px 24px;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background 0.15s;
        cursor: pointer;
    }
    .btn-add-cart:hover { background: var(--primary-dark); }
    .btn-wishlist {
        height: 40px;
        width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid var(--border);
        border-radius: 6px;
        background: #fff;
        color: var(--text-muted);
        cursor: pointer;
        transition: border-color 0.15s, color 0.15s;
        font-size: 1rem;
    }
    .btn-wishlist:hover { border-color: #FECACA; color: var(--danger); }

    /* ===== DESCRIPTION ===== */
    .description-section {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 28px 32px;
    }
    .description-text {
        color: var(--text);
        line-height: 1.8;
        font-size: 0.9rem;
    }
    .description-text.collapsed {
        max-height: 200px;
        overflow: hidden;
        position: relative;
    }
    .description-text.collapsed::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 60px;
        background: linear-gradient(transparent, var(--card));
    }
    .btn-read-more {
        background: none;
        border: none;
        color: var(--primary);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        padding: 8px 0 0;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* ===== REVIEWS ===== */
    .reviews-section {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 28px 32px;
    }
    .review-item {
        padding: 20px 0;
        border-bottom: 1px solid var(--border);
    }
    .review-item:last-of-type { border-bottom: none; }
    .reviewer-avatar {
        width: 38px;
        height: 38px;
        background: var(--primary);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    .reviewer-name { font-weight: 600; font-size: 0.9rem; color: var(--text); }
    .review-date { font-size: 0.775rem; color: var(--text-muted); }
    .review-stars { display: flex; gap: 2px; margin: 4px 0; }
    .review-stars i { font-size: 0.825rem; color: var(--accent); }
    .review-stars i.empty { color: #CBD5E1; }
    .review-comment { font-size: 0.875rem; color: var(--text); line-height: 1.6; margin-top: 8px; }

    /* Review form */
    .review-form-wrap {
        background: #F8FAFC;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 24px;
        margin-top: 24px;
    }
    .review-form-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 16px;
    }
    .star-picker { display: flex; gap: 6px; margin-bottom: 4px; }
    .star-picker label {
        cursor: pointer;
        font-size: 1.75rem;
        color: #CBD5E1;
        transition: color 0.1s, transform 0.1s;
    }
    .star-picker label:hover { transform: scale(1.1); }
    .star-picker input[type="radio"] { display: none; }
    .star-hint { font-size: 0.775rem; color: var(--text-muted); margin-bottom: 16px; }

    .login-prompt {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        background: #F8FAFC;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.875rem;
        color: var(--text-muted);
        margin-top: 20px;
    }
    .login-prompt i { font-size: 1.2rem; color: var(--text-muted); }

    @media (max-width: 767.98px) {
        .book-image-panel { border-right: none; border-bottom: 1px solid var(--border); padding: 24px 16px; }
        .book-info-panel { padding: 24px 16px; }
        .book-title-main { font-size: 1.3rem; }
        .description-section, .reviews-section { padding: 20px 16px; }
        .review-form-wrap { padding: 16px; }
        .cart-form { flex-direction: column; align-items: stretch; }
        .btn-add-cart { min-width: unset; }
    }
</style>
@endpush

@section('content')
<div class="container" style="margin-top: 24px; margin-bottom: 48px;">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Kho sách</a></li>
            @if($book->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('books.index', ['category' => $book->category->slug]) }}">
                        {{ $book->category->name }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active">{{ Str::limit($book->title, 50) }}</li>
        </ol>
    </nav>

    {{-- ===== BOOK DETAIL CARD ===== --}}
    <div class="book-detail-wrap mb-4">
        <div class="row g-0">

            {{-- Ảnh bìa --}}
            <div class="col-md-4 col-lg-3 book-image-panel">
                @if($book->cover_image)
                    <div class="book-cover-frame">
                        <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}">
                    </div>
                @else
                    <div class="book-cover-placeholder">
                        <i class="bi bi-book"></i>
                    </div>
                @endif

                <div class="book-actions-panel">
                    {{-- Mua ngay --}}
                    @if(!isset($book->stock) || $book->stock > 0)
                    <a href="{{ route('cart.index') }}"
                       style="text-align:center; padding:10px; background:#F8FAFC; border:1.5px solid var(--border); border-radius:6px; font-size:0.825rem; font-weight:600; color:var(--text-muted); transition:border-color 0.15s;"
                       onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)';"
                       onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-muted)';">
                        <i class="bi bi-bag-check me-1"></i>Xem giỏ hàng
                    </a>
                    @endif

                    {{-- Chia sẻ --}}
                    <button type="button"
                            style="text-align:center; padding:10px; background:#F8FAFC; border:1.5px solid var(--border); border-radius:6px; font-size:0.825rem; font-weight:600; color:var(--text-muted); transition:border-color 0.15s; cursor:pointer;"
                            onclick="navigator.share ? navigator.share({title:'{{ addslashes($book->title) }}', url:window.location.href}) : (navigator.clipboard.writeText(window.location.href), alert('Đã sao chép link'));"
                            onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)';"
                            onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-muted)';">
                        <i class="bi bi-share me-1"></i>Chia sẻ
                    </button>
                </div>
            </div>

            {{-- Thông tin sách --}}
            <div class="col-md-8 col-lg-9 book-info-panel">

                {{-- Danh mục --}}
                @if($book->category)
                    <a href="{{ route('books.index', ['category' => $book->category->slug]) }}"
                       class="book-category-badge">
                        <i class="bi bi-tag"></i>{{ $book->category->name }}
                    </a>
                @endif

                {{-- Tiêu đề --}}
                <h1 class="book-title-main">{{ $book->title }}</h1>

                {{-- Tác giả --}}
                <p class="book-author-line">
                    <i class="bi bi-person me-1"></i>
                    Tác giả: <a href="{{ route('books.index', ['search' => $book->author]) }}">{{ $book->author }}</a>
                </p>

                {{-- Rating tổng --}}
                @if($book->reviews->count() > 0)
                @php
                    $avgRating = $book->reviews->avg('rating');
                @endphp
                <div class="rating-summary">
                    <span class="rating-num">{{ number_format($avgRating, 1) }}</span>
                    <div>
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($avgRating))
                                    <i class="bi bi-star-fill"></i>
                                @elseif($i - $avgRating < 1)
                                    <i class="bi bi-star-half"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="rating-count">{{ $book->reviews->count() }} đánh giá</span>
                    </div>
                </div>
                @endif

                {{-- Thông số sách --}}
                <table class="specs-table">
                    <tbody>
                        @if($book->publisher)
                        <tr>
                            <td><i class="bi bi-building me-2" style="color:var(--text-muted);"></i>Nhà xuất bản</td>
                            <td>{{ $book->publisher }}</td>
                        </tr>
                        @endif
                        @if($book->publish_year)
                        <tr>
                            <td><i class="bi bi-calendar3 me-2" style="color:var(--text-muted);"></i>Năm xuất bản</td>
                            <td>{{ $book->publish_year }}</td>
                        </tr>
                        @endif
                        @if($book->pages)
                        <tr>
                            <td><i class="bi bi-file-earmark-text me-2" style="color:var(--text-muted);"></i>Số trang</td>
                            <td>{{ number_format($book->pages) }} trang</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                {{-- Giá --}}
                <div class="price-block">
                    <div class="d-flex align-items-center flex-wrap gap-1">
                        @if($book->sale_price)
                            <span class="price-current">{{ number_format($book->sale_price, 0, ',', '.') }}đ</span>
                            <span class="price-original">{{ number_format($book->price, 0, ',', '.') }}đ</span>
                            <span class="price-discount-badge">
                                -{{ round((1 - $book->sale_price / $book->price) * 100) }}%
                            </span>
                        @else
                            <span class="price-current no-sale">{{ number_format($book->price, 0, ',', '.') }}đ</span>
                        @endif
                    </div>
                    @if($book->sale_price)
                        <div class="price-save-text">
                            <i class="bi bi-piggy-bank"></i>
                            Tiết kiệm {{ number_format($book->price - $book->sale_price, 0, ',', '.') }}đ
                        </div>
                    @endif

                    {{-- Tình trạng kho --}}
                    <div class="mt-3">
                        @if(isset($book->stock) && $book->stock > 0)
                            <span class="stock-in">
                                <i class="bi bi-check-circle-fill"></i>
                                Còn hàng ({{ number_format($book->stock) }} cuốn)
                            </span>
                        @elseif(isset($book->stock) && $book->stock === 0)
                            <span class="stock-out">
                                <i class="bi bi-x-circle-fill"></i>
                                Tạm hết hàng
                            </span>
                        @else
                            <span class="stock-in">
                                <i class="bi bi-check-circle-fill"></i>
                                Còn hàng
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Thêm vào giỏ --}}
                @if(!isset($book->stock) || $book->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <div class="cart-form">
                        <div class="qty-selector">
                            <button type="button" class="qty-btn"
                                    onclick="let i=this.nextElementSibling; if(i.value>1) i.value--;">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" name="quantity" value="1"
                                   min="1" max="{{ $book->stock ?? 99 }}"
                                   class="qty-input">
                            <button type="button" class="qty-btn"
                                    onclick="let i=this.previousElementSibling; let max=parseInt(i.max)||99; if(parseInt(i.value)<max) i.value=parseInt(i.value)+1;">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <button type="submit" class="btn-add-cart">
                            <i class="bi bi-cart-plus"></i>
                            Thêm vào giỏ hàng
                        </button>
                        @auth
                        <form action="{{ route('wishlist.toggle', $book) }}" method="POST" style="display:contents;">
                            @csrf
                            <button type="submit" class="btn-wishlist" title="Thêm vào yêu thích">
                                <i class="bi bi-heart"></i>
                            </button>
                        </form>
                        @endauth
                    </div>
                </form>
                @else
                    @auth
                    <form action="{{ route('wishlist.toggle', $book) }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-heart me-1"></i>Lưu vào yêu thích
                        </button>
                    </form>
                    @endauth
                @endif

            </div>
        </div>
    </div>

    {{-- ===== MÔ TẢ SÁCH ===== --}}
    @if($book->description)
    <div class="description-section mb-4">
        <h2 class="section-title">Mô Tả Sách</h2>
        <div class="description-text collapsed" id="descText">
            {!! nl2br(e($book->description)) !!}
        </div>
        <button class="btn-read-more" id="btnReadMore" onclick="toggleDesc()">
            <span id="readMoreLabel">Xem thêm</span>
            <i class="bi bi-chevron-down" id="readMoreIcon"></i>
        </button>
    </div>
    @endif

    {{-- ===== ĐÁNH GIÁ ===== --}}
    <div class="reviews-section">
        <h2 class="section-title">Đánh Giá Từ Độc Giả</h2>

        {{-- Danh sách đánh giá --}}
        @forelse($book->reviews as $review)
        <div class="review-item">
            <div class="d-flex gap-3">
                <div class="reviewer-avatar">
                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                </div>
                <div class="flex-fill">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
                        <div>
                            <div class="reviewer-name">{{ $review->user->name }}</div>
                            <div class="review-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star-fill {{ $i > $review->rating ? 'empty' : '' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <span class="review-date">{{ $review->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($review->comment)
                        <p class="review-comment">{{ $review->comment }}</p>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div style="text-align:center; padding:32px 0; color:var(--text-muted);">
            <i class="bi bi-chat-square-text" style="font-size:2rem; display:block; margin-bottom:10px;"></i>
            <p style="font-size:0.875rem; margin:0;">Chưa có đánh giá nào. Hãy là người đầu tiên chia sẻ cảm nhận!</p>
        </div>
        @endforelse

        {{-- Form viết đánh giá --}}
        @auth
        <div class="review-form-wrap">
            <div class="review-form-title">
                <i class="bi bi-pencil-square me-2" style="color:var(--primary);"></i>
                Viết Đánh Giá Của Bạn
            </div>
            <form action="{{ route('reviews.store', $book) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Xếp hạng của bạn</label>
                    <div class="star-picker" id="starPicker">
                        @for($i = 1; $i <= 5; $i++)
                        <label for="star{{ $i }}" title="{{ $i }} sao">
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                            <i class="bi bi-star-fill" data-value="{{ $i }}"></i>
                        </label>
                        @endfor
                    </div>
                    <div class="star-hint" id="starHint">Chọn số sao để đánh giá</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nội dung nhận xét</label>
                    <textarea name="comment" class="form-control" rows="4"
                              placeholder="Chia sẻ cảm nhận của bạn về cuốn sách này... Sách có hữu ích không? Nội dung thú vị không?"></textarea>
                </div>

                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-send me-2"></i>Gửi đánh giá
                </button>
            </form>
        </div>
        @else
        <div class="login-prompt">
            <i class="bi bi-person-circle"></i>
            <span>
                <a href="{{ route('login') }}" style="color:var(--primary); font-weight:700;">Đăng nhập</a>
                để viết đánh giá và chia sẻ cảm nhận của bạn về cuốn sách này.
            </span>
        </div>
        @endauth
    </div>

</div>
@endsection

@push('scripts')
<script>
// ===== STAR PICKER =====
(function() {
    const picker = document.getElementById('starPicker');
    if (!picker) return;

    const labels = picker.querySelectorAll('label');
    const hint   = document.getElementById('starHint');
    const texts  = ['', 'Rất tệ', 'Tệ', 'Bình thường', 'Tốt', 'Xuất sắc'];

    function paint(count, permanent) {
        labels.forEach((lbl, idx) => {
            const icon = lbl.querySelector('i');
            icon.style.color = idx < count ? 'var(--accent)' : '#CBD5E1';
        });
        if (hint && count > 0) hint.textContent = texts[count];
    }

    let selected = 0;

    labels.forEach((lbl, idx) => {
        lbl.addEventListener('mouseenter', () => paint(idx + 1, false));
        lbl.addEventListener('mouseleave', () => paint(selected, true));
        lbl.querySelector('input').addEventListener('change', () => {
            selected = idx + 1;
            paint(selected, true);
        });
    });
})();

// ===== DESCRIPTION TOGGLE =====
function toggleDesc() {
    const el  = document.getElementById('descText');
    const lbl = document.getElementById('readMoreLabel');
    const ico = document.getElementById('readMoreIcon');
    if (!el) return;

    const collapsed = el.classList.toggle('collapsed');
    lbl.textContent = collapsed ? 'Xem thêm' : 'Thu gọn';
    ico.className   = collapsed ? 'bi bi-chevron-down' : 'bi bi-chevron-up';
}
</script>
@endpush
