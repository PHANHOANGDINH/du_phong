@extends('layouts.app')
@section('title', $author->name)

@section('content')
<div class="container pb-4">
    <a href="{{ route('authors.index') }}" class="btn btn-link px-0 mb-2"><i class="bi bi-arrow-left"></i> Danh sách tác giả</a>

    <h3 class="fw-bold mb-1">{{ $author->name }}</h3>
    @if ($author->biography)
        <p class="mb-2" style="white-space: pre-line">{{ $author->biography }}</p>
    @endif
    <p class="text-muted">{{ $books->total() }} đầu sách</p>

    @include('books.partials.book-list')
</div>
@endsection
