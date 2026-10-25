<?php

/*
| Thông báo lỗi validation tiếng Việt cho các rule đang dùng trong dự án.
| Rule nào chưa có ở đây sẽ tự lấy bản tiếng Anh (APP_FALLBACK_LOCALE=en).
*/

return [
    'array' => ':Attribute phải là một danh sách.',
    'distinct' => ':Attribute bị chọn trùng.',
    'exists' => ':Attribute đã chọn không tồn tại.',
    'image' => ':Attribute phải là file ảnh.',
    'integer' => ':Attribute phải là số nguyên.',
    'max' => [
        'array' => ':Attribute không được vượt quá :max mục.',
        'file' => ':Attribute không được lớn hơn :max KB.',
        'numeric' => ':Attribute không được lớn hơn :max.',
        'string' => ':Attribute không được dài quá :max ký tự.',
    ],
    'mimes' => ':Attribute phải có định dạng: :values.',
    'min' => [
        'array' => ':Attribute phải có ít nhất :min mục.',
        'file' => ':Attribute phải có dung lượng tối thiểu :min KB.',
        'numeric' => ':Attribute phải lớn hơn hoặc bằng :min.',
        'string' => ':Attribute phải có ít nhất :min ký tự.',
    ],
    'numeric' => ':Attribute phải là số.',
    'required' => 'Vui lòng nhập :attribute.',
    'string' => ':Attribute phải là chuỗi ký tự.',
    'unique' => ':Attribute này đã tồn tại.',
    'uploaded' => 'Tải lên :attribute thất bại.',

    'attributes' => [],
];
