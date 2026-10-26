{{--
Form dùng chung cho thêm mới và cập nhật sách.
Biến: $book, $publishers, $authors, $categories
--}}
@php
    $selectedAuthors = old('author_ids', $book->authors?->pluck('id')->all() ?? []);
    $selectedCategories = old('category_ids', $book->categories?->pluck('id')->all() ?? []);

    // Accordion tác giả / thể loại: tên đã chọn hiện trên tiêu đề; mở sẵn khi chưa chọn gì hoặc đang có lỗi
    $authorNames = $authors->whereIn('id', $selectedAuthors)->pluck('name');
    $categoryNames = $categories->whereIn('id', $selectedCategories)->pluck('name');
    $authorError = $errors->has('author_ids') || $errors->has('author_ids.*');
    $categoryError = $errors->has('category_ids') || $errors->has('category_ids.*');
    $openAuthors = $authorNames->isEmpty() || $authorError;
    $openCategories = $categoryNames->isEmpty() || $categoryError;
@endphp

@if ($errors->any())
    <div class="alert alert-danger">Vui lòng kiểm tra lại các trường bị lỗi bên dưới.</div>
@endif

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Tên sách</label>
        <input type="text" name="title" value="{{ old('title', $book->title) }}"
               class="form-control @error('title') is-invalid @enderror" maxlength="255" required>
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Mã sách (ISBN)</label>
        @if ($book->exists)
            {{-- Mã sách chỉ đọc khi cập nhật; backend cũng bỏ qua trường này --}}
            <input type="text" value="{{ $book->isbn ?? '—' }}" class="form-control" readonly disabled>
        @else
            <input type="text" name="isbn" value="{{ old('isbn') }}"
                   class="form-control @error('isbn') is-invalid @enderror" maxlength="20">
            @error('isbn') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @endif
    </div>

    <div class="col-md-4">
        <label class="form-label">Giá bán</label>
        <input type="number" name="price" value="{{ old('price', (float) ($book->price ?? 0)) }}" min="0" step="100"
               class="form-control @error('price') is-invalid @enderror" required>
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Số lượng tồn</label>
        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $book->stock_quantity ?? 0) }}" min="0"
               class="form-control @error('stock_quantity') is-invalid @enderror" required>
        @error('stock_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Nhà xuất bản</label>
        <select name="publisher_id" class="form-select @error('publisher_id') is-invalid @enderror">
            <option value="">— Không chọn —</option>
            @foreach ($publishers as $publisher)
                <option value="{{ $publisher->id }}" @selected(old('publisher_id', $book->publisher_id) == $publisher->id)>{{ $publisher->name }}</option>
            @endforeach
        </select>
        @error('publisher_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Năm xuất bản</label>
        <input type="number" name="published_year" value="{{ old('published_year', $book->published_year) }}" min="1901" max="{{ now()->year + 1 }}"
               class="form-control @error('published_year') is-invalid @enderror">
        @error('published_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Số trang</label>
        <input type="number" name="page_count" value="{{ old('page_count', $book->page_count) }}" min="1"
               class="form-control @error('page_count') is-invalid @enderror">
        @error('page_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Kích thước</label>
        <input type="text" name="dimensions" value="{{ old('dimensions', $book->dimensions) }}" maxlength="50"
               class="form-control @error('dimensions') is-invalid @enderror">
        @error('dimensions') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <div class="accordion pick-accordion">
            {{-- Tác giả (chọn nhiều) --}}
            <div @class(['accordion-item', 'border-danger' => $authorError])>
                <h2 class="accordion-header">
                    <button type="button" @class(['accordion-button', 'collapsed' => ! $openAuthors])
                            data-bs-toggle="collapse" data-bs-target="#pick-authors" aria-expanded="{{ $openAuthors ? 'true' : 'false' }}">
                        <span class="pick-label">Tác giả</span>
                        <span class="badge rounded-pill text-bg-secondary" data-pick-count="authors">{{ $authorNames->count() }}</span>
                        <span class="pick-summary" data-pick-summary="authors">{{ $authorNames->join(', ') ?: 'Chưa chọn' }}</span>
                    </button>
                </h2>
                <div id="pick-authors" @class(['accordion-collapse collapse', 'show' => $openAuthors])>
                    <div class="accordion-body">
                        <input type="search" class="form-control form-control-sm mb-2" placeholder="Tìm tác giả..." oninput="filterChecklist(this, 'form-author-list')" aria-label="Tìm tác giả">
                        <div class="filter-scroll row row-cols-1 row-cols-md-3 g-0" id="form-author-list" data-pick="authors">
                            {{-- Tác giả đã chọn đưa lên đầu --}}
                            @foreach ($authors->sortBy(fn ($a) => in_array($a->id, $selectedAuthors) ? 0 : 1, SORT_REGULAR) as $author)
                                <div class="col form-check" data-name="{{ mb_strtolower($author->name) }}">
                                    <input class="form-check-input" type="checkbox" name="author_ids[]" value="{{ $author->id }}"
                                           id="author{{ $author->id }}" @checked(in_array($author->id, $selectedAuthors))>
                                    <label class="form-check-label" for="author{{ $author->id }}">{{ $author->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thể loại (chọn nhiều) --}}
            <div @class(['accordion-item', 'border-danger' => $categoryError])>
                <h2 class="accordion-header">
                    <button type="button" @class(['accordion-button', 'collapsed' => ! $openCategories])
                            data-bs-toggle="collapse" data-bs-target="#pick-categories" aria-expanded="{{ $openCategories ? 'true' : 'false' }}">
                        <span class="pick-label">Thể loại</span>
                        <span class="badge rounded-pill text-bg-secondary" data-pick-count="categories">{{ $categoryNames->count() }}</span>
                        <span class="pick-summary" data-pick-summary="categories">{{ $categoryNames->join(', ') ?: 'Chưa chọn' }}</span>
                    </button>
                </h2>
                <div id="pick-categories" @class(['accordion-collapse collapse', 'show' => $openCategories])>
                    <div class="accordion-body">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-0" data-pick="categories">
                            @foreach ($categories as $category)
                                <div class="col form-check">
                                    <input class="form-check-input" type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                           id="cat{{ $category->id }}" @checked(in_array($category->id, $selectedCategories))>
                                    <label class="form-check-label" for="cat{{ $category->id }}">{{ $category->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @error('author_ids') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        @error('author_ids.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        @error('category_ids') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        @error('category_ids.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Ảnh bìa</label>
        <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp"
               class="form-control @error('cover_image') is-invalid @enderror">
        @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        @if ($book->cover_url)
            <label class="form-label d-block">Ảnh hiện tại</label>
            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="img-thumbnail" style="max-height: 120px">
        @endif
    </div>

    <div class="col-12">
        <label class="form-label">Mô tả</label>
        <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $book->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
