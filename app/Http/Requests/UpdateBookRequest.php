<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;

class UpdateBookRequest extends StoreBookRequest
{
    /**
     * Giống khi thêm mới, trừ mã sách (ISBN) chỉ đọc sau khi đã tạo.
     * Bỏ khỏi rules nên dù client cố gửi lên cũng không lọt vào dữ liệu đã validate.
     */
    public function rules(): array
    {
        return Arr::except(parent::rules(), ['isbn']);
    }
}
