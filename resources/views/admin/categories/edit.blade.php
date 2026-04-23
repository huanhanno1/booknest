@extends('layouts.admin')

@section('title', 'Chỉnh sửa danh mục')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Chỉnh sửa danh mục</h1>
        <p class="page-header-sub">
            <a href="{{ route('admin.categories.index') }}" class="text-decoration-none" style="color:var(--text-muted);">Danh mục</a>
            <i class="bi bi-chevron-right mx-1" style="font-size:0.65rem;"></i>
            {{ $category->name }}
        </p>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-header-title">Thông tin danh mục</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Tên danh mục <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $category->name) }}"
                               placeholder="Tên danh mục"
                               required
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug URL</label>
                        <div class="input-group">
                            <span class="input-group-text" style="font-size:0.82rem;color:var(--text-muted);background:#F8FAFC;border-color:var(--border);">/danh-muc/</span>
                            <input type="text"
                                   name="slug"
                                   id="slug"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug', $category->slug) }}"
                                   placeholder="slug-danh-muc">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">Thay đổi slug sẽ ảnh hưởng đến URL của danh mục.</div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Mô tả ngắn về danh mục...">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 p-3" style="background:#F8FAFC;border:1px solid var(--border);border-radius:8px;">
                        <div class="form-check mb-0">
                            <input type="checkbox"
                                   name="is_active"
                                   id="is_active"
                                   class="form-check-input"
                                   value="1"
                                   {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label" style="font-weight:600;font-size:0.875rem;">
                                Hiển thị danh mục
                            </label>
                            <div class="form-text">Bỏ chọn để ẩn danh mục khỏi trang người dùng.</div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Cập nhật
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary px-4">
                            Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Thông tin thêm --}}
        <div class="card mt-3">
            <div class="card-body py-3 px-4">
                <div class="d-flex gap-4" style="font-size:0.8rem;color:var(--text-muted);">
                    <div>
                        <i class="bi bi-calendar3 me-1"></i>
                        Tạo lúc: {{ $category->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <i class="bi bi-pencil-square me-1"></i>
                        Cập nhật: {{ $category->updated_at->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <i class="bi bi-book me-1"></i>
                        {{ $category->books->count() }} sách
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const originalSlug = '{{ $category->slug }}';
    const slugField = document.getElementById('slug');
    let slugManual = false;

    document.getElementById('name').addEventListener('input', function () {
        if (!slugManual) slugField.value = generateSlug(this.value);
    });

    slugField.addEventListener('input', function () {
        slugManual = this.value.trim() !== originalSlug;
    });

    function generateSlug(text) {
        const map = {
            'à':'a','á':'a','ả':'a','ã':'a','ạ':'a','ă':'a','ằ':'a','ắ':'a','ẳ':'a','ẵ':'a','ặ':'a',
            'â':'a','ầ':'a','ấ':'a','ẩ':'a','ẫ':'a','ậ':'a','è':'e','é':'e','ẻ':'e','ẽ':'e','ẹ':'e',
            'ê':'e','ề':'e','ế':'e','ể':'e','ễ':'e','ệ':'e','ì':'i','í':'i','ỉ':'i','ĩ':'i','ị':'i',
            'ò':'o','ó':'o','ỏ':'o','õ':'o','ọ':'o','ô':'o','ồ':'o','ố':'o','ổ':'o','ỗ':'o','ộ':'o',
            'ơ':'o','ờ':'o','ớ':'o','ở':'o','ỡ':'o','ợ':'o','ù':'u','ú':'u','ủ':'u','ũ':'u','ụ':'u',
            'ư':'u','ừ':'u','ứ':'u','ử':'u','ữ':'u','ự':'u','ỳ':'y','ý':'y','ỷ':'y','ỹ':'y','ỵ':'y',
            'đ':'d',
            'À':'a','Á':'a','Ả':'a','Ã':'a','Ạ':'a','Ă':'a','Ằ':'a','Ắ':'a','Ẳ':'a','Ẵ':'a','Ặ':'a',
            'Â':'a','Ầ':'a','Ấ':'a','Ẩ':'a','Ẫ':'a','Ậ':'a','È':'e','É':'e','Ẻ':'e','Ẽ':'e','Ẹ':'e',
            'Ê':'e','Ề':'e','Ế':'e','Ể':'e','Ễ':'e','Ệ':'e','Ì':'i','Í':'i','Ỉ':'i','Ĩ':'i','Ị':'i',
            'Ò':'o','Ó':'o','Ỏ':'o','Õ':'o','Ọ':'o','Ô':'o','Ồ':'o','Ố':'o','Ổ':'o','Ỗ':'o','Ộ':'o',
            'Ơ':'o','Ờ':'o','Ớ':'o','Ở':'o','Ỡ':'o','Ợ':'o','Ù':'u','Ú':'u','Ủ':'u','Ũ':'u','Ụ':'u',
            'Ư':'u','Ừ':'u','Ứ':'u','Ử':'u','Ữ':'u','Ự':'u','Ỳ':'y','Ý':'y','Ỷ':'y','Ỹ':'y','Ỵ':'y',
            'Đ':'d'
        };
        return text.split('').map(c => map[c] || c).join('')
            .toLowerCase().replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-').replace(/-+/g, '-');
    }
</script>
@endpush
