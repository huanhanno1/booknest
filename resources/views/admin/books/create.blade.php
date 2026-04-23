@extends('layouts.admin')

@section('title', 'Thêm sách mới')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Thêm sách mới</h1>
        <p class="page-header-sub">
            <a href="{{ route('admin.books.index') }}" class="text-decoration-none" style="color:var(--text-muted);">Quản lý sách</a>
            <i class="bi bi-chevron-right mx-1" style="font-size:0.65rem;"></i> Thêm mới
        </p>
    </div>
    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
</div>

<form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        {{-- Cột trái: Thông tin chính --}}
        <div class="col-lg-8">
            {{-- Thông tin cơ bản --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-header-title"><i class="bi bi-info-circle me-2" style="color:var(--primary);"></i>Thông tin cơ bản</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề sách <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}"
                               placeholder="Nhập tiêu đề sách" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug URL</label>
                        <div class="input-group">
                            <span class="input-group-text" style="font-size:0.82rem;color:var(--text-muted);background:#F8FAFC;border-color:var(--border);">/sach/</span>
                            <input type="text" name="slug" id="slug"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug') }}"
                                   placeholder="tu-dong-tao-tu-tieu-de">
                            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="author" class="form-label">Tác giả <span class="text-danger">*</span></label>
                            <input type="text" name="author" id="author"
                                   class="form-control @error('author') is-invalid @enderror"
                                   value="{{ old('author') }}" placeholder="Tên tác giả" required>
                            @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-0">
                        <div class="col-md-6">
                            <label for="publisher" class="form-label">Nhà xuất bản</label>
                            <input type="text" name="publisher" id="publisher"
                                   class="form-control @error('publisher') is-invalid @enderror"
                                   value="{{ old('publisher') }}" placeholder="Tên NXB">
                            @error('publisher')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="publish_year" class="form-label">Năm xuất bản</label>
                            <input type="number" name="publish_year" id="publish_year"
                                   class="form-control @error('publish_year') is-invalid @enderror"
                                   value="{{ old('publish_year') }}" placeholder="{{ date('Y') }}"
                                   min="1900" max="{{ date('Y') + 1 }}">
                            @error('publish_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="pages" class="form-label">Số trang</label>
                            <input type="number" name="pages" id="pages"
                                   class="form-control @error('pages') is-invalid @enderror"
                                   value="{{ old('pages') }}" placeholder="300" min="1">
                            @error('pages')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="description" class="form-label">Mô tả / Giới thiệu sách</label>
                        <textarea name="description" id="description" rows="5"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Nội dung giới thiệu về cuốn sách...">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Cột phải: Giá, ảnh, cài đặt --}}
        <div class="col-lg-4">
            {{-- Giá & Kho --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-header-title"><i class="bi bi-currency-dollar me-2" style="color:#16A34A;"></i>Giá & Kho hàng</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="price" class="form-label">Giá gốc (₫) <span class="text-danger">*</span></label>
                        <input type="number" name="price" id="price"
                               class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price') }}" placeholder="150000" min="0" required>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="sale_price" class="form-label">Giá khuyến mãi (₫)</label>
                        <input type="number" name="sale_price" id="sale_price"
                               class="form-control @error('sale_price') is-invalid @enderror"
                               value="{{ old('sale_price') }}" placeholder="120000" min="0">
                        <div class="form-text">Để trống nếu không giảm giá.</div>
                        @error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Số lượng kho <span class="text-danger">*</span></label>
                        <input type="number" name="stock" id="stock"
                               class="form-control @error('stock') is-invalid @enderror"
                               value="{{ old('stock', 0) }}" min="0" required>
                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-0">
                        <label for="isbn" class="form-label">Mã ISBN</label>
                        <input type="text" name="isbn" id="isbn"
                               class="form-control @error('isbn') is-invalid @enderror"
                               value="{{ old('isbn') }}" placeholder="978-xxxxxxxxxx">
                        @error('isbn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Ảnh bìa --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-header-title"><i class="bi bi-image me-2" style="color:#7C3AED;"></i>Ảnh bìa sách</h6>
                </div>
                <div class="card-body">
                    <div id="cover-preview-wrap" style="display:none;margin-bottom:12px;text-align:center;">
                        <img id="preview-img" src="#" alt="Xem trước"
                             style="max-width:100%;max-height:220px;border-radius:6px;border:1px solid var(--border);">
                    </div>
                    <div id="cover-upload-area"
                         style="border:2px dashed var(--border);border-radius:8px;padding:24px;text-align:center;cursor:pointer;transition:border-color 0.15s;"
                         onclick="document.getElementById('cover_image').click()"
                         ondragover="event.preventDefault();this.style.borderColor='var(--primary)'"
                         ondragleave="this.style.borderColor='var(--border)'"
                         ondrop="handleDrop(event)">
                        <i class="bi bi-cloud-upload" style="font-size:1.5rem;color:var(--text-muted);display:block;margin-bottom:8px;"></i>
                        <div style="font-size:0.82rem;color:var(--text-muted);">Kéo thả ảnh vào đây hoặc <span style="color:var(--primary);font-weight:500;">chọn file</span></div>
                        <div style="font-size:0.75rem;color:#94A3B8;margin-top:4px;">JPG, PNG, WebP — Tối đa 2MB</div>
                    </div>
                    <input type="file" name="cover_image" id="cover_image"
                           class="@error('cover_image') is-invalid @enderror"
                           accept="image/*"
                           onchange="previewCover(this)"
                           style="display:none;">
                    @error('cover_image')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Cài đặt --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-header-title"><i class="bi bi-gear me-2" style="color:var(--text-muted);"></i>Cài đặt hiển thị</h6>
                </div>
                <div class="card-body">
                    <div class="p-3 mb-2" style="background:#F8FAFC;border:1px solid var(--border);border-radius:8px;">
                        <div class="form-check mb-0">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                                   {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label" style="font-size:0.875rem;font-weight:600;">
                                Hiển thị sách
                            </label>
                            <div class="form-text">Sách sẽ xuất hiện trên trang người dùng.</div>
                        </div>
                    </div>
                    <div class="p-3" style="background:#F8FAFC;border:1px solid var(--border);border-radius:8px;">
                        <div class="form-check mb-0">
                            <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" value="1"
                                   {{ old('is_featured') ? 'checked' : '' }}>
                            <label for="is_featured" class="form-check-label" style="font-size:0.875rem;font-weight:600;">
                                Sách nổi bật
                            </label>
                            <div class="form-text">Hiển thị trong mục sách nổi bật trang chủ.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="card mb-4">
        <div class="card-body py-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i> Lưu sách
            </button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary px-4">
                Hủy
            </a>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function previewCover(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('cover-preview-wrap').style.display = 'block';
                document.getElementById('cover-upload-area').style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function handleDrop(event) {
        event.preventDefault();
        event.currentTarget.style.borderColor = 'var(--border)';
        const files = event.dataTransfer.files;
        if (files.length) {
            const input = document.getElementById('cover_image');
            const dt = new DataTransfer();
            dt.items.add(files[0]);
            input.files = dt.files;
            previewCover(input);
        }
    }

    // Slug auto-generate
    const slugField = document.getElementById('slug');
    let slugManual = false;

    document.getElementById('title').addEventListener('input', function () {
        if (!slugManual) slugField.value = generateSlug(this.value);
    });
    slugField.addEventListener('input', function () {
        slugManual = this.value.trim() !== '';
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
