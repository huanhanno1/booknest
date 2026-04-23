@extends('layouts.app')

@section('title', 'Đơn Hàng #' . $order->order_code . ' - BookHaven')

@push('styles')
<style>
.info-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.info-card-header {
    padding: 0.875rem 1.25rem;
    border-bottom: 1px solid var(--border);
    background: var(--bg);
    display: flex;
    align-items: center;
    gap: 0.625rem;
}
.info-card-header .card-icon {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.8rem;
    flex-shrink: 0;
}
.info-card-header h5 {
    margin: 0;
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--text);
}
.info-card-body {
    padding: 1.25rem;
}
.order-items-table th {
    font-size: 0.78rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-muted);
    padding: 0.75rem 1rem;
    background: var(--bg);
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.order-items-table td {
    padding: 0.875rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border);
}
.order-items-table tbody tr:last-child td {
    border-bottom: none;
}
.status-badge-lg {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 0.82rem;
    font-weight: 600;
}
.info-row {
    display: flex;
    align-items: flex-start;
    padding: 0.6rem 0;
    border-bottom: 1px solid var(--border);
    gap: 1rem;
}
.info-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.info-row .info-label {
    font-size: 0.8rem;
    color: var(--text-muted);
    min-width: 110px;
    flex-shrink: 0;
    padding-top: 1px;
}
.info-row .info-value {
    font-size: 0.875rem;
    color: var(--text);
    font-weight: 600;
    flex-grow: 1;
}
.timeline {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 1.5rem;
    overflow-x: auto;
    padding-bottom: 4px;
}
.timeline-step {
    flex: 1;
    min-width: 80px;
    text-align: center;
    position: relative;
}
.timeline-step::before {
    content: '';
    position: absolute;
    top: 14px;
    left: 50%;
    right: -50%;
    height: 2px;
    background: var(--border);
    z-index: 0;
}
.timeline-step:last-child::before {
    display: none;
}
.timeline-step.done::before {
    background: var(--primary);
}
.timeline-dot {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 2px solid var(--border);
    background: var(--card);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    color: var(--text-muted);
    margin: 0 auto 6px;
    position: relative;
    z-index: 1;
    transition: all 0.15s;
}
.timeline-step.active .timeline-dot {
    border-color: var(--primary);
    background: var(--primary);
    color: #fff;
}
.timeline-step.done .timeline-dot {
    border-color: var(--primary);
    background: var(--primary);
    color: #fff;
}
.timeline-step.cancelled .timeline-dot {
    border-color: var(--danger);
    background: var(--danger);
    color: #fff;
}
.timeline-label {
    font-size: 0.72rem;
    color: var(--text-muted);
    white-space: nowrap;
}
.timeline-step.active .timeline-label,
.timeline-step.done .timeline-label {
    color: var(--primary);
    font-weight: 600;
}
.timeline-step.cancelled .timeline-label {
    color: var(--danger);
    font-weight: 600;
}
</style>
@endpush

