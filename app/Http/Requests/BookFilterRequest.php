<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Tham số tìm kiếm / lọc / sắp xếp cho danh sách và file xuất Excel.
 */
class BookFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer'],
            'author_id' => ['nullable', 'integer'],
            'publisher_id' => ['nullable', 'integer'],
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
            'price_min' => 'giá từ',
            'price_max' => 'giá đến',
            'stock' => 'tình trạng kho',
            'sort' => 'sắp xếp',
        ];
    }

    /**
     * Bộ lọc đã validate, bỏ các ô để trống.
     *
     * @return array<string, mixed>
     */
    public function filters(): array
    {
        return array_filter($this->validated(), fn ($value) => $value !== null && $value !== '');
    }
}
