<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\BookFixtures;
use Tests\TestCase;

class BookFilterTest extends TestCase
{
    use BookFixtures, RefreshDatabase;

    public function test_index_filters_and_sorts(): void
    {
        $otherCategory = Category::create(['name' => 'Thiếu Nhi']);
        $cheap = Book::create(['title' => 'Conan tập 1', 'isbn' => 'C1', 'price' => 20000, 'stock_quantity' => 0]);
        $cheap->categories()->attach($otherCategory);
        $mid = Book::create(['title' => 'Nhà Giả Kim', 'isbn' => 'NGK', 'price' => 70000, 'stock_quantity' => 3, 'publisher_id' => $this->publisher->id]);
        $mid->authors()->attach($this->author);
        Book::create(['title' => 'Mưa Đỏ', 'price' => 180000, 'stock_quantity' => 50]);

        $titles = fn (array $query) => $this->get(route('books.index', $query))->assertOk()
            ->viewData('books')->pluck('title')->all();

        $this->assertSame(['Nhà Giả Kim'], $titles(['q' => 'giả kim']));
        $this->assertSame(['Conan tập 1'], $titles(['q' => 'C1']));
        $this->assertSame(['Conan tập 1'], $titles(['category_id' => $otherCategory->id]));
        $this->assertSame(['Nhà Giả Kim'], $titles(['author_id' => $this->author->id]));
        $this->assertSame(['Nhà Giả Kim'], $titles(['publisher_id' => $this->publisher->id]));
        $this->assertSame(['Nhà Giả Kim', 'Mưa Đỏ'], $titles(['price_min' => 50000, 'sort' => 'price_asc']));
        $this->assertSame(['Conan tập 1'], $titles(['stock' => 'out']));
        $this->assertSame(['Nhà Giả Kim'], $titles(['stock' => 'low']));
        $this->assertSame(['Mưa Đỏ'], $titles(['stock' => 'in']));
        $this->assertSame(['Mưa Đỏ', 'Nhà Giả Kim', 'Conan tập 1'], $titles(['sort' => 'price_desc']));
    }

    public function test_sidebar_links_combine_with_current_filters(): void
    {
        $response = $this->get(route('books.index', ['category_id' => $this->category->id, 'q' => 'abc']));

        // Bấm "100k - 200k" giữ nguyên thể loại và từ khóa đang chọn
        $response->assertSee(e(route('books.index', [
            'q' => 'abc', 'category_id' => $this->category->id, 'price_min' => 100000, 'price_max' => 199999,
        ])), false);
        // Bấm "Tất cả" thể loại chỉ bỏ thể loại
        $response->assertSee(e(route('books.index', ['q' => 'abc'])), false);
    }

    public function test_filter_is_kept_in_form_and_pagination(): void
    {
        foreach (range(1, 13) as $i) { // hơn 1 trang (12 sách/trang)
            Book::create(['title' => "Sách lọc $i", 'price' => 1000]);
        }

        $this->get(route('books.index', ['q' => 'lọc']))
            ->assertOk()
            ->assertSee('value="lọc"', false)
            ->assertSee('q=l%E1%BB%8Dc&amp;page=2', false);
    }

    public function test_invalid_filter_is_rejected(): void
    {
        $this->from(route('books.index'))->get(route('books.index', ['sort' => 'hack']))
            ->assertSessionHasErrors('sort');
    }
}
