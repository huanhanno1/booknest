@extends('layouts.admin')

@section('title', 'Chỉnh sửa sách')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Chỉnh sửa sách</h1>
        <p class="page-header-sub">
            <a href="{{ route('admin.books.index') }}" class="text-decoration-none" style="color:var(--text-muted);">Quản lý sách</a>
            <i class="bi bi-chevron-right mx-1" style="font-size:0.65rem;"></i>
            <span class="text-truncate" style="max-width:200px;display:inline-block;vertical-align:bottom;">{{ $book->title }}</span>
        </p>
    </div>
    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
</div>

<form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-3">
        {{-- Cột trái --}}
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-header-title"><i class="bi bi-info-circle me-2" style="color:var(--primary);"></i>Thông tin cơ bản</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề sách <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $book->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug URL</label>
                        <div class="input-group">
                            <span class="input-group-text" style="font-size:0.82rem;color:var(--text-muted);background:#F8FAFC;border-color:var(--border);">/sach/</span>
                            <input type="text" name="slug" id="slug"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug', $book->slug) }}">
                            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="author" class="form-label">Tác giả <span class="text-danger">*</span></label>
                            <input type="text" name="author" id="author"
                                   class="form-control @error('author') is-invalid @enderror"
                                   value="{{ old('author', $book->author) }}" required>
                            @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>
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
                                   value="{{ old('publisher', $book->publisher) }}">
                            @error('publisher')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="publish_year" class="form-label">Năm xuất bản</label>
                            <input type="number" name="publish_year" id="publish_year"
                                   class="form-control @error('publish_year') is-invalid @enderror"
                                   value="{{ old('publish_year', $book->publish_year) }}"
                                   min="1900" max="{{ date('Y') + 1 }}">
                            @error('publish_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="pages" class="form-label">Số trang</label>
                            <input type="number" name="pages" id="pages"
                                   class="form-control @error('pages') is-invalid @enderror"
                                   value="{{ old('pages', $book->pages) }}" min="1">
                            @error('pages')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="description" class="form-label">Mô tả / Giới thiệu sách</label>
                        <textarea name="description" id="description" rows="5"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $book->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Cột phải --}}
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
                               value="{{ old('price', $book->price) }}" min="0" required>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="sale_price" class="form-label">Giá khuyến mãi (₫)</label>
                        <input type="number" name="sale_price" id="sale_price"
                               class="form-control @error('sale_price') is-invalid @enderror"
                               value="{{ old('sale_price', $book->sale_price) }}" min="0">
                        <div class="form-text">Để trống nếu không giảm giá.</div>
                        @error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Số lượng kho <span class="text-danger">*</span></label>
                        <input type="number" name="stock" id="stock"
                               class="form-control @error('stock') is-invalid @enderror"
                               value="{{ old('stock', $book->stock) }}" min="0" required>
                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-0">
                        <label for="isbn" class="form-label">Mã ISBN</label>
                        <input type="text" name="isbn" id="isbn"
                               class="form-control @error('isbn') is-invalid @enderror"
                               value="{{ old('isbn', $book->isbn) }}">
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
                    @if($book->cover_image)
                    <div id="current-cover" style="margin-bottom:12px;">
                        <div style="font-size:0.78rem;color:var(--text-muted);margin-bottom:6px;">Ảnh hiện tại:</div>
                        <img src="{{ $book->cover_image_url }}"
                             alt="{{ $book->title }}"
                             id="preview-img"
                             style="max-width:100%;max-height:200px;border-radius:6px;border:1px solid var(--border);display:block;">
                    </div>
                    @else
                    <div id="cover-preview-wrap" style="display:none;margin-bottom:12px;">
                        <img id="preview-img" src="#" alt="Xem trước"
                             style="max-width:100%;max-height:200px;border-radius:6px;border:1px solid var(--border);display:block;">
                    </div>
                    @endif

                    <label for="cover_image" class="form-label">
                        {{ $book->cover_image ? 'Thay ảnh mới' : 'Tải ảnh lên' }}
                    </label>
                    <input type="file" name="cover_image" id="cover_image"
                           class="form-control @error('cover_image') is-invalid @enderror"
                           accept="image/*"
                           onchange="previewCover(this)">
                    <div class="form-text">JPG, PNG, WebP — Tối đa 2MB.</div>
                    @error('cover_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @if(!$book->cover_image)
                        <div id="cover-preview-wrap" style="display:none;margin-top:10px;">
                            <div style="font-size:0.78rem;color:var(--text-muted);margin-bottom:6px;">Ảnh mới:</div>
                        </div>
                    @endif
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
                                   {{ old('is_active', $book->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label" style="font-size:0.875rem;font-weight:600;">
                                Hiển thị sách
                            </label>
                            <div class="form-text">Sách sẽ xuất hiện trên trang người dùng.</div>
                        </div>
                    </div>
                    <div class="p-3" style="background:#F8FAFC;border:1px solid var(--border);border-radius:8px;">
                        <div class="form-check mb-0">
                            <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" value="1"
                                   {{ old('is_featured', $book->is_featured) ? 'checked' : '' }}>
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
        <div class="card-body py-3 d-flex gap-2 align-items-center justify-content-between">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i> Cập nhật sách
                </button>
                <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary px-4">Hủy</a>
            </div>
            <div style="font-size:0.78rem;color:var(--text-muted);">
                <i class="bi bi-clock me-1"></i> Cập nhật: {{ $book->updated_at->format('d/m/Y H:i') }}
            </div>
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
                const img = document.getElementById('preview-img');
                if (img) {
                    img.src = e.target.result;
                } else {
                    const wrap = document.getElementById('cover-preview-wrap');
                    if (wrap) {
                        wrap.style.display = 'block';
                        const newImg = document.createElement('img');
                        newImg.id = 'preview-img';
                        newImg.src = e.target.result;
                        newImg.style.cssText = 'max-width:100%;max-height:200px;border-radius:6px;border:1px solid var(--border);display:block;';
                        wrap.appendChild(newImg);
                    }
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
