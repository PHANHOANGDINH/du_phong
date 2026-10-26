{{-- Nhà xuất bản (chỉ xem): danh sách đơn giản, bấm vào để xem sách của NXB --}}
@extends('layouts.app')
@section('title', 'Nhà xuất bản')

@section('content')
<h1 class="page-title mb-3">Nhà xuất bản</h1>

<div class="shop-box overflow-hidden simple-list">
    @forelse ($publishers as $publisher)
        <a href="{{ route('publishers.show', $publisher) }}" class="item">
            <span class="rounded bg-light border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px">
                <i class="bi bi-building text-secondary"></i>
            </span>
            <span class="flex-grow-1 min-w-0">
                <span class="d-block fw-semibold">{{ $publisher->name }}</span>
                @if ($publisher->address)
                    <span class="d-block meta text-truncate"><i class="bi bi-geo-alt"></i> {{ $publisher->address }}</span>
                @endif
            </span>
            <span class="count-pill">{{ $publisher->books_count }} sách</span>
            <i class="bi bi-chevron-right text-muted"></i>
        </a>
    @empty
        <div class="text-center text-muted py-5">Chưa có nhà xuất bản nào.</div>
    @endforelse
</div>

<div class="mt-4 d-flex justify-content-center">{{ $publishers->links() }}</div>
@endsection
