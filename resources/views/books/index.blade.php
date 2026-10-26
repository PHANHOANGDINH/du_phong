{{-- Danh sách đầu sách: lọc bên trái, lưới thẻ sách, tìm kiếm / sắp xếp, phân trang --}}
@extends('layouts.app')
@section('title', 'Danh mục Sách')

@php
    // Các điều kiện đang lọc, mỗi mục kèm link bỏ riêng mục đó
    $without = function (string $key, $value = null) use ($filters) {
        $next = $filters;
        if ($value === null) {
            unset($next[$key]);
        } else {
            $next[$key] = array_values(array_diff((array) $next[$key], [$value]));
        }

        return route('books.index', $next);
    };
    $chips = [];
    foreach ((array) ($filters['category_id'] ?? []) as $id) {
        $chips[] = [$categories->firstWhere('id', $id)?->name, $without('category_id', $id)];
    }
    foreach ((array) ($filters['author_id'] ?? []) as $id) {
        $chips[] = [$authors->firstWhere('id', $id)?->name, $without('author_id', $id)];
    }
    if (! empty($filters['publisher_id'])) {
        $chips[] = [$publishers->firstWhere('id', $filters['publisher_id'])?->name, $without('publisher_id')];
    }
    if (isset($filters['price_min']) || isset($filters['price_max'])) {
        $price = number_format($filters['price_min'] ?? 0, 0, ',', '.').'đ - '.(isset($filters['price_max']) ? number_format($filters['price_max'] + 1, 0, ',', '.').'đ' : '...');
        $chips[] = ['Giá: '.$price, route('books.index', \Illuminate\Support\Arr::except($filters, ['price_min', 'price_max']))];
    }
    if (! empty($filters['stock'])) {
        $chips[] = [['in' => 'Còn hàng', 'low' => 'Sắp hết', 'out' => 'Hết hàng'][$filters['stock']], $without('stock')];
    }
@endphp

@section('content')
<form method="GET" action="{{ route('books.index') }}">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="page-title">Danh mục sách</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('books.exportExcel', request()->query()) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-file-earmark-excel"></i> Xuất Excel</a>
            <a href="{{ route('books.create') }}" class="btn btn-sm btn-shop"><i class="bi bi-plus-lg"></i> Thêm sách</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-3">
            @include('books.partials.filter-sidebar')
        </div>

        <div class="col-lg-9">
            {{-- Tìm kiếm + sắp xếp --}}
            <div class="shop-box p-2 mb-3 d-flex flex-wrap flex-sm-nowrap gap-2">
                <div class="input-group input-group-sm" style="flex: 1 1 260px; width: auto">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control border-start-0" placeholder="Tìm theo tên sách hoặc ISBN">
                    <button class="btn btn-shop px-3">Tìm</button>
                </div>
                <select name="sort" class="form-select form-select-sm w-auto" onchange="this.form.submit()" aria-label="Sắp xếp">
                    @foreach (['newest' => 'Mới nhất', 'title' => 'Tên A → Z', 'price_asc' => 'Giá tăng dần', 'price_desc' => 'Giá giảm dần', 'stock_asc' => 'Tồn kho ít nhất'] as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
            @endif

            <div class="d-flex flex-wrap align-items-center gap-2 mb-3 small">
                <span class="text-muted">Tìm thấy <strong class="text-dark">{{ $books->total() }}</strong> đầu sách</span>
                @if ($chips)
                    <span class="text-muted">· Đang lọc:</span>
                    @foreach ($chips as [$label, $removeUrl])
                        <a href="{{ $removeUrl }}" class="badge rounded-pill text-bg-light border text-decoration-none fw-normal" title="Bỏ điều kiện này">
                            {{ $label }} <i class="bi bi-x"></i>
                        </a>
                    @endforeach
                @endif
            </div>

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

            <div class="mt-4 d-flex justify-content-center">{{ $books->links() }}</div>
        </div>
    </div>
</form>

{{-- Form xóa của từng thẻ, đặt ngoài form lọc --}}
@foreach ($books as $book)
    @include('books.partials.delete-form')
@endforeach
@endsection
