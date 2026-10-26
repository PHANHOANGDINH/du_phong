@extends('layouts.app')
@section('title', $publisher->name)

@section('content')
<div>
    <a href="{{ route('publishers.index') }}" class="small text-decoration-none d-inline-block mb-2"><i class="bi bi-arrow-left"></i> Danh sách nhà xuất bản</a>

    <h1 class="page-title mb-1">{{ $publisher->name }}</h1>
    @if ($publisher->address)
        <p class="mb-2"><i class="bi bi-geo-alt"></i> {{ $publisher->address }}</p>
    @endif
    <p class="text-muted">{{ $books->total() }} đầu sách</p>

    @include('books.partials.book-list')
</div>
@endsection
