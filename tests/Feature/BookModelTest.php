<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BookModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_schema_uses_stock_quantity_and_soft_deletes(): void
    {
        $this->assertTrue(Schema::hasColumns('books', ['stock_quantity', 'deleted_at']));
        $this->assertFalse(Schema::hasTable('warehouses'));
        $this->assertFalse(Schema::hasTable('book_warehouse'));
    }

    public function test_seeder_creates_books_with_relations_and_stock(): void
    {
        $this->seed();

        $this->assertSame(58, Book::count());
        $this->assertTrue(Book::where('stock_quantity', 0)->exists());
        $this->assertTrue(Book::where('stock_quantity', '>=', Book::LOW_STOCK)->exists());
        $this->assertNotEmpty(Book::first()->authors);
        $this->assertNotEmpty(Book::first()->categories);
    }

    public function test_soft_delete_keeps_row(): void
    {
        $book = Book::create(['title' => 'Sách thử', 'price' => 1000]);

        $book->delete();

        $this->assertSoftDeleted($book);
        $this->assertSame(0, Book::count());
        $this->assertSame(1, Book::withTrashed()->count());
    }

    public function test_sample_seeder_adds_20_books_without_duplicates(): void
    {
        $this->seed();
        $authorsBefore = \App\Models\Author::count();

        $this->seed(\Database\Seeders\SampleBookSeeder::class);
        Book::firstWhere('title', 'Mắt Biếc')->delete();
        $this->seed(\Database\Seeders\SampleBookSeeder::class); // chạy lại: không trùng, khôi phục sách lỡ xóa

        $this->assertSame(78, Book::count());
        $this->assertSame(0, Book::onlyTrashed()->count());

        $book = Book::firstWhere('title', 'Mắt Biếc');
        $this->assertSame(['Nguyễn Nhật Ánh'], $book->authors->pluck('name')->all());
        $this->assertSame(['Văn Học'], $book->categories->pluck('name')->all());
        $this->assertSame('NXB Trẻ', $book->publisher->name);

        // Tô Hoài, Gosho Aoyama... đã có sẵn thì dùng lại, không tạo trùng
        $this->assertSame(1, \App\Models\Author::where('name', 'Tô Hoài')->count());
        $this->assertLessThan(20, \App\Models\Author::count() - $authorsBefore);
    }

    public function test_diverse_seeder_adds_multilingual_multi_author_books(): void
    {
        $this->seed();
        $this->seed(\Database\Seeders\DiverseBookSeeder::class);
        $this->seed(\Database\Seeders\DiverseBookSeeder::class); // chạy lại không trùng

        $this->assertSame(88, Book::count());
        $this->assertSame(12, \App\Models\Category::count());

        $gof = Book::firstWhere('title', 'like', 'Design Patterns%');
        $this->assertCount(4, $gof->authors);

        foreach (['ノルウェイの森', '三体', '채식주의자', 'Война и мир', "L'Étranger"] as $title) {
            $this->get(route('books.index', ['q' => $title]))->assertOk()->assertSee(e($title), false);
        }
    }
}
