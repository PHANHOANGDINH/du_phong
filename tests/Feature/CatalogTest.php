<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\BookFixtures;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use BookFixtures, RefreshDatabase;

    public function test_catalogs_list_and_show_related_books(): void
    {
        $this->makeBook();
        $deleted = Book::create(['title' => 'Sách đã xóa', 'price' => 0]);
        $deleted->categories()->attach($this->category);
        $deleted->delete();

        $this->get(route('categories.index'))->assertOk()->assertSee('Văn Học');
        $this->get(route('authors.index'))->assertOk()->assertSee('Tô Hoài');
        $this->get(route('publishers.index'))->assertOk()->assertSee('Kim Đồng', false);

        $this->get(route('categories.show', $this->category))->assertOk()
            ->assertSee('Sách cũ')->assertDontSee('Sách đã xóa');
        $this->get(route('authors.show', $this->author))->assertOk()->assertSee('Sách cũ');
        $this->get(route('publishers.show', $this->publisher))->assertOk()->assertSee('Sách cũ');
    }

    public function test_book_detail_links_to_catalog_pages(): void
    {
        $book = $this->makeBook();

        $this->get(route('books.show', $book))
            ->assertSee(route('authors.show', $this->author), false)
            ->assertSee(route('categories.show', $this->category), false)
            ->assertSee(route('publishers.show', $this->publisher), false);
    }

    public function test_catalogs_are_read_only(): void
    {
        foreach (['categories' => $this->category, 'authors' => $this->author, 'publishers' => $this->publisher] as $uri => $model) {
            $this->get("/$uri/create")->assertNotFound();
            $this->post("/$uri", ['name' => 'Mới'])->assertStatus(405);
            $this->put("/$uri/{$model->id}", ['name' => 'Sửa'])->assertStatus(405);
            $this->delete("/$uri/{$model->id}")->assertStatus(405);
            $this->assertModelExists($model);
        }
    }
}
