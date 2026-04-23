@extends('layouts.admin')

@section('title', 'Quản lý sách')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Quản lý sách</h1>
        <p class="page-header-sub">Danh sách tất cả sách trong hệ thống</p>
    </div>
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Thêm sách mới
    </a>
</div>

{{-- Bộ lọc --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('admin.books.index') }}" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label mb-1">Tìm kiếm</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text" style="background:#F8FAFC;border-color:var(--border);">
                    <i class="bi bi-search" style="color:var(--text-muted);font-size:0.8rem;"></i>
                </span>
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Tên sách, tác giả, ISBN..."
                       value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label mb-1">Danh mục</label>
            <select name="category_id" class="form-select form-select-sm">
                <option value="">-- Tất cả danh mục --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-auto d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm px-3">
                <i class="bi bi-funnel me-1"></i> Lọc
            </button>
            @if(request('search') || request('category_id'))
                <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="bi bi-x-lg me-1"></i> Xóa lọc
                </a>
            @endif
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-header-title">
            Danh sách sách
            @if(request('search') || request('category_id'))
                <span style="font-size:0.75rem;font-weight:400;color:var(--text-muted);margin-left:6px;">(đã lọc)</span>
            @endif
        </h6>
        <span style="font-size:0.8rem;color:var(--text-muted);">{{ $books->total() }} sách</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="padding-left:20px;width:55px;">ID</th>
                        <th style="width:54px;">Ảnh</th>
                        <th>Tiêu đề</th>
                        <th style="width:130px;">Tác giả</th>
                        <th style="width:120px;">Danh mục</th>
                        <th style="width:130px;">Giá bán</th>
                        <th style="width:80px;text-align:center;">Kho</th>
                        <th style="width:110px;">Trạng thái</th>
                        <th style="width:80px;text-align:center;">Nổi bật</th>
                        <th style="padding-right:20px;width:100px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                    <tr>
                        <td style="padding-left:20px;color:var(--text-muted);">{{ $book->id }}</td>
                        <td>
                            @if($book->cover_image)
                                <img src="{{ $book->cover_image_url }}"
                                     alt="{{ $book->title }}"
                                     width="38" height="50"
                                     class="img-cover">
                            @else
                                <div class="img-placeholder" style="width:38px;height:50px;">
                                    <i class="bi bi-image" style="font-size:0.8rem;"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-truncate" style="max-width:200px;font-size:0.875rem;" title="{{ $book->title }}">
                                {{ $book->title }}
                            </div>
                            @if($book->isbn)
                                <div style="font-size:0.75rem;color:var(--text-muted);">ISBN: {{ $book->isbn }}</div>
                            @endif
                        </td>
                        <td style="font-size:0.875rem;color:var(--text-muted);">{{ $book->author }}</td>
                        <td>
                            @if($book->category)
                                <span class="badge-status badge-category">{{ $book->category->name }}</span>
                            @else
                                <span style="color:var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td>
                            @if($book->sale_price && $book->sale_price < $book->price)
                                <div class="fw-semibold" style="font-size:0.875rem;color:#DC2626;">
                                    {{ number_format($book->sale_price, 0, ',', '.') }}₫
                                </div>
                                <div style="font-size:0.75rem;color:var(--text-muted);text-decoration:line-through;">
                                    {{ number_format($book->price, 0, ',', '.') }}₫
                                </div>
                            @else
                                <div style="font-size:0.875rem;">{{ number_format($book->price, 0, ',', '.') }}₫</div>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <span class="{{ $book->stock < 10 ? 'stock-low' : 'stock-ok' }}" style="font-size:0.875rem;">
                                {{ number_format($book->stock) }}
                                @if($book->stock < 10)
                                    <i class="bi bi-exclamation-circle-fill ms-1" style="font-size:0.7rem;" title="Sắp hết hàng"></i>
                                @endif
                            </span>
                        </td>
                        <td>
                            @if($book->is_active)
                                <span class="badge-status badge-active">
                                    <i class="bi bi-eye-fill" style="font-size:0.6rem;"></i> Hiển thị
                                </span>
                            @else
                                <span class="badge-status badge-inactive">
                                    <i class="bi bi-eye-slash-fill" style="font-size:0.6rem;"></i> Ẩn
                                </span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($book->is_featured)
                                <i class="bi bi-star-fill" style="color:#F59E0B;" title="Sách nổi bật"></i>
                            @else
                                <i class="bi bi-star" style="color:#CBD5E1;"></i>
                            @endif
                        </td>
                        <td style="padding-right:20px;">
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.books.edit', $book) }}"
                                   class="btn btn-icon btn-outline-secondary"
                                   title="Chỉnh sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-icon btn-outline-danger"
                                        title="Xóa sách"
                                        onclick="confirmDeleteBook('form-del-book-{{ $book->id }}', '{{ addslashes($book->title) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="form-del-book-{{ $book->id }}"
                                      action="{{ route('admin.books.destroy', $book) }}"
                                      method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-book-x"></i></div>
                                <div class="empty-state-title">Không tìm thấy sách nào</div>
                                <div class="empty-state-sub">Thử thay đổi bộ lọc hoặc thêm sách mới.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($books->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small style="color:var(--text-muted);">
            Hiển thị {{ $books->firstItem() }}–{{ $books->lastItem() }} trong {{ $books->total() }} sách
        </small>
        {{ $books->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteBookModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content" style="border:1px solid var(--border);border-radius:10px;">
            <div class="modal-body p-4 text-center">
                <div style="width:52px;height:52px;background:#FFF1F2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#DC2626;font-size:1.3rem;"></i>
                </div>
                <h5 style="font-size:1rem;font-weight:700;margin-bottom:8px;">Xác nhận xóa sách</h5>
                <p style="font-size:0.875rem;color:var(--text-muted);" id="deleteBookMessage">
                    Bạn có chắc muốn xóa sách này không?
                </p>
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger px-4" id="deleteBookConfirmBtn">Xóa sách</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let targetBookFormId = null;
    const deleteBookModal = new bootstrap.Modal(document.getElementById('deleteBookModal'));

    function confirmDeleteBook(formId, title) {
        targetBookFormId = formId;
        document.getElementById('deleteBookMessage').textContent =
            'Bạn có chắc muốn xóa sách «' + title + '» không? Hành động này không thể hoàn tác.';
        deleteBookModal.show();
    }

    document.getElementById('deleteBookConfirmBtn').addEventListener('click', function () {
        if (targetBookFormId) {
            document.getElementById(targetBookFormId).submit();
        }
    });
</script>
@endpush
