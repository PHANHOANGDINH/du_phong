<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

/**
 * Thể loại: chỉ xem.
 */
class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('books')->orderBy('name')->paginate(15);

        return view('categories.index', compact('categories'));
    }

    public function show(Category $category): View
    {
        $books = $category->books()
            ->with(['authors', 'publisher'])
            ->orderBy('title')
            ->paginate(10);

        return view('categories.show', compact('category', 'books'));
    }
}
