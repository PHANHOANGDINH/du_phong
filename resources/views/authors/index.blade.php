@extends('layouts.app')
@section('title', 'Tác giả')

@section('content')
<div class="container pb-4">
    <h3 class="fw-bold text-primary mb-3">TÁC GIẢ</h3>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px">#</th>
                        <th>Tên tác giả</th>
                        <th>Tiểu sử</th>
                        <th class="text-center">Số sách</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($authors as $author)
                        <tr>
                            <td class="text-muted">{{ $author->id }}</td>
                            <td><a href="{{ route('authors.show', $author) }}" class="fw-semibold text-decoration-none">{{ $author->name }}</a></td>
                            <td class="small text-muted">{{ Str::limit($author->biography, 90) ?: '—' }}</td>
                            <td class="text-center">{{ $author->books_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Chưa có tác giả nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $authors->links() }}</div>
</div>
@endsection
