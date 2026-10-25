<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'isbn' => ['nullable', 'string', 'max:20', $this->uniqueIsbn()],
            'title' => ['required', 'string', 'max:255'],
            // Cột YEAR của MySQL chỉ nhận 1901 - 2155
            'published_year' => ['nullable', 'integer', 'min:1901', 'max:'.(now()->year + 1)],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'page_count' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'dimensions' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'publisher_id' => ['nullable', 'integer', 'exists:publishers,id'],
            'author_ids' => ['required', 'array', 'min:1'],
            'author_ids.*' => ['integer', 'distinct', 'exists:authors,id'],
            'category_ids' => ['required', 'array', 'min:1'],
            'category_ids.*' => ['integer', 'distinct', 'exists:categories,id'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'isbn' => 'ISBN',
            'title' => 'tên sách',
            'published_year' => 'năm xuất bản',
            'price' => 'giá bán',
            'stock_quantity' => 'số lượng tồn',
            'page_count' => 'số trang',
            'dimensions' => 'kích thước',
            'description' => 'mô tả',
            'publisher_id' => 'nhà xuất bản',
            'author_ids' => 'tác giả',
            'author_ids.*' => 'tác giả',
            'category_ids' => 'thể loại',
            'category_ids.*' => 'thể loại',
            'cover_image' => 'ảnh bìa',
        ];
    }

    protected function uniqueIsbn(): Unique
    {
        return Rule::unique('books', 'isbn');
    }
}
