<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Concerns\BookFixtures;
use Tests\TestCase;

class BookTrashTest extends TestCase
{
    use BookFixtures, RefreshDatabase;

    public function test_trash_lists_only_deleted_books(): void
    {
        $this->makeBook()->delete();

        $this->get(route('books.trash'))->assertOk()->assertSee('Sách cũ')->assertSee('Khôi phục');
    }

    public function test_restore_brings_book_back_with_relations(): void
    {
        $book = $this->makeBook();
        $book->delete();

        $this->post(route('books.restore', $book->id))->assertRedirect(route('books.trash'));

        $this->assertNotSoftDeleted($book);
        $this->get(route('books.show', $book))->assertOk()->assertSee('Tô Hoài');
    }

    public function test_force_delete_only_works_on_trashed_books(): void
    {
        $book = $this->makeBook();

        // Sách đang hiển thị thì không xóa vĩnh viễn / khôi phục được
        $this->delete(route('books.forceDelete', $book->id))->assertNotFound();
        $this->post(route('books.restore', $book->id))->assertNotFound();

        $book->delete();
        $this->delete(route('books.forceDelete', $book->id))->assertRedirect(route('books.trash'));

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
        $this->assertDatabaseMissing('author_book', ['book_id' => $book->id]);
        $this->assertDatabaseMissing('book_category', ['book_id' => $book->id]);
        Storage::disk('public')->assertMissing('covers/old.jpg');
    }
}
