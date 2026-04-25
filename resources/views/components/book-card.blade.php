@props(['book'])

<div class="book-card h-100">
    {{-- Ảnh bìa --}}
    <div class="card-img-wrap">
        <a href="{{ route('books.show', $book->slug) }}" style="display:block;">
            
            <img src="{{ $book->cover_image ?? 'https://via.placeholder.com/200x300?text=No+Image' }}"
                 class="card-img-top"
                 alt="{{ $book->title }}"
                 loading="lazy"
                 style="width:100%; height:220px; object-fit:cover; border-radius:6px;"
                 onerror="this.src='https://via.placeholder.com/200x300?text=No+Image'">

        </a>

        {{-- Badge giảm giá --}}
        @if($book->sale_price)
            @php $pct = round((1 - $book->sale_price / $book->price) * 100); @endphp
            <span class="badge-sale">-{{ $pct }}%</span>
        @endif
    </div>

    {{-- Thông tin --}}
    <div class="card-body">

        {{-- Danh mục --}}
        @if($book->category)
            <div style="margin-bottom:5px;">
                <span style="font-size:0.7rem; font-weight:600; color:var(--primary); text-transform:uppercase;">
                    {{ $book->category->name }}
                </span>
            </div>
        @endif

        {{-- Tiêu đề --}}
        <a href="{{ route('books.show', $book->slug) }}" class="book-title text-decoration-none">
            {{ $book->title }}
        </a>

        {{-- Tác giả --}}
        <div class="book-author">
            <i class="bi bi-person me-1"></i>{{ $book->author }}
        </div>

        {{-- Giá --}}
        <div class="book-price-wrap mt-auto pt-2">
            @if($book->sale_price)
                <div class="d-flex align-items-baseline gap-1 flex-wrap">
                    <span class="price-sale">{{ number_format($book->sale_price, 0, ',', '.') }}đ</span>
                    <span class="price-original">{{ number_format($book->price, 0, ',', '.') }}đ</span>
                </div>
            @else
                <span class="price-normal">{{ number_format($book->price, 0, ',', '.') }}đ</span>
            @endif

            {{-- Thêm vào giỏ --}}
            <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                        class="btn btn-primary btn-sm w-100"
                        style="font-size:0.8rem; padding:6px 10px; font-weight:600; border-radius:6px;">
                    <i class="bi bi-cart-plus me-1"></i>Thêm vào giỏ
                </button>
            </form>
        </div>
    </div>
</div>