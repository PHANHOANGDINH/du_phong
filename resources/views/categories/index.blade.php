{{-- Thể loại (chỉ xem): lưới ô, bấm vào để xem sách của thể loại --}}
@extends('layouts.app')
@section('title', 'Thể loại')

@section('content')
<h1 class="page-title mb-3">Thể loại</h1>

@if ($categories->isEmpty())
    <div class="shop-box text-center text-muted py-5">Chưa có thể loại nào.</div>
@else
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
        @foreach ($categories as $category)
            <div class="col">
                <a href="{{ route('categories.show', $category) }}" class="shop-box tile">
                    <i class="bi bi-bookmark text-price fs-5"></i>
                    <div class="fw-semibold mt-2">{{ $category->name }}</div>
                    <div class="small text-muted">{{ $category->books_count }} đầu sách</div>
                </a>
            </div>
        @endforeach
    </div>
@endif

<div class="mt-4 d-flex justify-content-center">{{ $categories->links() }}</div>
@endsection
