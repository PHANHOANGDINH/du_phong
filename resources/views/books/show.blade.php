{{-- Chi tiết đầu sách kiểu cửa hàng: ảnh bìa, thông tin chính, thông tin chi tiết, mô tả, sách liên quan --}}
@extends('layouts.app')
@section('title', $book->title)

@section('content')
<div>
    <nav aria-label="breadcrumb" class="small mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('books.index') }}" class="text-decoration-none">Sách</a></li>
            @if ($category = $book->categories->first())
                <li class="breadcrumb-item"><a href="{{ route('books.index', ['category_id' => $category->id]) }}" class="text-decoration-none">{{ $category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active text-truncate" style="max-width: 50ch">{{ $book->title }}</li>
        </ol>
    </nav>

    <div class="row g-3">
        {{-- Ảnh bìa --}}
        <div class="col-md-5">
            <div class="shop-box p-4 h-100 d-flex align-items-start justify-content-center">
                <div style="width: 100%; max-width: 300px">@include('books.partials.cover')</div>
            </div>
        </div>

        <div class="col-md-7 d-flex flex-column gap-3">
            {{-- Thông tin chính + thao tác --}}
            <div class="shop-box p-4">
                <h1 class="h4 fw-bold mb-2">{{ $book->title }}</h1>
                <div class="small text-muted mb-3">
                    Tác giả:
                    @forelse ($book->authors as $author)
                        <a href="{{ route('authors.show', $author) }}" class="link-secondary">{{ $author->name }}</a>@if (! $loop->last), @endif
                    @empty
                        Chưa rõ
                    @endforelse
                </div>

                <div class="fs-3 fw-bold text-price mb-2">{{ number_format($book->price, 0, ',', '.') }} đ</div>

                <div class="mb-4 small">
                    @include('books.partials.stock-badge')
                    @if ($book->stock_quantity > 0)
                        <span class="text-muted ms-1">Còn {{ $book->stock_quantity }} sản phẩm</span>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('books.edit', $book) }}" class="btn btn-shop px-4 flex-grow-1"><i class="bi bi-pencil"></i> Sửa thông tin</a>
                    <form action="{{ route('books.destroy', $book) }}" method="POST" class="flex-grow-1"
                          onsubmit="return confirm('Chuyển sách này vào thùng rác?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i> Xóa</button>
                    </form>
                </div>
            </div>

            {{-- Thông tin chi tiết --}}
            <div class="shop-box p-4 flex-grow-1">
                <h2 class="h6 fw-bold mb-3">Thông tin chi tiết</h2>
                <table class="table table-sm table-borderless small mb-0">
                    <tbody>
                        <tr><td class="text-muted" style="width: 40%">Nhà xuất bản</td>
                            <td>
                                @if ($book->publisher)
                                    <a href="{{ route('publishers.show', $book->publisher) }}" class="text-decoration-none">{{ $book->publisher->name }}</a>
                                @else
                                    —
                                @endif
                            </td></tr>
                        <tr><td class="text-muted">Năm xuất bản</td><td>{{ $book->published_year ?? '—' }}</td></tr>
                        <tr><td class="text-muted">Số trang</td><td>{{ $book->page_count ?? '—' }}</td></tr>
                        <tr><td class="text-muted">Kích thước</td><td>{{ $book->dimensions ?? '—' }}</td></tr>
                        <tr><td class="text-muted">ISBN</td><td>{{ $book->isbn ?? '—' }}</td></tr>
                        <tr><td class="text-muted">Thể loại</td>
                            <td>
                                @forelse ($book->categories as $category)
                                    <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">{{ $category->name }}</a>@if (! $loop->last), @endif
                                @empty
                                    —
                                @endforelse
                            </td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Mô tả --}}
    <div class="shop-box mt-3">
        <ul class="nav nav-underline px-4 pt-2 border-bottom">
            <li class="nav-item"><span class="nav-link active">Mô tả sản phẩm</span></li>
        </ul>
        <div class="p-4 small" style="white-space: pre-line">{{ $book->description ?: 'Chưa có mô tả sản phẩm.' }}</div>
    </div>

    {{-- Sách liên quan --}}
    @if ($related->isNotEmpty())
        <h2 class="h5 fw-bold text-center my-4">Sản phẩm liên quan</h2>
        {{-- Cột cố định theo màn hình: ít sách thì thẻ vẫn giữ kích thước, không bị kéo dãn --}}
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-6 g-3">
            @foreach ($related as $item)
                <div class="col">@include('books.partials.book-card', ['book' => $item])</div>
            @endforeach
        </div>
    @endif
</div>
@endsection
