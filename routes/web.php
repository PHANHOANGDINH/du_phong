<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookExportController;
use App\Http\Controllers\BookTrashController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PublisherController;

Route::get('/', function () {
    return redirect()->route('books.index');
});

// Sách: thùng rác (xóa mềm) và xuất Excel — khai báo trước resource để không bị hiểu nhầm là {book}
Route::prefix('books/trash')->name('books.')->controller(BookTrashController::class)->group(function () {
    Route::get('/', 'index')->name('trash');
    Route::post('/{id}/restore', 'restore')->whereNumber('id')->name('restore');
    Route::delete('/{id}/force-delete', 'forceDelete')->whereNumber('id')->name('forceDelete');
});
Route::get('/books/export/excel', [BookExportController::class, 'exportExcel'])->name('books.exportExcel');

// Sách: CRUD đầy đủ
Route::resource('books', BookController::class);

// Danh mục phụ: chỉ xem (danh sách + chi tiết kèm các sách liên quan)
Route::resource('categories', CategoryController::class)->only(['index', 'show']);
Route::resource('authors', AuthorController::class)->only(['index', 'show']);
Route::resource('publishers', PublisherController::class)->only(['index', 'show']);
