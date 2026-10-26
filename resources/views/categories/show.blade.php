@extends('layouts.app')
@section('title', $category->name)

@section('content')
<div>
    <a href="{{ route('categories.index') }}" class="small text-decoration-none d-inline-block mb-2"><i class="bi bi-arrow-left"></i> Danh sách thể loại</a>

    <h1 class="page-title mb-1">{{ $category->name }}</h1>
    <p class="text-muted">{{ $books->total() }} đầu sách</p>

    @include('books.partials.book-list')
</div>
@endsection
