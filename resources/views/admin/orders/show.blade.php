@extends('layouts.admin')

@section('title', 'Đơn hàng #' . $order->order_code)

@section('content')
@php
    $statusClasses = [
        'pending'   => 'badge-status-pending',
        'confirmed' => 'badge-status-confirmed',
        'shipping'  => 'badge-status-shipping',
        'delivered' => 'badge-status-delivered',
        'cancelled' => 'badge-status-cancelled',
    ];
    $statusLabels = [
        'pending'   => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'shipping'  => 'Đang giao hàng',
        'delivered' => 'Đã giao hàng',
        'cancelled' => 'Đã hủy',
    ];
    $sc = $statusClasses[$order->status] ?? 'badge-status-pending';
    $sl = $statusLabels[$order->status] ?? $order->status;

    $paymentMethodMap = [
        'cod'   => 'Tiền mặt khi nhận hàng (COD)',
        'vnpay' => 'Thanh toán qua VNPay',
        'bank'  => 'Chuyển khoản ngân hàng',
    ];
    $paymentStatusClasses = [
        'pending'  => 'badge-unpaid',
        'paid'     => 'badge-paid',
        'failed'   => 'badge-inactive',
        'refunded' => 'badge-user',
    ];
    $paymentStatusLabels = [
        'pending'  => 'Chưa thanh toán',
        'paid'     => 'Đã thanh toán',
        'failed'   => 'Thanh toán thất bại',
        'refunded' => 'Đã hoàn tiền',
    ];
    $pc = $paymentStatusClasses[$order->payment_status ?? 'pending'] ?? 'badge-unpaid';
    $pl = $paymentStatusLabels[$order->payment_status ?? 'pending'] ?? 'Chưa thanh toán';
@endphp

