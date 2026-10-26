<?php

namespace Tests\Feature;

use App\Models\Author;
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

    public function test_filter_by_multiple_categories_and_authors(): void
    {
        $kids = Category::create(['name' => 'Thiếu Nhi']);
        $science = Category::create(['name' => 'Khoa Học']);
        $otherAuthor = Author::create(['name' => 'Nam Cao']);

        $a = Book::create(['title' => 'Sách văn học', 'price' => 1000]);
        $a->categories()->attach($this->category);
        $a->authors()->attach($this->author);
        $b = Book::create(['title' => 'Sách thiếu nhi', 'price' => 1000]);
        $b->categories()->attach($kids);
        $b->authors()->attach($otherAuthor);
        $c = Book::create(['title' => 'Sách khoa học', 'price' => 1000]);
        $c->categories()->attach($science);

        $titles = fn (array $query) => $this->get(route('books.index', $query + ['sort' => 'title']))->assertOk()
            ->viewData('books')->pluck('title')->all();

        // Nhiều thể loại: sách thuộc BẤT KỲ thể loại đã chọn
        $this->assertSame(['Sách thiếu nhi', 'Sách văn học'], $titles(['category_id' => [$this->category->id, $kids->id]]));
        // Nhiều tác giả
        $this->assertSame(['Sách thiếu nhi', 'Sách văn học'], $titles(['author_id' => [$this->author->id, $otherAuthor->id]]));
        // Kết hợp: (thể loại A hoặc B) VÀ tác giả X
        $this->assertSame(['Sách thiếu nhi'], $titles(['category_id' => [$this->category->id, $kids->id], 'author_id' => [$otherAuthor->id]]));
        // Link cũ dạng một giá trị vẫn chạy
        $this->assertSame(['Sách khoa học'], $titles(['category_id' => $science->id]));
        // Ô tick trống bị bỏ qua
        $this->assertCount(3, $titles(['category_id' => ['']]));
    }

    public function test_filter_by_price_range_option(): void
    {
        Book::create(['title' => 'Rẻ', 'price' => 50000]);
        Book::create(['title' => 'Vừa', 'price' => 150000]);
        Book::create(['title' => 'Đắt', 'price' => 600000]);

        $titles = fn (array $query) => $this->get(route('books.index', $query))->viewData('books')->pluck('title')->all();

        $this->assertSame(['Rẻ'], $titles(['price_range' => 'lt100']));
        $this->assertSame(['Vừa'], $titles(['price_range' => '100-200']));
        $this->assertSame(['Đắt'], $titles(['price_range' => 'gt500']));
        $this->assertCount(3, $titles(['price_range' => 'all']));
    }

    public function test_selected_filters_are_checked_and_removable(): void
    {
        $kids = Category::create(['name' => 'Thiếu Nhi']);

        $response = $this->get(route('books.index', ['category_id' => [$this->category->id, $kids->id]]));

        foreach ([$this->category->id, $kids->id] as $id) {
            $this->assertMatchesRegularExpression('/id="cat-'.$id.'"\s+checked/', $response->getContent());
        }

        $response->assertSee('Đang lọc:')
            // Bấm × ở "Văn Học" chỉ bỏ Văn Học, giữ Thiếu Nhi
            ->assertSee(e(route('books.index', ['category_id' => [$kids->id]])), false);
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
