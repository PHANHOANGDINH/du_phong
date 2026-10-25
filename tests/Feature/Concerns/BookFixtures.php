<?php

namespace Tests\Feature\Concerns;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Support\Facades\Storage;

/**
 * Dữ liệu mẫu dùng chung cho các test về sách.
 * Laravel tự gọi setUpBookFixtures() trước mỗi test.
 */
trait BookFixtures
{
    protected Publisher $publisher;

    protected Author $author;

    protected Category $category;

    protected function setUpBookFixtures(): void
    {
        Storage::fake('public');

        $this->publisher = Publisher::create(['name' => 'Kim Đồng']);
        $this->author = Author::create(['name' => 'Tô Hoài']);
        $this->category = Category::create(['name' => 'Văn Học']);
    }

    protected function validPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => "Dế Mèn Phiêu Lưu Ký's",
            'isbn' => '9786042000001',
            'price' => 55000,
            'stock_quantity' => 12,
            'published_year' => 2020,
            'page_count' => 144,
            'publisher_id' => $this->publisher->id,
            'author_ids' => [$this->author->id],
            'category_ids' => [$this->category->id],
        ], $overrides);
    }

    /** Sách "Sách cũ" có đủ tác giả, thể loại, NXB và một file ảnh bìa covers/old.jpg. */
    protected function makeBook(): Book
    {
        $book = Book::create([
            'title' => 'Sách cũ',
            'isbn' => '111',
            'price' => 10000,
            'stock_quantity' => 1,
            'cover_image_url' => 'covers/old.jpg',
            'publisher_id' => $this->publisher->id,
        ]);
        $book->authors()->attach($this->author);
        $book->categories()->attach($this->category);
        Storage::disk('public')->put('covers/old.jpg', 'x');

        return $book;
    }
}
