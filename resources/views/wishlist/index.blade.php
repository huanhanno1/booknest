@extends('layouts.app')

@section('title', 'Sách Yêu Thích - BookHaven')

@push('styles')
<style>
.wishlist-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.wishlist-card:hover {
    border-color: #93C5FD;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}
.wishlist-cover {
    position: relative;
    display: block;
    overflow: hidden;
    background: var(--bg);
    aspect-ratio: 3/4;
}
.wishlist-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.2s;
}
.wishlist-card:hover .wishlist-cover img {
    transform: scale(1.02);
}
.wishlist-cover-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--border);
    font-size: 2.5rem;
}
.badge-sale {
    position: absolute;
    top: 8px;
    left: 8px;
    background: var(--danger);
    color: #fff;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 4px;
    letter-spacing: 0.03em;
    text-transform: uppercase;
}
.heart-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 30px;
    height: 30px;
    border-radius: 6px;
    background: rgba(255,255,255,0.95);
    border: 1px solid #FECACA;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--danger);
    font-size: 0.9rem;
}
.wishlist-body {
    padding: 0.875rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    gap: 4px;
}
.book-title-link {
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text);
    text-decoration: none;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.book-title-link:hover {
    color: var(--primary);
}
.book-author-text {
    font-size: 0.78rem;
    color: var(--text-muted);
}
.price-main {
    font-weight: 700;
    font-size: 1rem;
    color: var(--danger);
}
.price-old {
    font-size: 0.78rem;
    color: var(--text-muted);
    text-decoration: line-through;
    margin-left: 4px;
}
.wishlist-actions {
    margin-top: auto;
    padding-top: 0.75rem;
    display: flex;
    gap: 0.5rem;
}
.btn-add-cart {
    flex-grow: 1;
    padding: 0.55rem 0.75rem;
    border: none;
    border-radius: 6px;
    background: var(--primary);
    color: #fff;
    font-weight: 600;
    font-size: 0.8rem;
    cursor: pointer;
    transition: background 0.15s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
.btn-add-cart:hover {
    background: var(--primary-dark);
}
.btn-remove-wish {
    width: 34px;
    height: 34px;
    border: 1px solid #FECACA;
    border-radius: 6px;
    background: #FEF2F2;
    color: var(--danger);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
    flex-shrink: 0;
    font-size: 0.85rem;
}
.btn-remove-wish:hover {
    background: var(--danger);
    color: #fff;
    border-color: var(--danger);
}
.empty-state {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 4rem 2rem;
    text-align: center;
}
.empty-icon-wrap {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--bg);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
}
</style>
@endpush

@section('content')
<div class="container my-5">

    {{-- Tiêu đề --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h1 style="font-size:1.5rem; font-weight:700; color:var(--text); margin:0;">Sách Yêu Thích</h1>
            @if($wishlists->count() > 0)
                <p class="mb-0 mt-1" style="color:var(--text-muted); font-size:0.875rem;">
                    {{ $wishlists->count() }} cuốn sách trong danh sách yêu thích
                </p>
            @endif
        </div>
        @if($wishlists->count() > 0)
        <a href="{{ route('books.index') }}"
           style="display:inline-flex; align-items:center; gap:6px; font-size:0.85rem; color:var(--text-muted); text-decoration:none;">
            <i class="bi bi-search"></i>Khám phá thêm sách
        </a>
        @endif
    </div>

    @if($wishlists->count() > 0)

    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
        @foreach($wishlists as $wishlist)
        <div class="col">
            <div class="wishlist-card">

                {{-- Ảnh bìa --}}
                <a href="{{ route('books.show', $wishlist->book->slug) }}" class="wishlist-cover">
                    @if($wishlist->book->cover_image)
                        <img src="{{ $wishlist->book->cover_image_url }}"
                             alt="{{ $wishlist->book->title }}">
                    @else
                        <div class="wishlist-cover-placeholder">
                            <i class="bi bi-book"></i>
                        </div>
                    @endif

                    @if($wishlist->book->sale_price && $wishlist->book->sale_price < $wishlist->book->price)
                        <span class="badge-sale">Giảm giá</span>
                    @endif

                    <div class="heart-badge">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                </a>

                {{-- Nội dung --}}
                <div class="wishlist-body">
                    <a href="{{ route('books.show', $wishlist->book->slug) }}" class="book-title-link">
                        {{ $wishlist->book->title }}
                    </a>
                    <div class="book-author-text">{{ $wishlist->book->author }}</div>

                    <div class="mt-1">
                        @if($wishlist->book->sale_price && $wishlist->book->sale_price < $wishlist->book->price)
                            <span class="price-main">{{ number_format($wishlist->book->sale_price, 0, ',', '.') }}đ</span>
                            <span class="price-old">{{ number_format($wishlist->book->price, 0, ',', '.') }}đ</span>
                        @else
                            <span class="price-main">{{ number_format($wishlist->book->price, 0, ',', '.') }}đ</span>
                        @endif
                    </div>

                    {{-- Nút thao tác --}}
                    <div class="wishlist-actions">
                        <form action="{{ route('cart.add') }}" method="POST" style="display:contents;">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $wishlist->book->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn-add-cart">
                                <i class="bi bi-cart-plus"></i>
                                <span>Thêm vào giỏ</span>
                            </button>
                        </form>

                        <form action="{{ route('wishlist.toggle', $wishlist->book) }}" method="POST" style="display:contents;">
                            @csrf
                            <button type="submit"
                                    class="btn-remove-wish"
                                    title="Xóa khỏi yêu thích"
                                    onclick="return confirm('Xóa sách này khỏi danh sách yêu thích?')">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        @endforeach
    </div>

    @else

    {{-- Trạng thái trống --}}
    <div class="empty-state">
        <div class="empty-icon-wrap">
            <i class="bi bi-heart" style="font-size:2rem; color:var(--text-muted);"></i>
        </div>
        <h5 style="font-weight:700; color:var(--text); margin-bottom:0.5rem;">Danh sách yêu thích trống</h5>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:1.5rem; max-width:340px; margin-left:auto; margin-right:auto;">
            Hãy thêm những cuốn sách bạn yêu thích để dễ dàng tìm lại và mua sau.
        </p>
        <a href="{{ route('books.index') }}" class="btn btn-primary" style="font-weight:600; padding:0.65rem 1.75rem; border-radius:6px;">
            <i class="bi bi-book me-2"></i>Khám phá sách ngay
        </a>
    </div>

    @endif

</div>
@endsection
