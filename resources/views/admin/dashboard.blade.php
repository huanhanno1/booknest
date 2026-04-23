@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1 class="page-header-title">Tổng quan hệ thống</h1>
        <p class="page-header-sub">Chào mừng trở lại, {{ auth()->user()->name }}. Hôm nay là {{ now()->isoFormat('dddd, D/M/YYYY') }}.</p>
    </div>
</div>

{{-- 4 Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#EFF6FF;">
                <i class="bi bi-book-fill" style="color:#2563EB;"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($totalBooks) }}</div>
                <div class="stat-label">Tổng số sách</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#F0FDF4;">
                <i class="bi bi-bag-check-fill" style="color:#16A34A;"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($totalOrders) }}</div>
                <div class="stat-label">Tổng đơn hàng</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#F5F3FF;">
                <i class="bi bi-people-fill" style="color:#7C3AED;"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($totalUsers) }}</div>
                <div class="stat-label">Người dùng</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#FFF7ED;">
                <i class="bi bi-cash-stack" style="color:#EA580C;"></i>
            </div>
            <div>
                <div class="stat-value" style="font-size:1.2rem;">{{ number_format($totalRevenue, 0, ',', '.') }}₫</div>
                <div class="stat-label">Doanh thu</div>
            </div>
        </div>
    </div>
</div>

{{-- Two Column Section --}}
<div class="row g-3">
    {{-- Đơn hàng gần đây --}}
    <div class="col-xl-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-header-title">
                    <i class="bi bi-clock-history me-2" style="color:#2563EB;"></i>Đơn hàng gần đây
                </h6>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                    Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="padding-left:20px;">Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th style="padding-right:20px;">Ngày đặt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
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
                            @endphp
                            <tr>
                                <td style="padding-left:20px;">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="fw-semibold text-decoration-none" style="color:var(--primary);">
                                        #{{ $order->order_code }}
                                    </a>
                                </td>
                                <td>{{ $order->user->name ?? ($order->receiver_name ?? 'Khách') }}</td>
                                <td class="fw-semibold">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                                <td>
                                    <span class="badge-status {{ $sc }}">{{ $sl }}</span>
                                </td>
                                <td style="padding-right:20px;color:var(--text-muted);">
                                    {{ $order->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-bag-x"></i></div>
                                        <div class="empty-state-title">Chưa có đơn hàng nào</div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Sách bán chạy --}}
    <div class="col-xl-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-header-title">
                    <i class="bi bi-fire me-2" style="color:#EA580C;"></i>Sách bán chạy
                </h6>
                <a href="{{ route('admin.books.index') }}" class="btn btn-sm btn-outline-primary">
                    Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($topSellingBooks as $index => $book)
                <div class="d-flex align-items-center gap-3 px-4 py-3 {{ $index < count($topSellingBooks) - 1 ? 'border-bottom' : '' }}" style="border-color:var(--border)!important;">
                    <div style="width:24px;text-align:center;flex-shrink:0;">
                        @if($index < 3)
                            <span style="font-size:0.82rem;font-weight:700;color:{{ ['#F59E0B','#94A3B8','#CD7C3A'][$index] }};">#{{ $index + 1 }}</span>
                        @else
                            <span style="font-size:0.82rem;color:var(--text-muted);">#{{ $index + 1 }}</span>
                        @endif
                    </div>
                    @if($book->cover_image)
                        <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}"
                             width="36" height="48" class="img-cover" style="flex-shrink:0;">
                    @else
                        <div class="img-placeholder" style="width:36px;height:48px;flex-shrink:0;">
                            <i class="bi bi-book" style="font-size:0.8rem;"></i>
                        </div>
                    @endif
                    <div style="flex:1;min-width:0;">
                        <div class="fw-semibold text-truncate" style="font-size:0.875rem;" title="{{ $book->title }}">
                            {{ $book->title }}
                        </div>
                        <div style="font-size:0.78rem;color:var(--text-muted);">{{ $book->author }}</div>
                    </div>
                    <div style="flex-shrink:0;">
                        <span style="font-size:0.78rem;font-weight:600;color:#2563EB;background:#EFF6FF;padding:3px 8px;border-radius:12px;">
                            {{ number_format($book->sold_count ?? 0) }} bán
                        </span>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="bi bi-bar-chart-line"></i></div>
                    <div class="empty-state-title">Chưa có dữ liệu</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
