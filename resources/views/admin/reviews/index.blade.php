@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Quản lý đánh giá</h1>
        <p class="page-header-sub">Đánh giá sách từ người dùng — phê duyệt hoặc từ chối</p>
    </div>
</div>

{{-- Bộ lọc trạng thái --}}
<div class="status-tabs">
    <a href="{{ route('admin.reviews.index') }}"
       class="status-tab {{ !request('status') ? 'active' : '' }}">
        <i class="bi bi-grid-3x3-gap" style="font-size:0.8rem;"></i> Tất cả
    </a>
    <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}"
       class="status-tab {{ request('status') === 'pending' ? 'active' : '' }}">
        <i class="bi bi-hourglass-split" style="font-size:0.8rem;"></i> Chờ duyệt
    </a>
    <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}"
       class="status-tab {{ request('status') === 'approved' ? 'active' : '' }}">
        <i class="bi bi-check-circle" style="font-size:0.8rem;"></i> Đã duyệt
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-header-title">Danh sách đánh giá</h6>
        <span style="font-size:0.8rem;color:var(--text-muted);">{{ $reviews->total() }} đánh giá</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="padding-left:20px;width:55px;">ID</th>
                        <th style="min-width:180px;">Sách</th>
                        <th style="width:150px;">Người đánh giá</th>
                        <th style="width:140px;">Đánh giá sao</th>
                        <th>Nhận xét</th>
                        <th style="width:110px;">Trạng thái</th>
                        <th style="padding-right:20px;width:120px;text-align:center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr>
                        <td style="padding-left:20px;color:var(--text-muted);">{{ $review->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($review->book && $review->book->cover_image)
                                    <img src="{{ $review->book->cover_image_url }}"
                                         alt="{{ $review->book->title }}"
                                         width="30" height="40"
                                         class="img-cover"
                                         style="flex-shrink:0;">
                                @endif
                                <div class="fw-semibold text-truncate" style="max-width:160px;font-size:0.875rem;" title="{{ $review->book->title ?? '—' }}">
                                    {{ $review->book->title ?? '—' }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-size:0.875rem;font-weight:500;">{{ $review->user->name ?? '—' }}</div>
                            @if($review->created_at)
                                <div style="font-size:0.75rem;color:var(--text-muted);">{{ $review->created_at->format('d/m/Y') }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill star-fill' : ' star-empty' }}"
                                       style="font-size:0.9rem;"></i>
                                @endfor
                                <span style="font-size:0.78rem;color:var(--text-muted);margin-left:4px;">
                                    {{ $review->rating }}/5
                                </span>
                            </div>
                        </td>
                        <td>
                            @if($review->comment)
                                <div style="font-size:0.875rem;color:var(--text-muted);line-height:1.4;"
                                     title="{{ $review->comment }}">
                                    {{ Str::limit($review->comment, 90, '...') }}
                                </div>
                            @else
                                <span style="color:var(--text-muted);font-style:italic;font-size:0.82rem;">Không có nhận xét</span>
                            @endif
                        </td>
                        <td>
                            @if($review->is_approved)
                                <span class="badge-status badge-approved">
                                    <i class="bi bi-check-circle-fill" style="font-size:0.6rem;"></i> Đã duyệt
                                </span>
                            @else
                                <span class="badge-status badge-pending-review">
                                    <i class="bi bi-hourglass-split" style="font-size:0.6rem;"></i> Chờ duyệt
                                </span>
                            @endif
                        </td>
                        <td style="padding-right:20px;">
                            <div class="d-flex gap-1 justify-content-center">
                                {{-- Toggle duyệt --}}
                                <form action="{{ route('admin.reviews.toggleApproval', $review) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    @if($review->is_approved)
                                        <button type="submit"
                                                class="btn btn-icon btn-outline-warning"
                                                title="Bỏ duyệt đánh giá này">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    @else
                                        <button type="submit"
                                                class="btn btn-icon btn-outline-success"
                                                title="Duyệt đánh giá này">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    @endif
                                </form>
                                {{-- Xóa --}}
                                <button type="button"
                                        class="btn btn-icon btn-outline-danger"
                                        title="Xóa đánh giá"
                                        onclick="confirmDeleteReview('form-del-review-{{ $review->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="form-del-review-{{ $review->id }}"
                                      action="{{ route('admin.reviews.destroy', $review) }}"
                                      method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-chat-square-x"></i></div>
                                <div class="empty-state-title">Chưa có đánh giá nào</div>
                                <div class="empty-state-sub">
                                    @if(request('status') === 'pending')
                                        Không có đánh giá nào đang chờ duyệt.
                                    @elseif(request('status') === 'approved')
                                        Chưa có đánh giá nào được duyệt.
                                    @else
                                        Hệ thống chưa có đánh giá nào.
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
    @if($reviews->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small style="color:var(--text-muted);">
            Hiển thị {{ $reviews->firstItem() }}–{{ $reviews->lastItem() }} trong {{ $reviews->total() }} đánh giá
        </small>
        {{ $reviews->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteReviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content" style="border:1px solid var(--border);border-radius:10px;">
            <div class="modal-body p-4 text-center">
                <div style="width:52px;height:52px;background:#FFF1F2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#DC2626;font-size:1.3rem;"></i>
                </div>
                <h5 style="font-size:1rem;font-weight:700;margin-bottom:8px;">Xóa đánh giá</h5>
                <p style="font-size:0.875rem;color:var(--text-muted);">
                    Bạn có chắc muốn xóa đánh giá này không? Hành động này không thể hoàn tác.
                </p>
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger px-4" id="deleteReviewConfirmBtn">Xóa</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let targetReviewFormId = null;
    const deleteReviewModal = new bootstrap.Modal(document.getElementById('deleteReviewModal'));

    function confirmDeleteReview(formId) {
        targetReviewFormId = formId;
        deleteReviewModal.show();
    }

    document.getElementById('deleteReviewConfirmBtn').addEventListener('click', function () {
        if (targetReviewFormId) {
            document.getElementById(targetReviewFormId).submit();
        }
    });
</script>
@endpush
