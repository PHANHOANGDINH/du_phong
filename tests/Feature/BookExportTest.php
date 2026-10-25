<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\BookFixtures;
use Tests\TestCase;
use ZipArchive;

class BookExportTest extends TestCase
{
    use BookFixtures, RefreshDatabase;

    public function test_export_excel_follows_filters(): void
    {
        $this->makeBook();
        Book::create(['title' => 'Sách khác', 'price' => 1]);

        $response = $this->get(route('books.exportExcel', ['q' => 'cũ']));

        $response->assertOk()->assertDownload();
        $path = $response->baseResponse->getFile()->getPathname();

        $zip = new ZipArchive;
        $this->assertTrue($zip->open($path)); // file .xlsx thật (định dạng zip)
        $sheet = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();
        unlink($path); // trong test response không được gửi nên deleteFileAfterSend không chạy

        $this->assertStringContainsString('Tên sách', $sheet);
        $this->assertStringContainsString('Sách cũ', $sheet);
        $this->assertStringContainsString('Tô Hoài', $sheet);
        $this->assertStringNotContainsString('Sách khác', $sheet);
    }

    public function test_list_page_links_export_with_current_filters(): void
    {
        $this->get(route('books.index', ['stock' => 'low']))
            ->assertSee(route('books.exportExcel', ['stock' => 'low']), false);
    }
}
