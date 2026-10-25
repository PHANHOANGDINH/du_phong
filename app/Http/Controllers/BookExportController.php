<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookFilterRequest;
use App\Models\Book;
use Illuminate\Support\Str;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BookExportController extends Controller
{
    private const HEADINGS = [
        'ID', 'ISBN', 'Tên sách', 'Tác giả', 'Thể loại', 'Nhà xuất bản',
        'Năm XB', 'Số trang', 'Kích thước', 'Giá bán (₫)', 'Số lượng tồn',
    ];

    /**
     * Xuất file .xlsx đúng theo bộ lọc / sắp xếp đang áp dụng ở trang danh sách.
     */
    public function exportExcel(BookFilterRequest $request): BinaryFileResponse
    {
        // File tạm trong storage của dự án (thư mục temp hệ thống có thể không ghi được khi chạy web server), xóa sau khi gửi
        $path = storage_path('app/private/export-'.Str::uuid().'.xlsx');

        $writer = new Writer(new Options(tempFolder: dirname($path)));
        $writer->getOptions()->setColumnWidth(45, 3);
        $writer->getOptions()->setColumnWidth(28, 4, 5, 6);
        $writer->openToFile($path);
        $writer->addRow(Row::fromValuesWithStyle(self::HEADINGS, new Style(fontBold: true, backgroundColor: 'DDEBF7')));

        Book::with(['publisher', 'authors', 'categories'])
            ->filter($request->filters())
            ->lazy()
            ->each(fn (Book $book) => $writer->addRow(Row::fromValues([
                $book->id,
                $book->isbn,
                $book->title,
                $book->authors->pluck('name')->join(', '),
                $book->categories->pluck('name')->join(', '),
                $book->publisher?->name,
                $book->published_year,
                $book->page_count,
                $book->dimensions,
                (float) $book->price,
                $book->stock_quantity,
            ])));

        $writer->close();

        return response()
            ->download($path, 'danh-sach-sach-'.now()->format('Ymd-His').'.xlsx')
            ->deleteFileAfterSend();
    }
}
