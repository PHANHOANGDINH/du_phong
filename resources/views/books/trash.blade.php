{{-- Thùng rác: sách đã xóa mềm — Khôi phục hoặc Xóa vĩnh viễn --}}
@extends('layouts.app')
@section('title', 'Thùng rác - Sách đã xóa')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-1">
    <h1 class="page-title"><i class="bi bi-trash3"></i> Thùng rác</h1>
    <a href="{{ route('books.index') }}" class="small text-decoration-none"><i class="bi bi-arrow-left"></i> Về danh mục sách</a>
</div>
<p class="small text-muted mb-3">Sách đã xóa vẫn được lưu trong CSDL. Có thể khôi phục, hoặc xóa vĩnh viễn (không thể hoàn tác).</p>

<div class="shop-box overflow-hidden simple-list">
    @forelse ($books as $book)
        <div class="item flex-wrap">
            <div class="flex-shrink-0 cover-sm" style="width: 44px">@include('books.partials.cover')</div>
            <div class="flex-grow-1 min-w-0">
                <div class="fw-semibold text-truncate">{{ $book->title }}</div>
                <div class="meta">
                    {{ $book->authors->pluck('name')->join(', ') ?: 'Chưa rõ tác giả' }}
                    · {{ number_format($book->price, 0, ',', '.') }} đ
                    · Xóa lúc {{ $book->deleted_at->format('d/m/Y H:i') }}
                </div>
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('books.restore', $book->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-outline-success"><i class="bi bi-arrow-counterclockwise"></i> Khôi phục</button>
                </form>
                <form action="{{ route('books.forceDelete', $book->id) }}" method="POST"
                      onsubmit="return confirm(@js('Xóa VĨNH VIỄN sách "'.$book->title.'"? Thao tác này không thể hoàn tác.'))">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i> Xóa vĩnh viễn</button>
                </form>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-trash3 fs-1 d-block mb-2"></i>
            Thùng rác trống.
        </div>
    @endforelse
</div>

<div class="mt-4 d-flex justify-content-center">{{ $books->links() }}</div>
@endsection
