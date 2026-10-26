{{--
Thanh lọc bên trái (nằm trong form GET của trang danh sách). Chọn xong bấm "Lọc".
Thể loại và tác giả tick được nhiều. Nhận $filters, $categories, $authors, $publishers
--}}
@php
    $selectedCategories = array_map('intval', (array) ($filters['category_id'] ?? []));
    $selectedAuthors = array_map('intval', (array) ($filters['author_id'] ?? []));
    // Tác giả đang chọn đưa lên đầu danh sách
    $authors = $authors->sortBy(fn ($a) => in_array($a->id, $selectedAuthors) ? 0 : 1, SORT_REGULAR)->values();

    $currentPrice = [$filters['price_min'] ?? null, $filters['price_max'] ?? null];
    $priceIsSet = $currentPrice !== [null, null];
@endphp

<aside class="shop-box p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="fw-bold"><i class="bi bi-funnel"></i> Bộ lọc</span>
        <a href="{{ route('books.index') }}" class="small text-secondary text-decoration-none">Xóa lọc</a>
    </div>

    <div class="filter-group">
        <div class="filter-title">Khoảng giá</div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="price_range" value="all" id="price-all" @checked(! $priceIsSet)>
            <label class="form-check-label" for="price-all">Tất cả</label>
        </div>
        @foreach (\App\Http\Requests\BookFilterRequest::PRICE_RANGES as $key => [$label, $min, $max])
            <div class="form-check">
                <input class="form-check-input" type="radio" name="price_range" value="{{ $key }}" id="price-{{ $key }}"
                       @checked($priceIsSet && $currentPrice == [$min, $max])>
                <label class="form-check-label" for="price-{{ $key }}">{{ $label }}</label>
            </div>
        @endforeach
    </div>

    <div class="filter-group">
        <div class="filter-title">Thể loại <span class="text-muted fw-normal small">(chọn nhiều)</span></div>
        <div>
            @foreach ($categories as $category)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="category_id[]" value="{{ $category->id }}" id="cat-{{ $category->id }}"
                           @checked(in_array($category->id, $selectedCategories))>
                    <label class="form-check-label" for="cat-{{ $category->id }}">{{ $category->name }}</label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="filter-group">
        <div class="filter-title">Tác giả <span class="text-muted fw-normal small">(chọn nhiều)</span></div>
        <input type="search" class="form-control form-control-sm mb-2" placeholder="Tìm tác giả..." oninput="filterChecklist(this, 'author-list')" aria-label="Tìm tác giả">
        <div class="filter-scroll" id="author-list">
            @foreach ($authors as $author)
                <div class="form-check" data-name="{{ mb_strtolower($author->name) }}">
                    <input class="form-check-input" type="checkbox" name="author_id[]" value="{{ $author->id }}" id="author-{{ $author->id }}"
                           @checked(in_array($author->id, $selectedAuthors))>
                    <label class="form-check-label" for="author-{{ $author->id }}">{{ $author->name }}</label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="filter-group">
        <div class="filter-title">Nhà xuất bản</div>
        <select name="publisher_id" class="form-select form-select-sm">
            <option value="">Tất cả</option>
            @foreach ($publishers as $publisher)
                <option value="{{ $publisher->id }}" @selected(($filters['publisher_id'] ?? null) == $publisher->id)>{{ $publisher->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <div class="filter-title">Tình trạng kho</div>
        @foreach (['' => 'Tất cả', 'in' => 'Còn hàng', 'low' => 'Sắp hết (dưới '.\App\Models\Book::LOW_STOCK.')', 'out' => 'Hết hàng'] as $value => $label)
            <div class="form-check">
                <input class="form-check-input" type="radio" name="stock" value="{{ $value }}" id="stock-{{ $value ?: 'all' }}"
                       @checked(($filters['stock'] ?? '') === $value)>
                <label class="form-check-label" for="stock-{{ $value ?: 'all' }}">{{ $label }}</label>
            </div>
        @endforeach
    </div>

    <button class="btn btn-shop w-100 mt-2"><i class="bi bi-funnel"></i> Lọc</button>
</aside>
