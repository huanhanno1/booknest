@extends('layouts.app')

@section('title', 'Giỏ Hàng - BookHaven')

@push('styles')
<style>
.cart-table th {
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-muted);
    padding: 0.85rem 1rem;
    background: var(--bg);
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.cart-table td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border);
    color: var(--text);
}
.cart-table tbody tr:last-child td {
    border-bottom: none;
}
.qty-control {
    display: inline-flex;
    align-items: center;
    border: 1px solid var(--border);
    border-radius: 6px;
    overflow: hidden;
}
.qty-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: var(--bg);
    color: var(--text-muted);
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s, color 0.15s;
    flex-shrink: 0;
}
.qty-btn:hover {
    background: var(--border);
    color: var(--text);
}
.qty-value {
    width: 36px;
    text-align: center;
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text);
    border-left: 1px solid var(--border);
    border-right: 1px solid var(--border);
    line-height: 32px;
}
.summary-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 1.5rem;
    position: sticky;
    top: 80px;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
}
.summary-divider {
    border: none;
    border-top: 1px solid var(--border);
    margin: 0.75rem 0;
}
.btn-remove {
    width: 32px;
    height: 32px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: none;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: border-color 0.15s, color 0.15s, background 0.15s;
    cursor: pointer;
}
.btn-remove:hover {
    border-color: var(--danger);
    color: var(--danger);
    background: #FEF2F2;
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

    {{-- Tiêu đề trang --}}
    <div class="mb-4">
        <h1 style="font-size:1.5rem; font-weight:700; color:var(--text); margin:0;">Giỏ Hàng Của Bạn</h1>
        @if($cart && $cart->items->count() > 0)
            <p class="mb-0 mt-1" style="color:var(--text-muted); font-size:0.9rem;">
                {{ $cart->items->sum('quantity') }} sản phẩm trong giỏ hàng
            </p>
        @endif
    </div>

    @if($cart && $cart->items->count() > 0)

    @php
        $total = $cart->items->sum(fn($item) => ($item->book->sale_price ?? $item->book->price) * $item->quantity);
    @endphp

    <div class="row g-4">

        {{-- Bảng sản phẩm --}}
        <div class="col-lg-8">
            <div style="background:var(--card); border:1px solid var(--border); border-radius:8px; overflow:hidden;">

                {{-- Desktop table --}}
                <div class="d-none d-md-block">
                    <table class="table mb-0 cart-table">
                        <thead>
                            <tr>
                                <th style="width:45%;">Sản phẩm</th>
                                <th class="text-center">Đơn giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Thành tiền</th>
                                <th style="width:48px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart->items as $item)
                            @php $price = $item->book->sale_price ?? $item->book->price; @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <a href="{{ route('books.show', $item->book->slug) }}" class="flex-shrink-0">
                                            @if($item->book->cover_image)
                                                <img src="{{ $item->book->cover_image_url }}"
                                                     alt="{{ $item->book->title }}"
                                                     style="width:52px; height:70px; object-fit:cover; border-radius:4px; border:1px solid var(--border);">
                                            @else
                                                <div style="width:52px; height:70px; background:var(--bg); border-radius:4px; border:1px solid var(--border); display:flex; align-items:center; justify-content:center;">
                                                    <i class="bi bi-book" style="color:var(--text-muted); font-size:1.2rem;"></i>
                                                </div>
                                            @endif
                                        </a>
                                        <div style="min-width:0;">
                                            <a href="{{ route('books.show', $item->book->slug) }}"
                                               class="text-decoration-none"
                                               style="color:var(--text); font-weight:600; font-size:0.9rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; line-height:1.4;">
                                                {{ $item->book->title }}
                                            </a>
                                            <div class="mt-1" style="font-size:0.8rem; color:var(--text-muted);">{{ $item->book->author }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span style="font-weight:600; color:var(--danger); font-size:0.9rem;">
                                        {{ number_format($price, 0, ',', '.') }}đ
                                    </span>
                                    @if($item->book->sale_price && $item->book->sale_price < $item->book->price)
                                        <div style="font-size:0.78rem; color:var(--text-muted); text-decoration:line-through;">
                                            {{ number_format($item->book->price, 0, ',', '.') }}đ
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <div class="qty-control">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display:contents;">
                                                @csrf @method('PATCH')
                                                <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}" class="qty-btn">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                            </form>
                                            <span class="qty-value">{{ $item->quantity }}</span>
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display:contents;">
                                                @csrf @method('PATCH')
                                                <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="qty-btn">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span style="font-weight:700; color:var(--danger); font-size:0.95rem;">
                                        {{ number_format($price * $item->quantity, 0, ',', '.') }}đ
                                    </span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-remove mx-auto"
                                                onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')"
                                                title="Xóa sản phẩm">
                                            <i class="bi bi-trash3" style="font-size:0.8rem;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile cards --}}
                <div class="d-md-none">
                    @foreach($cart->items as $item)
                    @php $price = $item->book->sale_price ?? $item->book->price; @endphp
                    <div style="padding:1rem; border-bottom:1px solid var(--border);">
                        <div class="d-flex gap-3">
                            <a href="{{ route('books.show', $item->book->slug) }}" class="flex-shrink-0">
                                @if($item->book->cover_image)
                                    <img src="{{ $item->book->cover_image_url }}"
                                         alt="{{ $item->book->title }}"
                                         style="width:60px; height:80px; object-fit:cover; border-radius:4px; border:1px solid var(--border);">
                                @else
                                    <div style="width:60px; height:80px; background:var(--bg); border-radius:4px; border:1px solid var(--border); display:flex; align-items:center; justify-content:center;">
                                        <i class="bi bi-book" style="color:var(--text-muted);"></i>
                                    </div>
                                @endif
                            </a>
                            <div class="flex-grow-1 min-width-0">
                                <a href="{{ route('books.show', $item->book->slug) }}"
                                   class="text-decoration-none"
                                   style="color:var(--text); font-weight:600; font-size:0.9rem; line-height:1.4; display:block;">
                                    {{ $item->book->title }}
                                </a>
                                <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">{{ $item->book->author }}</div>
                                <div style="color:var(--danger); font-weight:700; margin-top:6px;">
                                    {{ number_format($price, 0, ',', '.') }}đ
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <div class="qty-control">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display:contents;">
                                            @csrf @method('PATCH')
                                            <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}" class="qty-btn">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                        </form>
                                        <span class="qty-value">{{ $item->quantity }}</span>
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display:contents;">
                                            @csrf @method('PATCH')
                                            <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="qty-btn">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <span style="font-weight:700; color:var(--danger); font-size:0.9rem;">
                                            {{ number_format($price * $item->quantity, 0, ',', '.') }}đ
                                        </span>
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-remove"
                                                    onclick="return confirm('Xóa sản phẩm này?')"
                                                    title="Xóa">
                                                <i class="bi bi-trash3" style="font-size:0.8rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>

            <div class="mt-3">
                <a href="{{ route('books.index') }}"
                   style="color:var(--text-muted); font-size:0.875rem; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                    <i class="bi bi-arrow-left"></i>
                    Tiếp tục mua sắm
                </a>
            </div>
        </div>

        {{-- Tóm tắt giỏ hàng --}}
        <div class="col-lg-4">
            <div class="summary-card">
                <h5 style="font-weight:700; color:var(--text); font-size:1rem; margin-bottom:1.25rem; padding-bottom:0.875rem; border-bottom:1px solid var(--border);">
                    Tóm Tắt Đơn Hàng
                </h5>

                <div class="summary-row">
                    <span style="color:var(--text-muted); font-size:0.9rem;">Tạm tính ({{ $cart->items->sum('quantity') }} sản phẩm)</span>
                    <span style="font-weight:600; font-size:0.9rem;">{{ number_format($total, 0, ',', '.') }}đ</span>
                </div>
                <div class="summary-row">
                    <span style="color:var(--text-muted); font-size:0.9rem;">Phí vận chuyển</span>
                    <span style="color:var(--success); font-weight:600; font-size:0.9rem;">Miễn phí</span>
                </div>

                <hr class="summary-divider">

                <div class="summary-row" style="margin-bottom:1.25rem;">
                    <span style="font-weight:700; font-size:1rem; color:var(--text);">Tổng cộng</span>
                    <span style="font-weight:700; font-size:1.25rem; color:var(--danger);">
                        {{ number_format($total, 0, ',', '.') }}đ
                    </span>
                </div>

                <a href="{{ route('orders.checkout') }}"
                   class="btn btn-primary w-100"
                   style="font-weight:600; padding:0.75rem; font-size:0.95rem; border-radius:6px;">
                    <i class="bi bi-credit-card me-2"></i>Tiến Hành Thanh Toán
                </a>

                <div class="mt-3 pt-3" style="border-top:1px solid var(--border);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-shield-check" style="color:var(--success); font-size:0.9rem;"></i>
                        <span style="font-size:0.8rem; color:var(--text-muted);">Thanh toán bảo mật</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-truck" style="color:var(--primary); font-size:0.9rem;"></i>
                        <span style="font-size:0.8rem; color:var(--text-muted);">Miễn phí vận chuyển toàn quốc</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-arrow-repeat" style="color:var(--accent); font-size:0.9rem;"></i>
                        <span style="font-size:0.8rem; color:var(--text-muted);">Đổi trả trong 7 ngày</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @else

    {{-- Giỏ hàng trống --}}
    <div class="empty-state">
        <div class="empty-icon-wrap">
            <i class="bi bi-cart3" style="font-size:2rem; color:var(--text-muted);"></i>
        </div>
        <h5 style="font-weight:700; color:var(--text); margin-bottom:0.5rem;">Giỏ hàng đang trống</h5>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:1.5rem; max-width:320px; margin-left:auto; margin-right:auto;">
            Hãy thêm những cuốn sách bạn yêu thích vào giỏ hàng để tiếp tục mua sắm.
        </p>
        <a href="{{ route('books.index') }}" class="btn btn-primary" style="font-weight:600; padding:0.65rem 1.75rem; border-radius:6px;">
            <i class="bi bi-book me-2"></i>Khám phá sách ngay
        </a>
    </div>

    @endif

</div>
@endsection
