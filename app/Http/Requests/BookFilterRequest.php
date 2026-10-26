<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Tham số tìm kiếm / lọc / sắp xếp cho danh sách và file xuất Excel.
 * Thể loại và tác giả chọn được nhiều (category_id[], author_id[]): sách thuộc BẤT KỲ mục nào đã chọn.
 */
class BookFilterRequest extends FormRequest
{
    /** Các khoảng giá ở thanh lọc: key => [nhãn, giá từ, giá đến]. */
    public const PRICE_RANGES = [
        'lt100' => ['Dưới 100.000đ', null, 99999],
        '100-200' => ['100.000đ - 200.000đ', 100000, 199999],
        '200-300' => ['200.000đ - 300.000đ', 200000, 299999],
        '300-500' => ['300.000đ - 500.000đ', 300000, 499999],
        'gt500' => ['Trên 500.000đ', 500000, null],
    ];

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Link cũ dạng ?category_id=5 vẫn dùng được: đổi giá trị đơn thành mảng, bỏ ô trống
        foreach (['category_id', 'author_id'] as $field) {
            if ($this->has($field)) {
                $values = array_filter((array) $this->input($field), fn ($id) => $id !== null && $id !== '');
                $this->merge([$field => array_values($values)]);
            }
        }

        // Chọn một khoảng giá có sẵn -> quy ra giá từ / giá đến
        if ($this->has('price_range')) {
            [, $min, $max] = self::PRICE_RANGES[$this->input('price_range')] ?? [null, null, null];
            $this->merge(['price_min' => $min, 'price_max' => $max]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'array'],
            'category_id.*' => ['integer'],
            'author_id' => ['nullable', 'array'],
            'author_id.*' => ['integer'],
            'publisher_id' => ['nullable', 'integer'],
            'price_range' => ['nullable', Rule::in(['all', ...array_keys(self::PRICE_RANGES)])],
            'price_min' => ['nullable', 'numeric', 'min:0'],
            'price_max' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', Rule::in(['out', 'low', 'in'])],
            'sort' => ['nullable', Rule::in(array_keys(Book::SORTS))],
        ];
    }

    public function attributes(): array
    {
        return [
            'q' => 'từ khóa',
            'category_id' => 'thể loại',
            'author_id' => 'tác giả',
            'price_range' => 'khoảng giá',
            'price_min' => 'giá từ',
            'price_max' => 'giá đến',
            'stock' => 'tình trạng kho',
            'sort' => 'sắp xếp',
        ];
    }

    /**
     * Bộ lọc đã validate, bỏ các ô để trống (price_range đã được quy ra price_min / price_max).
     *
     * @return array<string, mixed>
     */
    public function filters(): array
    {
        return array_filter(
            collect($this->validated())->except('price_range')->all(),
            fn ($value) => $value !== null && $value !== '' && $value !== [],
        );
    }
}
