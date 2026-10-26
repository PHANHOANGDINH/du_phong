<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookFilterRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BookFilterRequest $request): View
    {
        $filters = $request->filters();

        $books = Book::with(['publisher', 'authors', 'categories'])
            ->filter($filters)
            ->paginate(12) // lưới 4 cột x 3 hàng
            ->withQueryString();

        return view('books.index', $this->formOptions() + compact('books', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('books.create', $this->formOptions() + ['book' => new Book]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['author_ids', 'category_ids', 'cover_image']);
        $newCover = $this->storeCover($request->file('cover_image'));

        if ($newCover) {
            $data['cover_image_url'] = $newCover;
        }

        try {
            $book = DB::transaction(function () use ($data, $request) {
                $book = Book::create($data);
                $book->authors()->sync($request->validated('author_ids'));
                $book->categories()->sync($request->validated('category_ids'));

                return $book;
            });
        } catch (Throwable $e) {
            $this->discardCover($newCover);
            throw $e;
        }

        return redirect()->route('books.show', $book)
            ->with('success', "Đã thêm sách \"{$book->title}\".");
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): View
    {
        $book->load(['publisher', 'authors', 'categories']);

        // Sách liên quan: cùng ít nhất một thể loại
        $related = Book::with('authors')
            ->whereKeyNot($book->id)
            ->whereHas('categories', fn ($q) => $q->whereKey($book->categories->modelKeys()))
            ->latest('id')
            ->limit(10)
            ->get();

        return view('books.show', compact('book', 'related'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book): View
    {
        $book->load(['authors', 'categories']);

        return view('books.edit', $this->formOptions() + compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $data = $request->safe()->except(['author_ids', 'category_ids', 'cover_image']);
        $oldBook = clone $book;
        $newCover = $this->storeCover($request->file('cover_image'));

        if ($newCover) {
            $data['cover_image_url'] = $newCover;
        }

        try {
            DB::transaction(function () use ($book, $data, $request) {
                $book->update($data);
                $book->authors()->sync($request->validated('author_ids'));
                $book->categories()->sync($request->validated('category_ids'));
            });
        } catch (Throwable $e) {
            $this->discardCover($newCover);
            throw $e;
        }

        // Chỉ xóa ảnh cũ khi đã lưu thành công ảnh mới
        if ($newCover) {
            $oldBook->deleteCoverFile();
        }

        return redirect()->route('books.show', $book)
            ->with('success', "Đã cập nhật sách \"{$book->title}\".");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): RedirectResponse
    {
        // Xóa mềm: chỉ ghi deleted_at, giữ nguyên bản ghi, liên kết tác giả/thể loại và ảnh bìa để còn khôi phục
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', "Đã chuyển sách \"{$book->title}\" vào thùng rác.");
    }

    /**
     * Danh sách dùng cho các ô chọn trong form sách.
     *
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'publishers' => Publisher::orderBy('name')->get(['id', 'name']),
            'authors' => Author::orderBy('name')->get(['id', 'name']),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
        ];
    }

    private function storeCover(?UploadedFile $file): ?string
    {
        return $file?->store(Book::COVER_DIR, 'public') ?: null;
    }

    private function discardCover(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
