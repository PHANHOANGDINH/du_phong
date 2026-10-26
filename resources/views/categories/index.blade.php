@extends('layouts.app')
@section('title', 'Thể loại')

@section('content')
<div>
    <h1 class="page-title mb-3">Thể loại</h1>

    <div class="shop-box overflow-hidden">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px">#</th>
                    <th>Tên thể loại</th>
                    <th class="text-center">Số sách</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td class="text-muted">{{ $category->id }}</td>
                        <td><a href="{{ route('categories.show', $category) }}" class="fw-semibold text-decoration-none">{{ $category->name }}</a></td>
                        <td class="text-center">{{ $category->books_count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">Chưa có thể loại nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $categories->links() }}</div>
</div>
@endsection
