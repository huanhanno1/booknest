@extends('layouts.admin')

@section('title', 'Danh mục sách')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Danh mục sách</h1>
        <p class="page-header-sub">Quản lý các danh mục phân loại sách</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Thêm danh mục
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-header-title">Tất cả danh mục</h6>
        <span style="font-size:0.8rem;color:var(--text-muted);">{{ $categories->count() }} danh mục</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="padding-left:20px;width:60px;">ID</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th style="width:110px;">Số sách</th>
                        <th style="width:120px;">Trạng thái</th>
                        <th style="padding-right:20px;width:110px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td style="padding-left:20px;color:var(--text-muted);">{{ $category->id }}</td>
                        <td>
                            <div class="fw-semibold" style="font-size:0.9rem;">{{ $category->name }}</div>
                            @if($category->description)
                                <div style="font-size:0.78rem;color:var(--text-muted);margin-top:2px;">
                                    {{ Str::limit($category->description, 60) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <code style="font-size:0.8rem;color:var(--text-muted);background:#F1F5F9;padding:2px 6px;border-radius:4px;">
                                {{ $category->slug }}
                            </code>
                        </td>
                        <td>
                            <span class="badge-status badge-category">
                                <i class="bi bi-book" style="font-size:0.65rem;"></i>
                                {{ $category->books_count ?? $category->books->count() }} sách
                            </span>
                        </td>
                        <td>
                            @if($category->is_active)
                                <span class="badge-status badge-active">
                                    <i class="bi bi-check-circle-fill" style="font-size:0.65rem;"></i> Hiển thị
                                </span>
                            @else
                                <span class="badge-status badge-inactive">
                                    <i class="bi bi-eye-slash-fill" style="font-size:0.65rem;"></i> Ẩn
                                </span>
                            @endif
                        </td>
                        <td style="padding-right:20px;">
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="btn btn-icon btn-outline-secondary"
                                   title="Chỉnh sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-icon btn-outline-danger"
                                        title="Xóa danh mục"
                                        onclick="confirmDelete('form-delete-cat-{{ $category->id }}', '{{ addslashes($category->name) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="form-delete-cat-{{ $category->id }}"
                                      action="{{ route('admin.categories.destroy', $category) }}"
                                      method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-folder-x"></i></div>
                                <div class="empty-state-title">Chưa có danh mục nào</div>
                                <div class="empty-state-sub">Bắt đầu bằng cách thêm danh mục đầu tiên.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content" style="border:1px solid var(--border);border-radius:10px;">
            <div class="modal-body p-4 text-center">
                <div style="width:52px;height:52px;background:#FFF1F2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#DC2626;font-size:1.3rem;"></i>
                </div>
                <h5 style="font-size:1rem;font-weight:700;margin-bottom:8px;">Xác nhận xóa</h5>
                <p style="font-size:0.875rem;color:var(--text-muted);" id="deleteModalMessage">
                    Bạn có chắc muốn xóa danh mục này không?
                </p>
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger px-4" id="deleteConfirmBtn">Xóa</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let targetFormId = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

    function confirmDelete(formId, name) {
        targetFormId = formId;
        document.getElementById('deleteModalMessage').textContent =
            'Bạn có chắc muốn xóa danh mục «' + name + '» không? Hành động này không thể hoàn tác.';
        deleteModal.show();
    }

    document.getElementById('deleteConfirmBtn').addEventListener('click', function () {
        if (targetFormId) {
            document.getElementById(targetFormId).submit();
        }
    });
</script>
@endpush