@section('content')
<div class="container my-5">

    {{-- Tiêu đề + điều hướng --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('orders.index') }}"
                   style="color:var(--text-muted); font-size:0.85rem; text-decoration:none; display:inline-flex; align-items:center; gap:5px;">
                    <i class="bi bi-arrow-left"></i>Danh sách đơn hàng
                </a>
            </div>
            <h1 style="font-size:1.4rem; font-weight:700; color:var(--text); margin:0;">
                Chi Tiết Đơn Hàng
            </h1>
            <div style="margin-top:4px;">
                <span style="font-size:0.875rem; color:var(--text-muted);">Mã đơn: </span>
                <span style="font-family:'Courier New',monospace; font-weight:700; color:var(--primary); font-size:0.9rem;">
                    #{{ $order->order_code }}
                </span>
                <span style="font-size:0.8rem; color:var(--text-muted); margin-left:0.75rem;">
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>
    </div>

    @php
        $statusMap = [
            'pending'   => ['bg' => '#FEF3C7', 'color' => '#92400E', 'label' => 'Chờ xử lý',      'icon' => 'bi-clock',          'step' => 0],
            'confirmed' => ['bg' => '#DBEAFE', 'color' => '#1E40AF', 'label' => 'Đã xác nhận',    'icon' => 'bi-check-circle',   'step' => 1],
            'shipping'  => ['bg' => '#E0F2FE', 'color' => '#0369A1', 'label' => 'Đang giao hàng', 'icon' => 'bi-truck',          'step' => 2],
            'delivered' => ['bg' => '#DCFCE7', 'color' => '#166534', 'label' => 'Đã giao hàng',   'icon' => 'bi-bag-check',      'step' => 3],
            'cancelled' => ['bg' => '#FEE2E2', 'color' => '#991B1B', 'label' => 'Đã hủy',         'icon' => 'bi-x-circle',       'step' => -1],
        ];
        $sc = $statusMap[$order->status] ?? ['bg' => '#F1F5F9', 'color' => '#475569', 'label' => $order->status, 'icon' => 'bi-circle', 'step' => 0];
        $currentStep = $sc['step'];
    @endphp

    @if($order->status !== 'cancelled')
    {{-- Timeline trạng thái --}}
    <div style="background:var(--card); border:1px solid var(--border); border-radius:8px; padding:1.25rem 1.5rem; margin-bottom:1.5rem;">
        <div class="timeline">
            @php
                $steps = [
                    ['label' => 'Chờ xử lý',    'icon' => 'bi-clock',        'step' => 0],
                    ['label' => 'Xác nhận',      'icon' => 'bi-check-circle', 'step' => 1],
                    ['label' => 'Đang giao',     'icon' => 'bi-truck',        'step' => 2],
                    ['label' => 'Đã giao',       'icon' => 'bi-bag-check',    'step' => 3],
                ];
            @endphp
            @foreach($steps as $i => $step)
            @php
                $isDone   = $currentStep > $step['step'];
                $isActive = $currentStep === $step['step'];
                $stepClass = $isDone ? 'done' : ($isActive ? 'active' : '');
            @endphp
            <div class="timeline-step {{ $stepClass }}">
                <div class="timeline-dot">
                    <i class="bi {{ $isDone ? 'bi-check' : $step['icon'] }}"></i>
                </div>
                <div class="timeline-label">{{ $step['label'] }}</div>
            </div>
            @endforeach
        </div>
        <div class="text-center">
            <span class="status-badge-lg" style="background:{{ $sc['bg'] }}; color:{{ $sc['color'] }};">
                <i class="bi {{ $sc['icon'] }}"></i>
                {{ $sc['label'] }}
            </span>
        </div>
    </div>
    @else
    {{-- Trạng thái hủy --}}
    <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:8px; padding:1rem 1.25rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:0.75rem;">
        <i class="bi bi-x-circle-fill" style="color:var(--danger); font-size:1.25rem;"></i>
        <div>
            <div style="font-weight:700; color:#991B1B;">Đơn hàng đã bị hủy</div>
            <div style="font-size:0.82rem; color:#B91C1C; margin-top:2px;">Đơn hàng này đã bị hủy. Nếu bạn đã thanh toán, vui lòng liên hệ hỗ trợ.</div>
        </div>
    </div>
    @endif

    <div class="row g-4">

        {{-- Cột trái: Sản phẩm --}}
        <div class="col-lg-8">
            <div class="info-card">
                <div class="info-card-header">
                    <div class="card-icon"><i class="bi bi-bag"></i></div>
                    <h5>Sản Phẩm Đã Đặt</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 order-items-table">
                        <thead>
                            <tr>
                                <th style="width:50%;">Sách</th>
                                <th class="text-center">Đơn giá</th>
                                <th class="text-center">SL</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($item->book->cover_image)
                                            <img src="{{ $item->book->cover_image_url }}"
                                                 alt="{{ $item->book->title }}"
                                                 style="width:44px; height:58px; object-fit:cover; border-radius:4px; border:1px solid var(--border); flex-shrink:0;">
                                        @else
                                            <div style="width:44px; height:58px; background:var(--bg); border-radius:4px; border:1px solid var(--border); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                <i class="bi bi-book" style="color:var(--text-muted);"></i>
                                            </div>
                                        @endif
                                        <a href="{{ route('books.show', $item->book->slug) }}"
                                           style="color:var(--text); font-weight:600; text-decoration:none; font-size:0.875rem; line-height:1.4; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                            {{ $item->book->title }}
                                        </a>
                                    </div>
                                </td>
                                <td class="text-center" style="font-size:0.875rem; color:var(--text-muted);">
                                    {{ number_format($item->price, 0, ',', '.') }}đ
                                </td>
                                <td class="text-center">
                                    <span style="font-weight:600; font-size:0.875rem; background:var(--bg); border:1px solid var(--border); padding:2px 10px; border-radius:4px;">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span style="font-weight:700; color:var(--danger); font-size:0.9rem;">
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:var(--bg);">
                                <td colspan="3" class="text-end" style="padding:0.875rem 1rem;">
                                    <span style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; font-weight:600;">Phí vận chuyển</span>
                                </td>
                                <td class="text-end" style="padding:0.875rem 1rem;">
                                    <span style="color:var(--success); font-weight:600; font-size:0.875rem;">Miễn phí</span>
                                </td>
                            </tr>
                            <tr style="background:var(--bg); border-top:2px solid var(--border);">
                                <td colspan="3" class="text-end" style="padding:1rem;">
                                    <span style="font-weight:700; color:var(--text); font-size:0.9rem;">Tổng cộng</span>
                                </td>
                                <td class="text-end" style="padding:1rem;">
                                    <span style="font-weight:700; font-size:1.2rem; color:var(--danger);">
                                        {{ number_format($order->total_amount, 0, ',', '.') }}đ
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Cột phải: Thông tin --}}
        <div class="col-lg-4">

            {{-- Thông tin đơn hàng --}}
            <div class="info-card">
                <div class="info-card-header">
                    <div class="card-icon"><i class="bi bi-info-circle"></i></div>
                    <h5>Thông Tin Đơn Hàng</h5>
                </div>
                <div class="info-card-body">
                    <div class="info-row" style="padding-top:0;">
                        <span class="info-label">Mã đơn</span>
                        <span class="info-value" style="font-family:'Courier New',monospace; color:var(--primary);">
                            #{{ $order->order_code }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ngày đặt</span>
                        <span class="info-value" style="font-weight:500;">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Trạng thái</span>
                        <span>
                            <span class="status-badge-lg" style="background:{{ $sc['bg'] }}; color:{{ $sc['color'] }}; padding:3px 10px; font-size:0.75rem;">
                                {{ $sc['label'] }}
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Thanh toán</span>
                        <span class="info-value" style="font-weight:500;">
                            {{ $order->payment_method === 'cod' ? 'COD' : 'VNPay' }}
                        </span>
                    </div>
                    <div class="info-row" style="padding-bottom:0;">
                        <span class="info-label">Trạng thái TT</span>
                        @if($order->payment_status === 'paid')
                            <span style="background:#DCFCE7; color:#166534; padding:3px 10px; border-radius:4px; font-size:0.75rem; font-weight:600;">Đã thanh toán</span>
                        @else
                            <span style="background:#FEF3C7; color:#92400E; padding:3px 10px; border-radius:4px; font-size:0.75rem; font-weight:600;">Chưa thanh toán</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Thông tin nhận hàng --}}
            <div class="info-card">
                <div class="info-card-header">
                    <div class="card-icon"><i class="bi bi-geo-alt"></i></div>
                    <h5>Thông Tin Nhận Hàng</h5>
                </div>
                <div class="info-card-body">
                    <div class="info-row" style="padding-top:0;">
                        <span class="info-label">Người nhận</span>
                        <span class="info-value">{{ $order->receiver_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Điện thoại</span>
                        <span class="info-value">{{ $order->receiver_phone }}</span>
                    </div>
                    <div class="info-row" style="{{ $order->note ? '' : 'padding-bottom:0;' }}">
                        <span class="info-label">Địa chỉ</span>
                        <span class="info-value" style="font-weight:400; line-height:1.5;">{{ $order->receiver_address }}</span>
                    </div>
                    @if($order->note)
                    <div class="info-row" style="padding-bottom:0;">
                        <span class="info-label">Ghi chú</span>
                        <span class="info-value" style="font-weight:400; font-style:italic; color:var(--text-muted);">{{ $order->note }}</span>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
