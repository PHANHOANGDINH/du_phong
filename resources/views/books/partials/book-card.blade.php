{{-- Thẻ sách trong lưới: nhận $book; $actions = true để hiện nút Sửa / Xóa khi rê chuột --}}
<div class="book-card">
    <a href="{{ route('books.show', $book) }}" class="text-decoration-none">
        @include('books.partials.cover')
        <div class="book-title" title="{{ $book->title }}">{{ $book->title }}</div>
    </a>
    <div class="mt-auto d-flex flex-wrap justify-content-between align-items-center gap-1">
        <span class="book-price">{{ number_format($book->price, 0, ',', '.') }} đ</span>
        @include('books.partials.stock-badge')
    </div>

    @if ($actions ?? false)
        <div class="card-actions">
            <a href="{{ route('books.edit', $book) }}" class="btn btn-light btn-sm" title="Sửa"><i class="bi bi-pencil"></i></a>
            <form action="{{ route('books.destroy', $book) }}" method="POST"
                  onsubmit="return confirm(@js('Chuyển sách "'.$book->title.'" vào thùng rác?'))">
                @csrf
                @method('DELETE')
                <button class="btn btn-light btn-sm text-danger" title="Xóa"><i class="bi bi-trash"></i></button>
            </form>
        </div>
    @endif
</div>
