{{--
=============================================================================
CHI TIẾT ĐẦU SÁCH
=============================================================================
--}}
@extends('layouts.app')
@section('title', $book->title)

@section('content')
<div class="container pb-4">
    <a href="{{ route('books.index') }}" class="btn btn-link px-0 mb-2"><i class="bi bi-arrow-left"></i> Danh sách sách</a>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-3 text-center">
                    @if ($book->cover_url)
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="img-fluid rounded shadow-sm">
                    @else
                        <div class="bg-secondary-subtle rounded d-flex align-items-center justify-content-center text-secondary" style="height: 260px">
                            <i class="bi bi-image fs-1"></i>
                        </div>
                    @endif
                </div>

                <div class="col-md-9">
                    <h3 class="fw-bold">{{ $book->title }}</h3>
                    <p class="text-muted mb-2">
                        @forelse ($book->authors as $author)
                            <a href="{{ route('authors.show', $author) }}" class="link-secondary">{{ $author->name }}</a>@if (! $loop->last), @endif
                        @empty
                            Chưa rõ tác giả
                        @endforelse
                    </p>
                    <div class="mb-3">
                        @foreach ($book->categories as $category)
                            <a href="{{ route('categories.show', $category) }}" class="badge bg-primary-subtle text-primary-emphasis text-decoration-none">{{ $category->name }}</a>
                        @endforeach
                    </div>

                    <div class="fs-4 fw-bold text-danger mb-3">{{ number_format($book->price, 0, ',', '.') }} ₫</div>

                    <table class="table table-sm w-auto">
                        <tr><th class="pe-4">Số lượng tồn</th>
                            <td>@include('books.partials.stock-badge')</td></tr>
                        <tr><th class="pe-4">ISBN</th><td>{{ $book->isbn ?? '—' }}</td></tr>
                        <tr><th class="pe-4">Nhà xuất bản</th><td>
                            @if ($book->publisher)
                                <a href="{{ route('publishers.show', $book->publisher) }}">{{ $book->publisher->name }}</a>
                            @else
                                —
                            @endif
                        </td></tr>
                        <tr><th class="pe-4">Năm xuất bản</th><td>{{ $book->published_year ?? '—' }}</td></tr>
                        <tr><th class="pe-4">Số trang</th><td>{{ $book->page_count ?? '—' }}</td></tr>
                        <tr><th class="pe-4">Kích thước</th><td>{{ $book->dimensions ?? '—' }}</td></tr>
                    </table>

                    <div class="d-flex gap-2">
                        <a href="{{ route('books.edit', $book) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                        <form action="{{ route('books.destroy', $book) }}" method="POST"
                              onsubmit="return confirm('Xóa sách này khỏi danh sách?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger"><i class="bi bi-trash"></i> Xóa</button>
                        </form>
                    </div>
                </div>
            </div>

            @if ($book->description)
                <hr>
                <h5>Mô tả</h5>
                <div style="white-space: pre-line">{{ $book->description }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
