@extends('layouts.app')
@section('title', $author->name)

@section('content')
<div>
    <a href="{{ route('authors.index') }}" class="small text-decoration-none d-inline-block mb-2"><i class="bi bi-arrow-left"></i> Danh sách tác giả</a>

    <h1 class="page-title mb-1">{{ $author->name }}</h1>
    @if ($author->biography)
        <p class="mb-2" style="white-space: pre-line">{{ $author->biography }}</p>
    @endif
    <p class="text-muted">{{ $books->total() }} đầu sách</p>

    @include('books.partials.book-list')
</div>
@endsection
