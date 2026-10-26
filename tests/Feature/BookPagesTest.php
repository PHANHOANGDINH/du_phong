<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\BookFixtures;
use Tests\TestCase;

class BookPagesTest extends TestCase
{
    use BookFixtures, RefreshDatabase;

    public function test_pages_render(): void
    {
        $book = $this->makeBook();

        $this->get(route('books.index'))->assertOk()->assertSee('Sách cũ')->assertSee('Sắp hết (1)');
        $this->get(route('books.create'))->assertOk()->assertSee('Tô Hoài')->assertSee('name="isbn"', false);
        $this->get(route('books.show', $book))->assertOk()->assertSee('Kim Đồng', false)->assertSee('/storage/covers/old.jpg');
        $this->get(route('books.edit', $book))->assertOk()->assertSee('Sách cũ')
            ->assertDontSee('name="isbn"', false)       // ISBN chỉ đọc khi sửa
            ->assertSee('name="stock_quantity"', false);
    }

    public function test_deleted_book_is_hidden_from_list(): void
    {
        $book = $this->makeBook();

        $this->delete(route('books.destroy', $book));

        $this->get(route('books.index'))->assertSee('Không có sách nào phù hợp.');
    }

    public function test_detail_page_shows_related_books_of_same_category(): void
    {
        $book = $this->makeBook();
        $sameCategory = \App\Models\Book::create(['title' => 'Sách cùng thể loại', 'price' => 1000]);
        $sameCategory->categories()->attach($this->category);
        \App\Models\Book::create(['title' => 'Sách khác thể loại', 'price' => 1000]);

        $this->get(route('books.show', $book))
            ->assertSee('Sản phẩm liên quan')
            ->assertSee('Sách cùng thể loại')
            ->assertDontSee('Sách khác thể loại');
    }

    public function test_book_without_cover_gets_placeholder(): void
    {
        $book = \App\Models\Book::create(['title' => 'Không có bìa', 'price' => 1000]);

        $this->get(route('books.show', $book))->assertSee('cover-placeholder', false);
    }
}
