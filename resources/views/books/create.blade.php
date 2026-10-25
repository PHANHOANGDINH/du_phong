{{--
=============================================================================
THÊM MỚI ĐẦU SÁCH
=============================================================================
--}}
@extends('layouts.app')
@section('title', 'Thêm mới Đầu sách')

@section('content')
<div class="container pb-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white"><h5 class="mb-0">THÊM MỚI ĐẦU SÁCH</h5></div>
        <div class="card-body">
            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('books.partials.form')

                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-success"><i class="bi bi-save"></i> Lưu</button>
                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
