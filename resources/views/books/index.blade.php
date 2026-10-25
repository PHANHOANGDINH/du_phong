{{-- Danh sách đầu sách: tìm kiếm / lọc / sắp xếp, phân trang, Xem / Sửa / Xóa (mềm) --}}
@extends('layouts.app')
@section('title', 'Danh mục Sách')

@section('content')
<div class="container pb-4">
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-primary mb-0">QUẢN LÝ ĐẦU SÁCH</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('books.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Thêm sách</a>
        </div>
    </div>

    @include('books.partials.filter-bar')

    <p class="text-muted small mb-2">Tìm thấy <strong>{{ $books->total() }}</strong> đầu sách.</p>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 64px">Ảnh</th>
                        <th>Tên sách</th>
                        <th>Tác giả</th>
                        <th>Thể loại</th>
                        <th>NXB</th>
                        <th class="text-end">Giá bán</th>
                        <th class="text-center">Tồn</th>
                        <th class="text-end" style="width: 140px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td>
                                @if ($book->cover_url)
                                    <img src="{{ $book->cover_url }}" alt="" class="rounded" style="width: 44px; height: 60px; object-fit: cover">
                                @else
                                    <div class="rounded bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center" style="width: 44px; height: 60px">
                                        <i class="bi bi-book"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('books.show', $book) }}" class="fw-semibold text-decoration-none">{{ $book->title }}</a>
                                @if ($book->isbn)
                                    <div class="small text-muted">ISBN {{ $book->isbn }}</div>
                                @endif
                            </td>
                            <td class="small">{{ $book->authors->pluck('name')->join(', ') ?: '—' }}</td>
                            <td>
                                @foreach ($book->categories as $category)
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $category->name }}</span>
                                @endforeach
                            </td>
                            <td class="small">{{ $book->publisher?->name ?? '—' }}</td>
                            <td class="text-end text-nowrap">{{ number_format($book->price, 0, ',', '.') }} ₫</td>
                            <td class="text-center">@include('books.partials.stock-badge')</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-primary" title="Xem"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline-warning" title="Sửa"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm(@js('Chuyển sách "'.$book->title.'" vào thùng rác?'))">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Xóa"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Không có sách nào phù hợp.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $books->links() }}</div>
</div>
@endsection
