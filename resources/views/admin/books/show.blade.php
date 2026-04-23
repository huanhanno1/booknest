@extends('layouts.admin')

@section('title', 'Chi tiet sach')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Chi tiet sach</h4>
        <small class="text-muted">
            <a href="{{ route('admin.books.index') }}" class="text-muted text-decoration-none">Quan ly sach</a>
            / {{ Str::limit($book->title, 40) }}
        </small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil me-1"></i> Chinh sua
        </a>
        <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lai
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0" style="border:1px solid var(--border)!important;border-radius:8px;">
            <div class="card-body p-4 text-center">
                @if($book->cover_image)
                    <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}"
                         style="max-width:100%;max-height:350px;border-radius:6px;border:1px solid var(--border);">
                @else
                    <div style="width:100%;height:300px;background:#F1F5F9;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-book text-muted" style="font-size:4rem;"></i>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0" style="border:1px solid var(--border)!important;border-radius:8px;">
            <div class="card-header bg-white py-3 px-4" style="border-color:var(--border)!important;">
                <h5 class="mb-0 fw-semibold">{{ $book->title }}</h5>
            </div>
            <div class="card-body p-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width:160px;">Tac gia</td>
                        <td class="fw-semibold">{{ $book->author }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Danh muc</td>
                        <td>{{ $book->category->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">NXB</td>
                        <td>{{ $book->publisher ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nam xuat ban</td>
                        <td>{{ $book->publish_year ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">So trang</td>
                        <td>{{ $book->pages ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">ISBN</td>
                        <td><code>{{ $book->isbn ?? '—' }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Gia goc</td>
                        <td>{{ number_format($book->price, 0, ',', '.') }} d</td>
                    </tr>
                    @if($book->sale_price)
                    <tr>
                        <td class="text-muted">Gia khuyen mai</td>
                        <td class="text-danger fw-semibold">{{ number_format($book->sale_price, 0, ',', '.') }} d</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted">Ton kho</td>
                        <td>{{ number_format($book->stock) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Da ban</td>
                        <td>{{ number_format($book->sold_count ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Luot xem</td>
                        <td>{{ number_format($book->view_count ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Trang thai</td>
                        <td>
                            @if($book->is_active)
                                <span class="badge bg-success bg-opacity-15 text-success border border-success">Hien thi</span>
                            @else
                                <span class="badge bg-danger bg-opacity-15 text-danger border border-danger">An</span>
                            @endif
                            @if($book->is_featured)
                                <span class="badge bg-warning bg-opacity-15 text-warning border border-warning ms-1">Noi bat</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if($book->description)
        <div class="card border-0 mt-3" style="border:1px solid var(--border)!important;border-radius:8px;">
            <div class="card-header bg-white py-3 px-4" style="border-color:var(--border)!important;">
                <h6 class="mb-0 fw-semibold">Mo ta</h6>
            </div>
            <div class="card-body p-4">
                <p class="mb-0" style="line-height:1.8;">{{ $book->description }}</p>
            </div>
        </div>
        @endif

        @if($book->reviews->count())
        <div class="card border-0 mt-3" style="border:1px solid var(--border)!important;border-radius:8px;">
            <div class="card-header bg-white py-3 px-4" style="border-color:var(--border)!important;">
                <h6 class="mb-0 fw-semibold">Danh gia ({{ $book->reviews->count() }})</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($book->reviews as $review)
                    <li class="list-group-item px-4 py-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $review->user->name ?? '—' }}</strong>
                            <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                        </div>
                        <div class="mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"
                                   style="font-size:0.85rem;color:{{ $i <= $review->rating ? '#F59E0B' : '#CBD5E1' }};"></i>
                            @endfor
                        </div>
                        <p class="mb-0 mt-1 text-muted" style="font-size:0.9rem;">{{ $review->comment }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
