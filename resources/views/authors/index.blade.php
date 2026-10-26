{{-- Tác giả (chỉ xem): danh sách đơn giản, bấm vào để xem sách của tác giả --}}
@extends('layouts.app')
@section('title', 'Tác giả')

@section('content')
<h1 class="page-title mb-3">Tác giả</h1>

<div class="shop-box overflow-hidden simple-list">
    @forelse ($authors as $author)
        <a href="{{ route('authors.show', $author) }}" class="item">
            <span class="rounded-circle bg-light border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px">
                <i class="bi bi-person text-secondary"></i>
            </span>
            <span class="flex-grow-1 min-w-0">
                <span class="d-block fw-semibold">{{ $author->name }}</span>
                @if ($author->biography)
                    <span class="d-block meta text-truncate">{{ $author->biography }}</span>
                @endif
            </span>
            <span class="count-pill">{{ $author->books_count }} sách</span>
            <i class="bi bi-chevron-right text-muted"></i>
        </a>
    @empty
        <div class="text-center text-muted py-5">Chưa có tác giả nào.</div>
    @endforelse
</div>

<div class="mt-4 d-flex justify-content-center">{{ $authors->links() }}</div>
@endsection
