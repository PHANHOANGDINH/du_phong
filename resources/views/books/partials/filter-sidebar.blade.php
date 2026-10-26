{{--
Thanh lọc bên trái (kiểu cửa hàng). Mỗi lựa chọn là một link đổi đúng tham số đó, giữ nguyên các bộ lọc khác.
Nhận $filters, $categories, $authors, $publishers
--}}
@php
    // URL danh sách sau khi đổi một số tham số (null = bỏ tham số đó); tự về trang 1
    $filterUrl = fn (array $changes) => route('books.index', array_filter(
        array_merge($filters, $changes),
        fn ($value) => $value !== null && $value !== '',
    ));

    $priceRanges = [
        'Tất cả' => [null, null],
        'Dưới 100k' => [null, 99999],
        '100k - 200k' => [100000, 199999],
        '200k - 300k' => [200000, 299999],
        '300k - 500k' => [300000, 499999],
        'Trên 500k' => [500000, null],
    ];
    $currentPrice = [$filters['price_min'] ?? null, $filters['price_max'] ?? null];

    $stockOptions = ['' => 'Tất cả', 'in' => 'Còn hàng', 'low' => 'Sắp hết (< '.\App\Models\Book::LOW_STOCK.')', 'out' => 'Hết hàng'];

    $visiblePublishers = 6;
@endphp

<aside class="shop-box p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="fw-bold">Bộ lọc</span>
        <a href="{{ route('books.index') }}" class="text-secondary" title="Xóa tất cả bộ lọc"><i class="bi bi-arrow-clockwise"></i></a>
    </div>

    <div class="filter-title">Khoảng giá</div>
    <ul class="filter-list filter-radio">
        @foreach ($priceRanges as $label => [$min, $max])
            <li>
                <a href="{{ $filterUrl(['price_min' => $min, 'price_max' => $max]) }}"
                   @class(['active' => $currentPrice == [$min, $max]])>{{ $label }}</a>
            </li>
        @endforeach
    </ul>

    <div class="filter-title">Thể loại</div>
    <ul class="filter-list">
        <li><a href="{{ $filterUrl(['category_id' => null]) }}" @class(['active' => empty($filters['category_id'])])>Tất cả</a></li>
        @foreach ($categories as $category)
            <li>
                <a href="{{ $filterUrl(['category_id' => $category->id]) }}"
                   @class(['active' => ($filters['category_id'] ?? null) == $category->id])>{{ $category->name }}</a>
            </li>
        @endforeach
    </ul>

    <div class="filter-title">Nhà xuất bản</div>
    <ul class="filter-list mb-0">
        <li><a href="{{ $filterUrl(['publisher_id' => null]) }}" @class(['active' => empty($filters['publisher_id'])])>Tất cả</a></li>
        @foreach ($publishers as $i => $publisher)
            @php $isActive = ($filters['publisher_id'] ?? null) == $publisher->id; @endphp
            {{-- Ẩn bớt, nhưng NXB đang chọn luôn hiện --}}
            <li @class(['collapse more-publishers' => $i >= $visiblePublishers && ! $isActive])>
                <a href="{{ $filterUrl(['publisher_id' => $publisher->id]) }}" @class(['active' => $isActive])>{{ $publisher->name }}</a>
            </li>
        @endforeach
    </ul>
    @if ($publishers->count() > $visiblePublishers)
        <a href="#" class="small text-danger text-decoration-none d-inline-block mb-3 mt-1" data-bs-toggle="collapse"
           data-bs-target=".more-publishers" onclick="toggleMoreLabel(this); return false;">
            <i class="bi bi-chevron-down"></i> <span>Xem thêm</span>
        </a>
        <script>
            function toggleMoreLabel(link) {
                const icon = link.querySelector('i');
                const text = link.querySelector('span');
                icon.classList.toggle('bi-chevron-down');
                icon.classList.toggle('bi-chevron-up');
                text.textContent = text.textContent === 'Xem thêm' ? 'Thu gọn' : 'Xem thêm';
            }
        </script>
    @else
        <div class="mb-3"></div>
    @endif

    <div class="filter-title">Tác giả</div>
    <select class="form-select form-select-sm mb-3" onchange="location.href = this.value">
        <option value="{{ $filterUrl(['author_id' => null]) }}">Tất cả tác giả</option>
        @foreach ($authors as $author)
            <option value="{{ $filterUrl(['author_id' => $author->id]) }}" @selected(($filters['author_id'] ?? null) == $author->id)>{{ $author->name }}</option>
        @endforeach
    </select>

    <div class="filter-title">Tình trạng kho</div>
    <ul class="filter-list filter-radio mb-0">
        @foreach ($stockOptions as $value => $label)
            <li>
                <a href="{{ $filterUrl(['stock' => $value ?: null]) }}"
                   @class(['active' => ($filters['stock'] ?? '') === $value])>{{ $label }}</a>
            </li>
        @endforeach
    </ul>
</aside>
