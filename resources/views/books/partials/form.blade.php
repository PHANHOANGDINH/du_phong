{{--
Form dùng chung cho thêm mới và cập nhật sách.
Biến: $book, $publishers, $authors, $categories
--}}
@php
    $selectedAuthors = old('author_ids', $book->authors?->pluck('id')->all() ?? []);
    $selectedCategories = old('category_ids', $book->categories?->pluck('id')->all() ?? []);
@endphp

@if ($errors->any())
    <div class="alert alert-danger">Vui lòng kiểm tra lại các trường bị lỗi bên dưới.</div>
@endif

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Tên sách <span class="text-danger">*</span></label>
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
        <label class="form-label">Giá bán (₫) <span class="text-danger">*</span></label>
        <input type="number" name="price" value="{{ old('price', $book->price ?? 0) }}" min="0" step="100"
               class="form-control @error('price') is-invalid @enderror" required>
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Số lượng tồn <span class="text-danger">*</span></label>
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
        <input type="text" name="dimensions" value="{{ old('dimensions', $book->dimensions) }}" maxlength="50" placeholder="VD: 13 x 20.5 cm"
               class="form-control @error('dimensions') is-invalid @enderror">
        @error('dimensions') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Tác giả <span class="text-danger">*</span></label>
        <select name="author_ids[]" multiple size="8"
                class="form-select @error('author_ids') is-invalid @enderror @error('author_ids.*') is-invalid @enderror">
            @foreach ($authors as $author)
                <option value="{{ $author->id }}" @selected(in_array($author->id, $selectedAuthors))>{{ $author->name }}</option>
            @endforeach
        </select>
        <div class="form-text">Giữ Ctrl để chọn nhiều tác giả.</div>
        @error('author_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @error('author_ids.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Thể loại <span class="text-danger">*</span></label>
        <div class="border rounded p-2 @error('category_ids') border-danger @enderror @error('category_ids.*') border-danger @enderror">
            @foreach ($categories as $category)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                           id="cat{{ $category->id }}" @checked(in_array($category->id, $selectedCategories))>
                    <label class="form-check-label" for="cat{{ $category->id }}">{{ $category->name }}</label>
                </div>
            @endforeach
        </div>
        @error('category_ids') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        @error('category_ids.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Ảnh bìa</label>
        <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp"
               class="form-control @error('cover_image') is-invalid @enderror">
        <div class="form-text">JPG, PNG hoặc WEBP, tối đa 2MB.@if ($book->cover_url) Chọn ảnh mới sẽ thay thế ảnh hiện tại.@endif</div>
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
