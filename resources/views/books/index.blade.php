@extends('layouts.app')

@section('title', 'Danh Sách Sách - BookHaven')

@push('styles')
<style>
    /* ===== PAGE HEADER ===== */
    .page-header {
        background: var(--card);
        border-bottom: 1px solid var(--border);
        padding: 20px 0;
    }
    .page-header h1 {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 2px;
    }
    .page-header .breadcrumb { margin-bottom: 0; }

    /* ===== SIDEBAR ===== */
    .sidebar-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 16px;
    }
    .sidebar-title {
        padding: 12px 16px;
        border-bottom: 1px solid var(--border);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--text-muted);
        background: #F8FAFC;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sidebar-body { padding: 8px; }
    .sidebar-body.padded { padding: 16px; }

    .cat-nav-item {
        display: flex;
        align-items: center;
        padding: 8px 10px;
        border-radius: 6px;
        color: var(--text);
        font-size: 0.875rem;
        transition: background 0.15s, color 0.15s;
        text-decoration: none;
        gap: 8px;
    }
    .cat-nav-item:hover {
        background: #EFF6FF;
        color: var(--primary);
    }
    .cat-nav-item.active {
        background: #EFF6FF;
        color: var(--primary);
        font-weight: 600;
        border-left: 3px solid var(--primary);
        padding-left: 7px;
    }
    .cat-nav-item .cat-icon { color: var(--text-muted); font-size: 0.8rem; }
    .cat-nav-item.active .cat-icon { color: var(--primary); }

    /* Price range quick options */
    .price-quick-btn {
        display: block;
        padding: 7px 10px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 0.8rem;
        color: var(--text);
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s, color 0.15s;
        text-align: center;
        background: transparent;
        width: 100%;
    }
    .price-quick-btn:hover,
    .price-quick-btn.active {
        border-color: var(--primary);
        color: var(--primary);
        background: #EFF6FF;
    }

    /* ===== SORT BAR ===== */
    .sort-bar {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .sort-bar .result-text {
        font-size: 0.875rem;
        color: var(--text-muted);
    }
    .sort-bar .result-text strong { color: var(--text); }
    .sort-controls { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .sort-label { font-size: 0.8rem; color: var(--text-muted); white-space: nowrap; }
    .sort-select {
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 6px 10px;
        font-size: 0.825rem;
        color: var(--text);
        background: #fff;
        cursor: pointer;
    }
    .sort-select:focus { outline: none; border-color: var(--primary); }

    /* Active filters */
    .active-filters { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        background: #EFF6FF;
        color: var(--primary);
        border: 1px solid #BFDBFE;
        border-radius: 20px;
        font-size: 0.775rem;
        font-weight: 500;
    }
    .filter-tag a { color: inherit; display: flex; align-items: center; }
    .filter-tag a:hover { color: var(--danger); }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 64px 24px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 8px;
    }
    .empty-state .empty-icon {
        width: 72px;
        height: 72px;
        background: #F1F5F9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 1.75rem;
        color: var(--text-muted);
    }
    .empty-state h5 { color: var(--text); font-weight: 600; margin-bottom: 8px; }
    .empty-state p { color: var(--text-muted); font-size: 0.875rem; margin-bottom: 20px; }

    @media (max-width: 767.98px) {
        .sort-bar { flex-direction: column; align-items: flex-start; gap: 8px; }
    }
</style>
@endpush

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
                @if(request('category'))
                    <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Kho sách</a></li>
                    <li class="breadcrumb-item active">{{ $categories->where('slug', request('category'))->first()?->name ?? 'Danh mục' }}</li>
                @elseif(request('search'))
                    <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Kho sách</a></li>
                    <li class="breadcrumb-item active">Tìm kiếm</li>
                @else
                    <li class="breadcrumb-item active">Kho sách</li>
                @endif
            </ol>
        </nav>
        <h1>
            @if(request('search'))
                Kết quả cho: "{{ request('search') }}"
            @elseif(request('category'))
                {{ $categories->where('slug', request('category'))->first()?->name ?? 'Danh mục' }}
            @else
                Tất Cả Sách
            @endif
        </h1>
    </div>
</div>

{{-- ===== MAIN ===== --}}
<div class="container" style="margin-top: 24px; margin-bottom: 48px;">
    <div class="row g-4">

        {{-- ===== SIDEBAR ===== --}}
        <div class="col-lg-3">

            {{-- Danh mục --}}
            <div class="sidebar-card">
                <div class="sidebar-title">
                    <i class="bi bi-grid-3x3-gap"></i>
                    Danh mục
                </div>
                <div class="sidebar-body">
                    <a href="{{ route('books.index', array_merge(request()->except(['category', 'page']), [])) }}"
                       class="cat-nav-item {{ !request('category') ? 'active' : '' }}">
                        <i class="bi bi-collection cat-icon"></i>
                        Tất cả sách
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('books.index', array_merge(request()->except(['category', 'page']), ['category' => $cat->slug])) }}"
                           class="cat-nav-item {{ request('category') == $cat->slug ? 'active' : '' }}">
                            <i class="bi bi-chevron-right cat-icon"></i>
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Khoảng giá --}}
            <div class="sidebar-card">
                <div class="sidebar-title">
                    <i class="bi bi-funnel"></i>
                    Khoảng giá
                </div>
                <div class="sidebar-body padded">
                    <form method="GET" action="{{ route('books.index') }}" id="filterForm">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif

                        {{-- Gợi ý nhanh --}}
                        <div class="row g-1 mb-3">
                            @php
                                $priceRanges = [
                                    ['label' => 'Dưới 100K', 'min' => '', 'max' => '100000'],
                                    ['label' => '100–200K', 'min' => '100000', 'max' => '200000'],
                                    ['label' => '200–500K', 'min' => '200000', 'max' => '500000'],
                                    ['label' => 'Trên 500K', 'min' => '500000', 'max' => ''],
                                ];
                            @endphp
                            @foreach($priceRanges as $range)
                            <div class="col-6">
                                <button type="submit" class="price-quick-btn
                                    {{ request('price_min') == $range['min'] && request('price_max') == $range['max'] ? 'active' : '' }}"
                                    onclick="document.querySelector('[name=price_min]').value='{{ $range['min'] }}';
                                             document.querySelector('[name=price_max]').value='{{ $range['max'] }}';">
                                    {{ $range['label'] }}
                                </button>
                            </div>
                            @endforeach
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Từ (đồng)</label>
                            <input type="number" class="form-control form-control-sm"
                                   name="price_min" value="{{ request('price_min') }}"
                                   placeholder="0" min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Đến (đồng)</label>
                            <input type="number" class="form-control form-control-sm"
                                   name="price_max" value="{{ request('price_max') }}"
                                   placeholder="Không giới hạn" min="0">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 mb-2">
                            <i class="bi bi-check2 me-1"></i>Áp dụng bộ lọc
                        </button>
                        @if(request()->hasAny(['category', 'price_min', 'price_max', 'sort', 'search']))
                            <a href="{{ route('books.index') }}"
                               class="btn btn-outline-secondary btn-sm w-100">
                                <i class="bi bi-x-lg me-1"></i>Xóa bộ lọc
                            </a>
                        @endif
                    </form>
                </div>
            </div>

        </div>

        {{-- ===== BOOK GRID ===== --}}
        <div class="col-lg-9">

            {{-- Sort bar --}}
            <div class="sort-bar">
                <div>
                    @if($books->total() > 0)
                        <span class="result-text">
                            Hiển thị
                            <strong>{{ $books->firstItem() }}–{{ $books->lastItem() }}</strong>
                            /
                            <strong>{{ number_format($books->total()) }}</strong>
                            cuốn sách
                        </span>
                    @else
                        <span class="result-text">Không tìm thấy sách phù hợp</span>
                    @endif

                    {{-- Active filter tags --}}
                    @if(request()->hasAny(['category', 'price_min', 'price_max', 'search']))
                        <div class="active-filters mt-2">
                            @if(request('search'))
                                <span class="filter-tag">
                                    <i class="bi bi-search"></i>
                                    {{ request('search') }}
                                    <a href="{{ route('books.index', array_merge(request()->except('search'), [])) }}">
                                        <i class="bi bi-x"></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('category'))
                                <span class="filter-tag">
                                    <i class="bi bi-tag"></i>
                                    {{ $categories->where('slug', request('category'))->first()?->name }}
                                    <a href="{{ route('books.index', array_merge(request()->except('category'), [])) }}">
                                        <i class="bi bi-x"></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('price_min') || request('price_max'))
                                <span class="filter-tag">
                                    <i class="bi bi-currency-dollar"></i>
                                    {{ request('price_min') ? number_format(request('price_min'), 0, ',', '.') . 'đ' : '0' }}
                                    –
                                    {{ request('price_max') ? number_format(request('price_max'), 0, ',', '.') . 'đ' : 'Không giới hạn' }}
                                    <a href="{{ route('books.index', array_merge(request()->except(['price_min', 'price_max']), [])) }}">
                                        <i class="bi bi-x"></i>
                                    </a>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="sort-controls">
                    <span class="sort-label">Sắp xếp:</span>
                    <form method="GET" action="{{ route('books.index') }}">
                        @foreach(request()->except('sort') as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <select class="sort-select" name="sort" onchange="this.form.submit()">
                            <option value="newest"      {{ request('sort', 'newest') === 'newest'      ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price_asc"   {{ request('sort') === 'price_asc'             ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="price_desc"  {{ request('sort') === 'price_desc'            ? 'selected' : '' }}>Giá giảm dần</option>
                            <option value="bestselling" {{ request('sort') === 'bestselling'           ? 'selected' : '' }}>Bán chạy nhất</option>
                        </select>
                    </form>
                </div>
            </div>

            {{-- Lưới sách --}}
            @if($books->count())
                <div class="row row-cols-2 row-cols-md-3 g-3">
                    @foreach($books as $book)
                    <div class="col">
                        <x-book-card :book="$book" />
                    </div>
                    @endforeach
                </div>

                {{-- Phân trang --}}
                @if($books->hasPages())
                <div class="mt-5 d-flex flex-column align-items-center gap-2">
                    {{ $books->withQueryString()->links() }}
                    <p class="small" style="color:var(--text-muted);">
                        Trang {{ $books->currentPage() }} / {{ $books->lastPage() }}
                    </p>
                </div>
                @endif

            @else
                {{-- Empty state --}}
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5>Không tìm thấy sách phù hợp</h5>
                    <p>Thử thay đổi từ khóa tìm kiếm hoặc điều chỉnh bộ lọc để tìm được sách bạn cần.</p>
                    <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-arrow-left me-1"></i>Xem tất cả sách
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection
