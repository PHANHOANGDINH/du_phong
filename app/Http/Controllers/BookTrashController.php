<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Thùng rác: các sách đã xóa mềm — xem, khôi phục, xóa vĩnh viễn.
 */
class BookTrashController extends Controller
{
    public function index(): View
    {
        $books = Book::onlyTrashed()
            ->with(['publisher', 'authors', 'categories'])
            ->orderByDesc('deleted_at')
            ->paginate(10);

        return view('books.trash', compact('books'));
    }

    public function restore(int $id): RedirectResponse
    {
        $book = Book::onlyTrashed()->findOrFail($id);
        $book->restore();

        return redirect()->route('books.trash')
            ->with('success', "Đã khôi phục sách \"{$book->title}\".");
    }

    public function forceDelete(int $id): RedirectResponse
    {
        // Chỉ cho xóa vĩnh viễn sách đã nằm trong thùng rác
        $book = Book::onlyTrashed()->findOrFail($id);

        // Liên kết author_book, book_category tự xóa theo ON DELETE CASCADE
        $book->forceDelete();
        $book->deleteCoverFile();

        return redirect()->route('books.trash')
            ->with('success', "Đã xóa vĩnh viễn sách \"{$book->title}\".");
    }
}
