<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Concerns\BookFixtures;
use Tests\TestCase;

class BookCrudTest extends TestCase
{
    use BookFixtures, RefreshDatabase;

    public function test_store_creates_book_with_relations_and_cover(): void
    {
        $response = $this->post(route('books.store'), $this->validPayload([
            'cover_image' => UploadedFile::fake()->image('cover.jpg'),
        ]));

        $book = Book::firstWhere('isbn', '9786042000001');
        $response->assertRedirect(route('books.show', $book));

        $this->assertSame(12, $book->stock_quantity);
        $this->assertEquals([$this->author->id], $book->authors->pluck('id')->all());
        $this->assertEquals([$this->category->id], $book->categories->pluck('id')->all());
        Storage::disk('public')->assertExists($book->cover_image_url);
    }

    public function test_store_validates_input(): void
    {
        $this->post(route('books.store'), $this->validPayload([
            'title' => '',
            'price' => -1,
            'stock_quantity' => -5,
            'author_ids' => [],
            'category_ids' => [999],
        ]))->assertSessionHasErrors(['title', 'price', 'stock_quantity', 'author_ids', 'category_ids.0']);

        $this->assertSame(0, Book::count());
    }

    public function test_isbn_must_be_unique_including_deleted_books(): void
    {
        $book = $this->makeBook();

        $this->post(route('books.store'), $this->validPayload(['isbn' => '111']))
            ->assertSessionHasErrors('isbn');

        // Sách đã xóa mềm vẫn giữ ISBN
        $book->delete();
        $this->post(route('books.store'), $this->validPayload(['isbn' => '111']))
            ->assertSessionHasErrors('isbn');
    }

    public function test_update_replaces_cover_and_relations(): void
    {
        $book = $this->makeBook();
        $otherCategory = Category::create(['name' => 'Thiếu Nhi']);

        $this->put(route('books.update', $book), $this->validPayload([
            'title' => 'Tên mới',
            'category_ids' => [$otherCategory->id],
            'cover_image' => UploadedFile::fake()->image('new.png'),
        ]))->assertRedirect(route('books.show', $book));

        $book->refresh();
        $this->assertSame('Tên mới', $book->title);
        $this->assertEquals([$otherCategory->id], $book->categories->pluck('id')->all());
        Storage::disk('public')->assertMissing('covers/old.jpg');
        Storage::disk('public')->assertExists($book->cover_image_url);
    }

    public function test_update_changes_stock_but_keeps_isbn(): void
    {
        $book = $this->makeBook();

        $this->put(route('books.update', $book), $this->validPayload([
            'isbn' => '999',
            'stock_quantity' => 500,
        ]))->assertSessionHasNoErrors();

        $book->refresh();
        $this->assertSame('111', $book->isbn);
        $this->assertSame(500, $book->stock_quantity);
    }

    public function test_destroy_soft_deletes_and_keeps_data(): void
    {
        $book = $this->makeBook();

        $this->delete(route('books.destroy', $book))->assertRedirect(route('books.index'));

        // Ẩn khỏi hệ thống nhưng vẫn còn trong CSDL
        $this->assertSoftDeleted($book);
        $this->get(route('books.show', $book))->assertNotFound();

        // Liên kết và ảnh bìa được giữ để khôi phục
        $this->assertDatabaseHas('author_book', ['book_id' => $book->id]);
        $this->assertDatabaseHas('book_category', ['book_id' => $book->id]);
        Storage::disk('public')->assertExists('covers/old.jpg');

        $book->restore();
        $this->assertEquals([$this->author->id], $book->authors->pluck('id')->all());
    }
}
