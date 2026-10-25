@extends('layouts.app')
@section('title', $publisher->name)

@section('content')
<div class="container pb-4">
    <a href="{{ route('publishers.index') }}" class="btn btn-link px-0 mb-2"><i class="bi bi-arrow-left"></i> Danh sách nhà xuất bản</a>

    <h3 class="fw-bold mb-1">{{ $publisher->name }}</h3>
    @if ($publisher->address)
        <p class="mb-2"><i class="bi bi-geo-alt"></i> {{ $publisher->address }}</p>
    @endif
    <p class="text-muted">{{ $books->total() }} đầu sách</p>

    @include('books.partials.book-list')
</div>
@endsection
