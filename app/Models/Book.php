<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Book extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    /** Ngưỡng cảnh báo sắp hết hàng. */
    public const LOW_STOCK = 5;

    /** Thư mục lưu ảnh bìa trên disk "public". */
    public const COVER_DIR = 'covers';

    protected $fillable = [
        'isbn',
        'title',
        'published_year',
        'price',
        'stock_quantity',
        'page_count',
        'dimensions',
        'cover_image_url',
        'description',
        'publisher_id',
    ];

    protected $casts = [
        'published_year' => 'integer',
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'page_count' => 'integer',
    ];

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'author_book');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'book_category');
    }

    /** Các kiểu sắp xếp cho danh sách: key => [cột, chiều]. */
    public const SORTS = [
        'newest' => ['id', 'desc'],
        'title' => ['title', 'asc'],
        'price_asc' => ['price', 'asc'],
        'price_desc' => ['price', 'desc'],
        'stock_asc' => ['stock_quantity', 'asc'],
    ];

    /**
     * Tìm kiếm, lọc và sắp xếp theo các tham số đã validate (xem BookFilterRequest).
     *
     * @param  array<string, mixed>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        [$sortColumn, $sortDirection] = self::SORTS[$filters['sort'] ?? 'newest'] ?? self::SORTS['newest'];

        return $query
            ->when($filters['q'] ?? null, function (Builder $q, string $keyword) {
                $q->where(fn (Builder $w) => $w
                    ->where('title', 'like', "%{$keyword}%")
                    ->orWhere('isbn', 'like', "%{$keyword}%"));
            })
            // Chọn nhiều thể loại / tác giả: sách thuộc bất kỳ mục nào đã chọn
            ->when($filters['category_id'] ?? null, fn (Builder $q, $ids) => $q->whereHas('categories', fn (Builder $c) => $c->whereKey((array) $ids)))
            ->when($filters['author_id'] ?? null, fn (Builder $q, $ids) => $q->whereHas('authors', fn (Builder $a) => $a->whereKey((array) $ids)))
            ->when($filters['publisher_id'] ?? null, fn (Builder $q, $id) => $q->where('publisher_id', $id))
            ->when(isset($filters['price_min']), fn (Builder $q) => $q->where('price', '>=', $filters['price_min']))
            ->when(isset($filters['price_max']), fn (Builder $q) => $q->where('price', '<=', $filters['price_max']))
            ->when($filters['stock'] ?? null, fn (Builder $q, string $stock) => match ($stock) {
                'out' => $q->where('stock_quantity', 0),
                'low' => $q->whereBetween('stock_quantity', [1, self::LOW_STOCK - 1]),
                'in' => $q->where('stock_quantity', '>=', self::LOW_STOCK),
            })
            ->orderBy($sortColumn, $sortDirection)
            ->orderBy('id', 'desc');
    }

    /**
     * URL hiển thị ảnh bìa: link ngoài giữ nguyên, file upload lấy từ storage, không có thì null.
     */
    protected function coverUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            $path = $this->cover_image_url;

            if (! $path) {
                return null;
            }

            return Str::startsWith($path, ['http://', 'https://'])
                ? $path
                : Storage::disk('public')->url($path);
        });
    }

    /**
     * Xóa file ảnh bìa đã upload (bỏ qua link ngoài).
     */
    public function deleteCoverFile(): void
    {
        $path = $this->cover_image_url;

        if ($path && ! Str::startsWith($path, ['http://', 'https://'])) {
            Storage::disk('public')->delete($path);
        }
    }
}
