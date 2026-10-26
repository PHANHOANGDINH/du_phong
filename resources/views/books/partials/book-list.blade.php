{{-- Lưới sách (chỉ xem) dùng ở trang chi tiết thể loại / tác giả / NXB. Nhận $books (paginator) --}}
@if ($books->isEmpty())
    <div class="shop-box text-center text-muted py-5">Chưa có sách nào.</div>
@else
    <div class="row row-cols-2 row-cols-md-4 row-cols-xl-5 g-3">
        @foreach ($books as $book)
            <div class="col">@include('books.partials.book-card')</div>
        @endforeach
    </div>
@endif

<div class="mt-3 d-flex justify-content-center">{{ $books->links() }}</div>
