{{-- Bảng sách rút gọn (chỉ xem) dùng ở trang chi tiết thể loại / tác giả / NXB. Nhận $books (paginator) --}}
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tên sách</th>
                    <th>Tác giả</th>
                    <th>NXB</th>
                    <th class="text-end">Giá bán</th>
                    <th class="text-center">Tồn</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td><a href="{{ route('books.show', $book) }}" class="fw-semibold text-decoration-none">{{ $book->title }}</a></td>
                        <td class="small">{{ $book->authors->pluck('name')->join(', ') ?: '—' }}</td>
                        <td class="small">{{ $book->publisher?->name ?? '—' }}</td>
                        <td class="text-end text-nowrap">{{ number_format($book->price, 0, ',', '.') }} ₫</td>
                        <td class="text-center">@include('books.partials.stock-badge')</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Chưa có sách nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $books->links() }}</div>
