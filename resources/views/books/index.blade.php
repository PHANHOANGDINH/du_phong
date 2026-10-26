{{-- Danh sách đầu sách kiểu cửa hàng: lọc bên trái, lưới thẻ sách, sắp xếp / tìm kiếm, phân trang --}}
@extends('layouts.app')
@section('title', 'Danh mục Sách')

@section('content')
<div class="container pb-4">
    <div class="row g-3">
        <div class="col-lg-3">
            @include('books.partials.filter-sidebar')
        </div>

        <div class="col-lg-9">
            {{-- Thanh trên: sắp xếp, tìm kiếm, thao tác --}}
            <div class="shop-box p-2 mb-3">
                <form method="GET" action="{{ route('books.index') }}" class="d-flex flex-wrap gap-2 align-items-center">
                    {{-- Giữ nguyên các bộ lọc bên trái khi đổi sắp xếp / từ khóa --}}
                    @foreach (\Illuminate\Support\Arr::except($filters, ['q', 'sort']) as $name => $value)
                        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                    @endforeach

                    <select name="sort" class="form-select form-select-sm w-auto" onchange="this.form.submit()" aria-label="Sắp xếp">
                        @foreach (['newest' => 'Mới nhất', 'title' => 'Tên A → Z', 'price_asc' => 'Giá tăng dần', 'price_desc' => 'Giá giảm dần', 'stock_asc' => 'Tồn kho ít nhất'] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <div class="input-group input-group-sm flex-grow-1" style="max-width: 360px">
                        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Tìm theo tên sách hoặc ISBN">
                        <button class="btn btn-shop" title="Tìm"><i class="bi bi-search"></i></button>
                    </div>

                    <div class="ms-auto d-flex gap-2">
                        <a href="{{ route('books.exportExcel', request()->query()) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-file-earmark-excel"></i> Xuất Excel</a>
                        <a href="{{ route('books.create') }}" class="btn btn-sm btn-shop"><i class="bi bi-plus-lg"></i> Thêm sách</a>
                    </div>
                </form>
                @if ($errors->any())
                    <div class="text-danger small mt-2 px-1">{{ $errors->first() }}</div>
                @endif
            </div>

            <p class="text-muted small mb-2">Tìm thấy <strong>{{ $books->total() }}</strong> đầu sách.</p>

            @if ($books->isEmpty())
                <div class="shop-box text-center text-muted py-5">
                    <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                    Không có sách nào phù hợp.
                </div>
            @else
                <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
                    @foreach ($books as $book)
                        <div class="col">@include('books.partials.book-card', ['actions' => true])</div>
                    @endforeach
                </div>
            @endif

            <div class="mt-3 d-flex justify-content-center">{{ $books->links() }}</div>
        </div>
    </div>
</div>
@endsection
