@extends('layouts.app')
@section('title', 'Nhà xuất bản')

@section('content')
<div>
    <h1 class="page-title mb-3">Nhà xuất bản</h1>

    <div class="shop-box overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px">#</th>
                        <th>Tên nhà xuất bản</th>
                        <th>Địa chỉ</th>
                        <th class="text-center">Số sách</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($publishers as $publisher)
                        <tr>
                            <td class="text-muted">{{ $publisher->id }}</td>
                            <td><a href="{{ route('publishers.show', $publisher) }}" class="fw-semibold text-decoration-none">{{ $publisher->name }}</a></td>
                            <td class="small text-muted">{{ $publisher->address ?? '—' }}</td>
                            <td class="text-center">{{ $publisher->books_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Chưa có nhà xuất bản nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $publishers->links() }}</div>
</div>
@endsection
