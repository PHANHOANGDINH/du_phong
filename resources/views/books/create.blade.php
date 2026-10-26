{{--
=============================================================================
THÊM MỚI ĐẦU SÁCH
=============================================================================
--}}
@extends('layouts.app')
@section('title', 'Thêm mới Đầu sách')

@section('content')
<div>
    <div class="shop-box overflow-hidden mx-auto" style="max-width: 900px">
        <div class="px-4 pt-4"><h1 class="page-title">Thêm sách mới</h1></div>
        <div class="p-4">
            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('books.partials.form')

                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-shop px-4"><i class="bi bi-save"></i> Lưu</button>
                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
