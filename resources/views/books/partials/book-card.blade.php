{{--
Thẻ sách trong lưới: nhận $book.
$actions = true: hiện nút Sửa / Xóa khi rê chuột. Nút Xóa gửi form "delete-book-{id}" do trang cha đặt
ngoài form lọc (form không được lồng nhau), xem partial delete-form.
--}}
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
            <button type="submit" form="delete-book-{{ $book->id }}" class="btn btn-light btn-sm text-danger" title="Xóa"><i class="bi bi-trash"></i></button>
        </div>
    @endif
</div>
