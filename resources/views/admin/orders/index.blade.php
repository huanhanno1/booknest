@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Quản lý đơn hàng</h1>
        <p class="page-header-sub">Tất cả đơn hàng trong hệ thống</p>
    </div>
</div>

{{-- Bộ lọc trạng thái --}}
<div class="status-tabs">
    <a href="{{ route('admin.orders.index') }}"
       class="status-tab {{ !request('status') ? 'active' : '' }}">
        <i class="bi bi-grid-3x3-gap" style="font-size:0.8rem;"></i>
        Tất cả
    </a>
    @php
        $tabConfig = [
            'pending'   => ['label' => 'Chờ xác nhận', 'icon' => 'bi-hourglass-split'],
            'confirmed' => ['label' => 'Đã xác nhận',  'icon' => 'bi-check-circle'],
            'shipping'  => ['label' => 'Đang giao',    'icon' => 'bi-truck'],
            'delivered' => ['label' => 'Đã giao',      'icon' => 'bi-check-circle-fill'],
            'cancelled' => ['label' => 'Đã hủy',       'icon' => 'bi-x-circle'],
        ];
    @endphp
    @foreach($tabConfig as $value => $tab)
    <a href="{{ route('admin.orders.index', ['status' => $value]) }}"
       class="status-tab {{ request('status') === $value ? 'active' : '' }}">
        <i class="{{ $tab['icon'] }}" style="font-size:0.8rem;"></i>
        {{ $tab['label'] }}
    </a>
    @endforeach
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-header-title">
            @if(request('status'))
                {{ $tabConfig[request('status')]['label'] ?? 'Đơn hàng' }}
            @else
                Tất cả đơn hàng
            @endif
        </h6>
        <span style="font-size:0.8rem;color:var(--text-muted);">{{ $orders->total() }} đơn</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="padding-left:20px;">Mã đơn</th>
                        <th>Khách hàng</th>
                        <th style="width:140px;">Tổng tiền</th>
                        <th style="width:140px;">Trạng thái</th>
                        <th style="width:140px;">Thanh toán</th>
                        <th style="width:110px;">T.Toán</th>
                        <th style="width:130px;">Ngày đặt</th>
                        <th style="padding-right:20px;width:80px;text-align:center;">Xem</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
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
                            'shipping'  => 'Đang giao',
                            'delivered' => 'Đã giao',
                            'cancelled' => 'Đã hủy',
                        ];
                        $sc = $statusClasses[$order->status] ?? 'badge-status-pending';
                        $sl = $statusLabels[$order->status] ?? $order->status;

                        $paymentMethodMap = [
                            'cod'   => 'Tiền mặt (COD)',
                            'vnpay' => 'VNPay',
                            'bank'  => 'Chuyển khoản',
                        ];
                        $paymentStatusClasses = [
                            'pending'  => 'badge-unpaid',
                            'paid'     => 'badge-paid',
                            'failed'   => 'badge-inactive',
                            'refunded' => 'badge-user',
                        ];
                        $paymentStatusLabels = [
                            'pending'  => 'Chưa TT',
                            'paid'     => 'Đã TT',
                            'failed'   => 'Thất bại',
                            'refunded' => 'Hoàn tiền',
                        ];
                        $pc = $paymentStatusClasses[$order->payment_status ?? 'pending'] ?? 'badge-unpaid';
                        $pl = $paymentStatusLabels[$order->payment_status ?? 'pending'] ?? 'Chưa TT';
                    @endphp
                    <tr>
                        <td style="padding-left:20px;">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="fw-semibold text-decoration-none" style="color:var(--primary);">
                                #{{ $order->order_code }}
                            </a>
                        </td>
                        <td>
                            <div style="font-size:0.875rem;font-weight:500;">
                                {{ $order->user->name ?? ($order->receiver_name ?? 'Khách') }}
                            </div>
                            @if($order->user && $order->user->email)
                                <div style="font-size:0.75rem;color:var(--text-muted);">{{ $order->user->email }}</div>
                            @endif
                        </td>
                        <td class="fw-semibold" style="font-size:0.875rem;">
                            {{ number_format($order->total_amount, 0, ',', '.') }}₫
                        </td>
                        <td>
                            <span class="badge-status {{ $sc }}">{{ $sl }}</span>
                        </td>
                        <td style="font-size:0.82rem;color:var(--text-muted);">
                            {{ $paymentMethodMap[$order->payment_method ?? ''] ?? ($order->payment_method ?? '—') }}
                        </td>
                        <td>
                            <span class="badge-status {{ $pc }}">{{ $pl }}</span>
                        </td>
                        <td style="font-size:0.82rem;color:var(--text-muted);">
                            {{ $order->created_at->format('d/m/Y') }}<br>
                            <span style="font-size:0.75rem;">{{ $order->created_at->format('H:i') }}</span>
                        </td>
                        <td style="padding-right:20px;text-align:center;">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="btn btn-icon btn-outline-primary"
                               title="Xem chi tiết đơn hàng">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-bag-x"></i></div>
                                <div class="empty-state-title">Không có đơn hàng nào</div>
                                <div class="empty-state-sub">
                                    @if(request('status'))
                                        Chưa có đơn hàng ở trạng thái này.
                                    @else
                                        Hệ thống chưa có đơn hàng nào.
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small style="color:var(--text-muted);">
            Hiển thị {{ $orders->firstItem() }}–{{ $orders->lastItem() }} trong {{ $orders->total() }} đơn hàng
        </small>
        {{ $orders->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
