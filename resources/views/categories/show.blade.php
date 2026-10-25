@extends('layouts.app')
@section('title', $category->name)

@section('content')
<div class="container pb-4">
    <a href="{{ route('categories.index') }}" class="btn btn-link px-0 mb-2"><i class="bi bi-arrow-left"></i> Danh sách thể loại</a>

    <h3 class="fw-bold mb-1">{{ $category->name }}</h3>
    <p class="text-muted">{{ $books->total() }} đầu sách</p>

    @include('books.partials.book-list')
</div>
@endsection
