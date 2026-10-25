<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\View\View;

/**
 * Tác giả: chỉ xem.
 */
class AuthorController extends Controller
{
    public function index(): View
    {
        $authors = Author::withCount('books')->orderBy('name')->paginate(15);

        return view('authors.index', compact('authors'));
    }

    public function show(Author $author): View
    {
        $books = $author->books()
            ->with(['authors', 'publisher'])
            ->orderBy('title')
            ->paginate(10);

        return view('authors.show', compact('author', 'books'));
    }
}
