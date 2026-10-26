{{--
Thanh lọc bên trái dạng Accordion (nằm trong form GET của trang danh sách). Chọn xong bấm "Lọc".
Thể loại và tác giả tick được nhiều. Nhóm đang có điều kiện lọc tự mở sẵn.
Nhận $filters, $categories, $authors, $publishers
--}}
@php
    $selectedCategories = array_map('intval', (array) ($filters['category_id'] ?? []));
    $selectedAuthors = array_map('intval', (array) ($filters['author_id'] ?? []));
    // Tác giả đang chọn đưa lên đầu danh sách
    $authors = $authors->sortBy(fn ($a) => in_array($a->id, $selectedAuthors) ? 0 : 1, SORT_REGULAR)->values();

    $currentPrice = [$filters['price_min'] ?? null, $filters['price_max'] ?? null];
    $priceIsSet = $currentPrice !== [null, null];

    // Số điều kiện đang chọn của từng nhóm (hiện trên tiêu đề, và quyết định nhóm nào mở sẵn)
    $counts = [
        'price' => (int) $priceIsSet,
        'category' => count($selectedCategories),
        'author' => count($selectedAuthors),
        'publisher' => (int) ! empty($filters['publisher_id']),
        'stock' => (int) ! empty($filters['stock']),
    ];
    // Chưa lọc gì thì mở sẵn Khoảng giá và Thể loại
    $open = array_sum($counts) > 0
        ? array_map(fn ($n) => $n > 0, $counts)
        : ['price' => true, 'category' => true, 'author' => false, 'publisher' => false, 'stock' => false];
@endphp

<aside class="shop-box overflow-hidden">
    <div class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom">
        <span class="fw-bold"><i class="bi bi-funnel"></i> Bộ lọc</span>
        <a href="{{ route('books.index') }}" class="small text-secondary text-decoration-none">Xóa lọc</a>
    </div>

    <div class="accordion accordion-flush filter-accordion">
        {{-- Khoảng giá --}}
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button type="button" @class(['accordion-button', 'collapsed' => ! $open['price']])
                        data-bs-toggle="collapse" data-bs-target="#filter-price" aria-expanded="{{ $open['price'] ? 'true' : 'false' }}">
                    Khoảng giá @if ($counts['price']) <span class="badge rounded-pill text-bg-danger ms-2">1</span> @endif
                </button>
            </h2>
            <div id="filter-price" @class(['accordion-collapse collapse', 'show' => $open['price']])>
                <div class="accordion-body">
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
            </div>
        </div>

        {{-- Thể loại (chọn nhiều) --}}
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button type="button" @class(['accordion-button', 'collapsed' => ! $open['category']])
                        data-bs-toggle="collapse" data-bs-target="#filter-category" aria-expanded="{{ $open['category'] ? 'true' : 'false' }}">
                    Thể loại @if ($counts['category']) <span class="badge rounded-pill text-bg-danger ms-2">{{ $counts['category'] }}</span> @endif
                </button>
            </h2>
            <div id="filter-category" @class(['accordion-collapse collapse', 'show' => $open['category']])>
                <div class="accordion-body">
                    @foreach ($categories as $category)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="category_id[]" value="{{ $category->id }}" id="cat-{{ $category->id }}"
                                   @checked(in_array($category->id, $selectedCategories))>
                            <label class="form-check-label" for="cat-{{ $category->id }}">{{ $category->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Tác giả (chọn nhiều) --}}
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button type="button" @class(['accordion-button', 'collapsed' => ! $open['author']])
                        data-bs-toggle="collapse" data-bs-target="#filter-author" aria-expanded="{{ $open['author'] ? 'true' : 'false' }}">
                    Tác giả @if ($counts['author']) <span class="badge rounded-pill text-bg-danger ms-2">{{ $counts['author'] }}</span> @endif
                </button>
            </h2>
            <div id="filter-author" @class(['accordion-collapse collapse', 'show' => $open['author']])>
                <div class="accordion-body">
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
            </div>
        </div>

        {{-- Nhà xuất bản --}}
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button type="button" @class(['accordion-button', 'collapsed' => ! $open['publisher']])
                        data-bs-toggle="collapse" data-bs-target="#filter-publisher" aria-expanded="{{ $open['publisher'] ? 'true' : 'false' }}">
                    Nhà xuất bản @if ($counts['publisher']) <span class="badge rounded-pill text-bg-danger ms-2">1</span> @endif
                </button>
            </h2>
            <div id="filter-publisher" @class(['accordion-collapse collapse', 'show' => $open['publisher']])>
                <div class="accordion-body">
                    <select name="publisher_id" class="form-select form-select-sm" aria-label="Nhà xuất bản">
                        <option value="">Tất cả</option>
                        @foreach ($publishers as $publisher)
                            <option value="{{ $publisher->id }}" @selected(($filters['publisher_id'] ?? null) == $publisher->id)>{{ $publisher->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Tình trạng kho --}}
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button type="button" @class(['accordion-button', 'collapsed' => ! $open['stock']])
                        data-bs-toggle="collapse" data-bs-target="#filter-stock" aria-expanded="{{ $open['stock'] ? 'true' : 'false' }}">
                    Tình trạng kho @if ($counts['stock']) <span class="badge rounded-pill text-bg-danger ms-2">1</span> @endif
                </button>
            </h2>
            <div id="filter-stock" @class(['accordion-collapse collapse', 'show' => $open['stock']])>
                <div class="accordion-body">
                    @foreach (['' => 'Tất cả', 'in' => 'Còn hàng', 'low' => 'Sắp hết', 'out' => 'Hết hàng'] as $value => $label)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="stock" value="{{ $value }}" id="stock-{{ $value ?: 'all' }}"
                                   @checked(($filters['stock'] ?? '') === $value)>
                            <label class="form-check-label" for="stock-{{ $value ?: 'all' }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="p-3 border-top">
        <button class="btn btn-shop w-100"><i class="bi bi-funnel"></i> Lọc</button>
    </div>
</aside>