<div class="page-header">
    <div>
        <div class="d-flex align-items-center gap-3">
            <h1 class="page-header-title" style="margin:0;">Đơn hàng #{{ $order->order_code }}</h1>
            <span class="badge-status {{ $sc }}">{{ $sl }}</span>
        </div>
        <p class="page-header-sub" style="margin-top:4px;">
            <a href="{{ route('admin.orders.index') }}" class="text-decoration-none" style="color:var(--text-muted);">Quản lý đơn hàng</a>
            <i class="bi bi-chevron-right mx-1" style="font-size:0.65rem;"></i>
            #{{ $order->order_code }}
        </p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="row g-3">
    {{-- Cột trái --}}
    <div class="col-lg-8">
        {{-- Thông tin đơn hàng + Cập nhật trạng thái --}}
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-header-title">
                    <i class="bi bi-info-circle me-2" style="color:var(--primary);"></i>Thông tin đơn hàng
                </h6>
                <small style="color:var(--text-muted);">{{ $order->created_at->format('H:i, d/m/Y') }}</small>
            </div>
            <div class="card-body">
                {{-- Info grid --}}
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Mã đơn hàng</div>
                        <div class="fw-semibold">#{{ $order->order_code }}</div>
                    </div>
                    <div class="col-sm-4">
                        <div style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Phương thức thanh toán</div>
                        <div class="fw-semibold" style="font-size:0.875rem;">
                            {{ $paymentMethodMap[$order->payment_method ?? ''] ?? ($order->payment_method ?? '—') }}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Trạng thái thanh toán</div>
                        <span class="badge-status {{ $pc }}">{{ $pl }}</span>
                    </div>
                </div>

                {{-- Cập nhật trạng thái --}}
                <div style="border-top:1px solid var(--border);padding-top:20px;">
                    <div style="font-size:0.82rem;font-weight:600;color:var(--text-muted);margin-bottom:12px;text-transform:uppercase;letter-spacing:0.05em;">
                        Cập nhật trạng thái đơn hàng
                    </div>
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-flex gap-2 align-items-center flex-wrap">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select" style="max-width:240px;">
                            <option value="pending"   {{ $order->status === 'pending'   ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="shipping"  {{ $order->status === 'shipping'  ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Cập nhật
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Danh sách sản phẩm --}}
        <div class="card">
            <div class="card-header">
                <h6 class="card-header-title">
                    <i class="bi bi-bag me-2" style="color:#7C3AED;"></i>Sản phẩm đặt mua
                    <span style="font-size:0.75rem;font-weight:400;color:var(--text-muted);margin-left:6px;">{{ $order->items->count() }} sản phẩm</span>
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th style="padding-left:20px;">Sách</th>
                                <th style="width:130px;">Đơn giá</th>
                                <th style="width:80px;text-align:center;">SL</th>
                                <th style="padding-right:20px;width:140px;text-align:right;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td style="padding-left:20px;">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($item->book && $item->book->cover_image)
                                            <img src="{{ $item->book->cover_image_url }}"
                                                 alt="{{ $item->book->title }}"
                                                 width="40" height="53"
                                                 class="img-cover"
                                                 style="flex-shrink:0;">
                                        @else
                                            <div class="img-placeholder" style="width:40px;height:53px;flex-shrink:0;">
                                                <i class="bi bi-book" style="font-size:0.9rem;"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold" style="font-size:0.875rem;">
                                                {{ $item->book->title ?? $item->book_title ?? '—' }}
                                            </div>
                                            @if($item->book && $item->book->author)
                                                <div style="font-size:0.78rem;color:var(--text-muted);">{{ $item->book->author }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:0.875rem;">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                                <td style="text-align:center;">
                                    <span style="font-size:0.875rem;font-weight:600;">{{ $item->quantity }}</span>
                                </td>
                                <td style="padding-right:20px;text-align:right;font-weight:600;font-size:0.875rem;">
                                    {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:#FAFBFC;">
                                <td colspan="3" style="padding:14px 20px;font-weight:600;color:var(--text-muted);">Tổng cộng</td>
                                <td style="padding:14px 20px;text-align:right;font-size:1.05rem;font-weight:700;color:var(--primary);">
                                    {{ number_format($order->total_amount, 0, ',', '.') }}₫
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Cột phải --}}
    <div class="col-lg-4">
        {{-- Thông tin khách hàng & giao hàng --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-header-title">
                    <i class="bi bi-person me-2" style="color:#7C3AED;"></i>Thông tin khách hàng
                </h6>
            </div>
            <div class="card-body">
                @if($order->user)
                <div class="d-flex align-items-center gap-3 pb-3 mb-3" style="border-bottom:1px solid var(--border);">
                    <div style="width:40px;height:40px;background:#EFF6FF;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-person-fill" style="color:var(--primary);font-size:1rem;"></i>
                    </div>
                    <div>
                        <div class="fw-semibold" style="font-size:0.875rem;">{{ $order->user->name }}</div>
                        <div style="font-size:0.78rem;color:var(--text-muted);">{{ $order->user->email }}</div>
                    </div>
                </div>
                @endif

                <div class="mb-3">
                    <div style="font-size:0.72rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:5px;">Người nhận</div>
                    <div class="fw-semibold" style="font-size:0.875rem;">{{ $order->receiver_name ?? ($order->user->name ?? '—') }}</div>
                </div>

                <div class="mb-3">
                    <div style="font-size:0.72rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:5px;">Số điện thoại</div>
                    <div style="font-size:0.875rem;">
                        @if($order->receiver_phone)
                            <a href="tel:{{ $order->receiver_phone }}" class="text-decoration-none" style="color:var(--primary);">
                                {{ $order->receiver_phone }}
                            </a>
                        @else
                            <span style="color:var(--text-muted);">—</span>
                        @endif
                    </div>
                </div>

                <div>
                    <div style="font-size:0.72rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:5px;">Địa chỉ giao hàng</div>
                    <div style="font-size:0.875rem;line-height:1.5;">{{ $order->receiver_address ?? '—' }}</div>
                </div>
            </div>
        </div>

        @if($order->note)
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-header-title">
                    <i class="bi bi-chat-left-text me-2" style="color:#EA580C;"></i>Ghi chú đơn hàng
                </h6>
            </div>
            <div class="card-body">
                <p style="font-size:0.875rem;color:var(--text-muted);margin:0;line-height:1.6;">{{ $order->note }}</p>
            </div>
        </div>
        @endif

        {{-- Tóm tắt thanh toán --}}
        <div class="card">
            <div class="card-header">
                <h6 class="card-header-title">
                    <i class="bi bi-receipt me-2" style="color:#16A34A;"></i>Tóm tắt thanh toán
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2" style="font-size:0.875rem;">
                    <span style="color:var(--text-muted);">Tạm tính</span>
                    <span>{{ number_format($order->subtotal ?? $order->total_amount, 0, ',', '.') }}₫</span>
                </div>
                @if(isset($order->shipping_fee) && $order->shipping_fee > 0)
                <div class="d-flex justify-content-between mb-2" style="font-size:0.875rem;">
                    <span style="color:var(--text-muted);">Phí vận chuyển</span>
                    <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</span>
                </div>
                @endif
                @if(isset($order->discount_amount) && $order->discount_amount > 0)
                <div class="d-flex justify-content-between mb-2" style="font-size:0.875rem;">
                    <span style="color:var(--text-muted);">Giảm giá</span>
                    <span style="color:#16A34A;font-weight:500;">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                </div>
                @endif
                <div class="d-flex justify-content-between pt-3" style="border-top:2px solid var(--border);font-weight:700;">
                    <span>Tổng cộng</span>
                    <span style="color:var(--primary);font-size:1.05rem;">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
