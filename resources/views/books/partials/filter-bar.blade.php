{{--
Thanh tìm kiếm / lọc / sắp xếp (GET). Nhận $filters, $categories, $authors, $publishers
--}}
<form method="GET" action="{{ route('books.index') }}" class="card border-0 shadow-sm p-3 mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-lg-4 col-md-6">
            <label class="form-label small mb-1">Từ khóa</label>
            <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Tên sách hoặc ISBN">
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="form-label small mb-1">Thể loại</label>
            <select name="category_id" class="form-select">
                <option value="">Tất cả</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? null) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-6">
            <label class="form-label small mb-1">Tác giả</label>
            <select name="author_id" class="form-select">
                <option value="">Tất cả</option>
                @foreach ($authors as $author)
                    <option value="{{ $author->id }}" @selected(($filters['author_id'] ?? null) == $author->id)>{{ $author->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-6">
            <label class="form-label small mb-1">Nhà xuất bản</label>
            <select name="publisher_id" class="form-select">
                <option value="">Tất cả</option>
                @foreach ($publishers as $publisher)
                    <option value="{{ $publisher->id }}" @selected(($filters['publisher_id'] ?? null) == $publisher->id)>{{ $publisher->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-2 col-md-3 col-6">
            <label class="form-label small mb-1">Giá từ (₫)</label>
            <input type="number" name="price_min" value="{{ $filters['price_min'] ?? '' }}" min="0" step="1000" class="form-control">
        </div>
        <div class="col-lg-2 col-md-3 col-6">
            <label class="form-label small mb-1">Giá đến (₫)</label>
            <input type="number" name="price_max" value="{{ $filters['price_max'] ?? '' }}" min="0" step="1000" class="form-control">
        </div>
        <div class="col-lg-2 col-md-3 col-6">
            <label class="form-label small mb-1">Tình trạng kho</label>
            <select name="stock" class="form-select">
                <option value="">Tất cả</option>
                <option value="in" @selected(($filters['stock'] ?? null) === 'in')>Còn hàng</option>
                <option value="low" @selected(($filters['stock'] ?? null) === 'low')>Sắp hết (&lt; {{ \App\Models\Book::LOW_STOCK }})</option>
                <option value="out" @selected(($filters['stock'] ?? null) === 'out')>Hết hàng</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-3 col-6">
            <label class="form-label small mb-1">Sắp xếp</label>
            <select name="sort" class="form-select">
                @foreach (['newest' => 'Mới thêm', 'title' => 'Tên A → Z', 'price_asc' => 'Giá tăng dần', 'price_desc' => 'Giá giảm dần', 'stock_asc' => 'Tồn kho ít nhất'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-4 col-md-12 d-flex gap-2">
            <button class="btn btn-primary flex-grow-1"><i class="bi bi-search"></i> Lọc</button>
            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
        </div>
    </div>
    @if ($errors->any())
        <div class="text-danger small mt-2">{{ $errors->first() }}</div>
    @endif
</form>
