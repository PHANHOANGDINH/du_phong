{{-- Thùng rác: sách đã xóa mềm — Khôi phục hoặc Xóa vĩnh viễn --}}
@extends('layouts.app')
@section('title', 'Thùng rác - Sách đã xóa')

@section('content')
<div class="container pb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-danger mb-0"><i class="bi bi-trash3"></i> THÙNG RÁC</h3>
        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Danh sách sách</a>
    </div>

    <p class="text-muted small">Sách đã xóa vẫn được lưu trong CSDL. Có thể khôi phục lại, hoặc xóa vĩnh viễn (không thể hoàn tác).</p>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tên sách</th>
                        <th>Tác giả</th>
                        <th class="text-end">Giá bán</th>
                        <th class="text-center">Tồn</th>
                        <th>Ngày xóa</th>
                        <th class="text-end" style="width: 220px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td>
                                <span class="fw-semibold">{{ $book->title }}</span>
                                @if ($book->isbn)
                                    <div class="small text-muted">ISBN {{ $book->isbn }}</div>
                                @endif
                            </td>
                            <td class="small">{{ $book->authors->pluck('name')->join(', ') ?: '—' }}</td>
                            <td class="text-end text-nowrap">{{ number_format($book->price, 0, ',', '.') }} ₫</td>
                            <td class="text-center">{{ $book->stock_quantity }}</td>
                            <td class="small text-nowrap">{{ $book->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end text-nowrap">
                                <form action="{{ route('books.restore', $book->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success"><i class="bi bi-arrow-counterclockwise"></i> Khôi phục</button>
                                </form>
                                <form action="{{ route('books.forceDelete', $book->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm(@js('Xóa VĨNH VIỄN sách "'.$book->title.'"? Thao tác này không thể hoàn tác.'))">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i> Xóa vĩnh viễn</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Thùng rác trống.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $books->links() }}</div>
</div>
@endsection
