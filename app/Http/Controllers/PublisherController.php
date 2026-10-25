<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\View\View;

/**
 * Nhà xuất bản: chỉ xem.
 */
class PublisherController extends Controller
{
    public function index(): View
    {
        $publishers = Publisher::withCount('books')->orderBy('name')->paginate(15);

        return view('publishers.index', compact('publishers'));
    }

    public function show(Publisher $publisher): View
    {
        $books = $publisher->books()
            ->with(['authors', 'publisher'])
            ->orderBy('title')
            ->paginate(10);

        return view('publishers.show', compact('publisher', 'books'));
    }
}
