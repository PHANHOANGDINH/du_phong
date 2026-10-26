{{--
=============================================================================
CẬP NHẬT ĐẦU SÁCH (chọn ảnh mới sẽ thay ảnh cũ)
=============================================================================
--}}
@extends('layouts.app')
@section('title', 'Cập nhật Đầu sách')

@section('content')
<div>
    <div class="shop-box overflow-hidden mx-auto" style="max-width: 900px">
        <div class="px-4 pt-4"><h1 class="page-title">Sửa sách: {{ $book->title }}</h1></div>
        <div class="p-4">
            <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('books.partials.form')

                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-shop px-4"><i class="bi bi-save"></i> Cập nhật</button>
                    <a href="{{ route('books.show', $book) }}" class="btn btn-outline-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
