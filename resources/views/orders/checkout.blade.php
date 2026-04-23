@extends('layouts.app')

@section('title', 'Thanh Toán - BookHaven')

@push('styles')
<style>
.checkout-section {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.checkout-section-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border);
    background: var(--bg);
    display: flex;
    align-items: center;
    gap: 0.625rem;
}
.checkout-section-header .section-icon {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    background: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.85rem;
    flex-shrink: 0;
}
.checkout-section-header h5 {
    margin: 0;
    font-weight: 700;
    font-size: 0.95rem;
    color: var(--text);
}
.checkout-body {
    padding: 1.5rem;
}
.form-label-clean {
    display: block;
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--text);
    margin-bottom: 0.4rem;
}
.form-control-clean {
    width: 100%;
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 0.6rem 0.875rem;
    font-size: 0.9rem;
    color: var(--text);
    background: var(--card);
    outline: none;
    transition: border-color 0.15s;
    box-sizing: border-box;
}
.form-control-clean:focus {
    border-color: var(--primary);
}
.form-control-clean.is-invalid {
    border-color: var(--danger);
}
.input-icon-wrap {
    position: relative;
}
.input-icon-wrap .input-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 0.9rem;
    pointer-events: none;
}
.input-icon-wrap .form-control-clean {
    padding-left: 2.25rem;
}
.input-icon-wrap.textarea-wrap .input-icon {
    top: 0.7rem;
    transform: none;
}
.payment-card {
    border: 2px solid var(--border);
    border-radius: 8px;
    padding: 1rem 1.25rem;
    cursor: pointer;
    transition: border-color 0.15s, background 0.15s;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.75rem;
    user-select: none;
}
.payment-card:last-child {
    margin-bottom: 0;
}
.payment-card:hover {
    border-color: #93C5FD;
}
.payment-card.selected {
    border-color: var(--primary);
    background: #EFF6FF;
}
.payment-card input[type="radio"] {
    width: 16px;
    height: 16px;
    accent-color: var(--primary);
    flex-shrink: 0;
    cursor: pointer;
}
.payment-icon-box {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    background: var(--bg);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1rem;
}
.payment-card.selected .payment-icon-box {
    background: #DBEAFE;
    border-color: #BFDBFE;
}
.summary-sticky {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    position: sticky;
    top: 80px;
}
.summary-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border);
}
.summary-item:last-child {
    border-bottom: none;
}
.invalid-msg {
    color: var(--danger);
    font-size: 0.8rem;
    margin-top: 0.3rem;
}
.step-badge {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: var(--primary);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
</style>
@endpush

@section('content')
<div class="container my-5">

    {{-- Breadcrumb / Tiêu đề --}}
    <div class="mb-4">
        <h1 style="font-size:1.5rem; font-weight:700; color:var(--text); margin:0;">Thanh Toán Đơn Hàng</h1>
        <nav aria-label="breadcrumb" class="mt-1">
            <ol class="breadcrumb mb-0" style="font-size:0.82rem;">
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" style="color:var(--primary);">Giỏ hàng</a></li>
                <li class="breadcrumb-item active" style="color:var(--text-muted);">Thanh toán</li>
            </ol>
        </nav>
    </div>

    @php
        $total = $cart->items->sum(fn($item) => ($item->book->sale_price ?? $item->book->price) * $item->quantity);
    @endphp

    <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row g-4">

            {{-- Cột trái: Form thông tin --}}
            <div class="col-lg-7">

                {{-- Thông tin người nhận --}}
                <div class="checkout-section">
                    <div class="checkout-section-header">
                        <div class="section-icon"><i class="bi bi-person-lines-fill"></i></div>
                        <h5>Thông Tin Người Nhận</h5>
                    </div>
                    <div class="checkout-body">

                        <div class="mb-3">
                            <label class="form-label-clean">
                                Họ và tên <span style="color:var(--danger);">*</span>
                            </label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-person input-icon"></i>
                                <input type="text"
                                       class="form-control-clean @error('receiver_name') is-invalid @enderror"
                                       name="receiver_name"
                                       value="{{ old('receiver_name', auth()->user()->name) }}"
                                       placeholder="Nhập họ và tên người nhận"
                                       required>
                            </div>
                            @error('receiver_name')
                                <div class="invalid-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label-clean">
                                Số điện thoại <span style="color:var(--danger);">*</span>
                            </label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-telephone input-icon"></i>
                                <input type="tel"
                                       class="form-control-clean @error('receiver_phone') is-invalid @enderror"
                                       name="receiver_phone"
                                       value="{{ old('receiver_phone', auth()->user()->phone ?? '') }}"
                                       placeholder="Ví dụ: 0901234567"
                                       required>
                            </div>
                            @error('receiver_phone')
                                <div class="invalid-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label-clean">
                                Địa chỉ giao hàng <span style="color:var(--danger);">*</span>
                            </label>
                            <div class="input-icon-wrap textarea-wrap">
                                <i class="bi bi-geo-alt input-icon"></i>
                                <textarea class="form-control-clean @error('receiver_address') is-invalid @enderror"
                                          name="receiver_address"
                                          rows="3"
                                          style="padding-left:2.25rem; resize:none;"
                                          placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố"
                                          required>{{ old('receiver_address', auth()->user()->address ?? '') }}</textarea>
                            </div>
                            @error('receiver_address')
                                <div class="invalid-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label-clean">Ghi chú đơn hàng</label>
                            <div class="input-icon-wrap textarea-wrap">
                                <i class="bi bi-chat-text input-icon"></i>
                                <textarea class="form-control-clean"
                                          name="note"
                                          rows="2"
                                          style="padding-left:2.25rem; resize:none;"
                                          placeholder="Ghi chú cho người giao hàng (nếu có)...">{{ old('note') }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Phương thức thanh toán --}}
                <div class="checkout-section">
                    <div class="checkout-section-header">
                        <div class="section-icon"><i class="bi bi-credit-card-2-front"></i></div>
                        <h5>Phương Thức Thanh Toán</h5>
                    </div>
                    <div class="checkout-body">

                        <label class="payment-card {{ old('payment_method', 'cod') === 'cod' ? 'selected' : '' }}" id="card-cod">
                            <input type="radio" name="payment_method" value="cod"
                                   {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}
                                   onchange="selectPayment('cod')">
                            <div class="payment-icon-box">
                                <i class="bi bi-cash-coin" style="color:var(--accent);"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div style="font-weight:600; color:var(--text); font-size:0.9rem;">Thanh toán khi nhận hàng (COD)</div>
                                <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">Thanh toán tiền mặt khi nhận được hàng</div>
                            </div>
                            <i class="bi bi-check-circle-fill" style="color:var(--primary); font-size:1.1rem; display:{{ old('payment_method', 'cod') === 'cod' ? 'block' : 'none' }};" id="check-cod"></i>
                        </label>

                        <label class="payment-card {{ old('payment_method') === 'vnpay' ? 'selected' : '' }}" id="card-vnpay">
                            <input type="radio" name="payment_method" value="vnpay"
                                   {{ old('payment_method') === 'vnpay' ? 'checked' : '' }}
                                   onchange="selectPayment('vnpay')">
                            <div class="payment-icon-box">
                                <i class="bi bi-bank" style="color:#0066B2;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div style="font-weight:600; color:var(--text); font-size:0.9rem;">Thanh toán qua VNPay</div>
                                <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">Thanh toán trực tuyến bảo mật qua cổng VNPay</div>
                            </div>
                            <i class="bi bi-check-circle-fill" style="color:var(--primary); font-size:1.1rem; display:{{ old('payment_method') === 'vnpay' ? 'block' : 'none' }};" id="check-vnpay"></i>
                        </label>

                    </div>
                </div>

            </div>

            {{-- Cột phải: Tóm tắt đơn hàng --}}
            <div class="col-lg-5">
                <div class="summary-sticky">
                    <div class="checkout-section-header">
                        <div class="section-icon"><i class="bi bi-bag-check"></i></div>
                        <h5>Đơn Hàng Của Bạn ({{ $cart->items->sum('quantity') }} sản phẩm)</h5>
                    </div>

                    <div style="padding:1rem 1.5rem; max-height:320px; overflow-y:auto;">
                        @foreach($cart->items as $item)
                        @php $itemTotal = ($item->book->sale_price ?? $item->book->price) * $item->quantity; @endphp
                        <div class="summary-item">
                            @if($item->book->cover_image)
                                <img src="{{ $item->book->cover_image_url }}"
                                     alt="{{ $item->book->title }}"
                                     style="width:44px; height:58px; object-fit:cover; border-radius:4px; border:1px solid var(--border); flex-shrink:0;">
                            @else
                                <div style="width:44px; height:58px; background:var(--bg); border-radius:4px; border:1px solid var(--border); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="bi bi-book" style="color:var(--text-muted); font-size:1rem;"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1 min-width-0">
                                <div style="font-weight:600; color:var(--text); font-size:0.85rem; line-height:1.4; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                    {{ $item->book->title }}
                                </div>
                                <div style="font-size:0.78rem; color:var(--text-muted); margin-top:3px;">
                                    x{{ $item->quantity }}
                                </div>
                            </div>
                            <div style="font-weight:600; color:var(--text); font-size:0.85rem; flex-shrink:0;">
                                {{ number_format($itemTotal, 0, ',', '.') }}đ
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div style="padding:1rem 1.5rem; border-top:1px solid var(--border);">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                            <span style="color:var(--text-muted); font-size:0.875rem;">Tạm tính</span>
                            <span style="font-size:0.875rem; font-weight:600;">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                            <span style="color:var(--text-muted); font-size:0.875rem;">Phí vận chuyển</span>
                            <span style="color:var(--success); font-weight:600; font-size:0.875rem;">Miễn phí</span>
                        </div>

                        <div style="border-top:1px solid var(--border); padding-top:0.875rem; margin-bottom:1.25rem;">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <span style="font-weight:700; font-size:1rem; color:var(--text);">Tổng cộng</span>
                                <span style="font-weight:700; font-size:1.35rem; color:var(--danger);">
                                    {{ number_format($total, 0, ',', '.') }}đ
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100"
                                style="font-weight:600; padding:0.85rem; font-size:1rem; border-radius:6px;">
                            <i class="bi bi-check-circle me-2"></i>Đặt Hàng Ngay
                        </button>

                        <a href="{{ route('cart.index') }}"
                           class="d-flex align-items-center justify-content-center gap-2 mt-3"
                           style="color:var(--text-muted); font-size:0.85rem; text-decoration:none;">
                            <i class="bi bi-arrow-left"></i>
                            Quay lại giỏ hàng
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
function selectPayment(method) {
    ['cod', 'vnpay'].forEach(function(m) {
        var card = document.getElementById('card-' + m);
        var check = document.getElementById('check-' + m);
        if (m === method) {
            card.classList.add('selected');
            check.style.display = 'block';
        } else {
            card.classList.remove('selected');
            check.style.display = 'none';
        }
    });
}
</script>
@endpush
