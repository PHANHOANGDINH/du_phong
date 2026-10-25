<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookExportController;
use App\Http\Controllers\BookTrashController;

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
