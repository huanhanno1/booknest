@extends('layouts.app')

@section('title', 'Đơn Hàng Của Tôi - BookHaven')

@push('styles')
<style>
.order-table th {
    font-size: 0.78rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-muted);
    padding: 0.875rem 1rem;
    background: var(--bg);
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.order-table td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border);
}
.order-table tbody tr:last-child td {
    border-bottom: none;
}
.order-table tbody tr:hover {
    background: #FAFBFC;
}
.order-code {
    font-family: 'Courier New', Courier, monospace;
    font-weight: 700;
    font-size: 0.88rem;
    color: var(--primary);
    text-decoration: none;
    letter-spacing: 0.02em;
}
.order-code:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}
.status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.02em;
}
.pay-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.72rem;
    font-weight: 600;
    margin-top: 4px;
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
.btn-detail {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--text);
    text-decoration: none;
    background: var(--card);
    transition: border-color 0.15s, color 0.15s;
    white-space: nowrap;
}
.btn-detail:hover {
    border-color: var(--primary);
    color: var(--primary);
}
</style>
@endpush

@section('content')
<div class="container my-5">

    {{-- Tiêu đề --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 style="font-size:1.5rem; font-weight:700; color:var(--text); margin:0;">Đơn Hàng Của Tôi</h1>
            @if($orders->count() > 0)
                <p class="mb-0 mt-1" style="color:var(--text-muted); font-size:0.875rem;">
                    Tổng cộng {{ $orders->total() }} đơn hàng
                </p>
            @endif
        </div>
        <a href="{{ route('books.index') }}" class="btn-detail">
            <i class="bi bi-book"></i>Mua thêm sách
        </a>
    </div>

    @if($orders->count() > 0)

    <div style="background:var(--card); border:1px solid var(--border); border-radius:8px; overflow:hidden;">

        {{-- Desktop table --}}
        <div class="d-none d-md-block table-responsive">
            <table class="table mb-0 order-table">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    @php
                        $statusMap = [
                            'pending'   => ['bg' => '#FEF3C7', 'color' => '#92400E', 'label' => 'Chờ xử lý'],
                            'confirmed' => ['bg' => '#DBEAFE', 'color' => '#1E40AF', 'label' => 'Đã xác nhận'],
                            'shipping'  => ['bg' => '#E0F2FE', 'color' => '#0369A1', 'label' => 'Đang giao'],
                            'delivered' => ['bg' => '#DCFCE7', 'color' => '#166534', 'label' => 'Đã giao'],
                            'cancelled' => ['bg' => '#FEE2E2', 'color' => '#991B1B', 'label' => 'Đã hủy'],
                        ];
                        $sc = $statusMap[$order->status] ?? ['bg' => '#F1F5F9', 'color' => '#475569', 'label' => $order->status];
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="order-code">
                                #{{ $order->order_code }}
                            </a>
                        </td>
                        <td>
                            <div style="font-size:0.875rem; color:var(--text); font-weight:500;">
                                {{ $order->created_at->format('d/m/Y') }}
                            </div>
                            <div style="font-size:0.78rem; color:var(--text-muted);">
                                {{ $order->created_at->format('H:i') }}
                            </div>
                        </td>
                        <td>
                            <span style="font-weight:700; color:var(--danger); font-size:0.9rem;">
                                {{ number_format($order->total_amount, 0, ',', '.') }}đ
                            </span>
                        </td>
                        <td>
                            <div style="font-size:0.82rem; color:var(--text); font-weight:500;">
                                {{ $order->payment_method === 'cod' ? 'COD' : 'VNPay' }}
                            </div>
                            @if($order->payment_status === 'paid')
                                <span class="pay-badge" style="background:#DCFCE7; color:#166534;">Đã thanh toán</span>
                            @else
                                <span class="pay-badge" style="background:#FEF3C7; color:#92400E;">Chưa thanh toán</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge" style="background:{{ $sc['bg'] }}; color:{{ $sc['color'] }};">
                                {{ $sc['label'] }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('orders.show', $order) }}" class="btn-detail">
                                <i class="bi bi-eye"></i>Chi tiết
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="d-md-none">
            @foreach($orders as $order)
            @php
                $statusMap = [
                    'pending'   => ['bg' => '#FEF3C7', 'color' => '#92400E', 'label' => 'Chờ xử lý'],
                    'confirmed' => ['bg' => '#DBEAFE', 'color' => '#1E40AF', 'label' => 'Đã xác nhận'],
                    'shipping'  => ['bg' => '#E0F2FE', 'color' => '#0369A1', 'label' => 'Đang giao'],
                    'delivered' => ['bg' => '#DCFCE7', 'color' => '#166534', 'label' => 'Đã giao'],
                    'cancelled' => ['bg' => '#FEE2E2', 'color' => '#991B1B', 'label' => 'Đã hủy'],
                ];
                $sc = $statusMap[$order->status] ?? ['bg' => '#F1F5F9', 'color' => '#475569', 'label' => $order->status];
            @endphp
            <div style="padding:1rem; border-bottom:1px solid var(--border);">
                <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                    <a href="{{ route('orders.show', $order) }}" class="order-code">
                        #{{ $order->order_code }}
                    </a>
                    <span class="status-badge" style="background:{{ $sc['bg'] }}; color:{{ $sc['color'] }};">
                        {{ $sc['label'] }}
                    </span>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div style="font-size:0.78rem; color:var(--text-muted);">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        <div style="font-weight:700; color:var(--danger); font-size:0.9rem; margin-top:2px;">
                            {{ number_format($order->total_amount, 0, ',', '.') }}đ
                        </div>
                    </div>
                    <a href="{{ route('orders.show', $order) }}" class="btn-detail">
                        <i class="bi bi-eye"></i>Chi tiết
                    </a>
                </div>
            </div>
            @endforeach
        </div>

    </div>

    {{-- Phân trang --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links() }}
    </div>

    @else

    {{-- Trạng thái trống --}}
    <div class="empty-state">
        <div class="empty-icon-wrap">
            <i class="bi bi-bag-x" style="font-size:2rem; color:var(--text-muted);"></i>
        </div>
        <h5 style="font-weight:700; color:var(--text); margin-bottom:0.5rem;">Bạn chưa có đơn hàng nào</h5>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:1.5rem; max-width:320px; margin-left:auto; margin-right:auto;">
            Hãy khám phá và mua sắm những cuốn sách yêu thích của bạn.
        </p>
        <a href="{{ route('books.index') }}" class="btn btn-primary" style="font-weight:600; padding:0.65rem 1.75rem; border-radius:6px;">
            <i class="bi bi-book me-2"></i>Mua sắm ngay
        </a>
    </div>

    @endif

</div>
@endsection
