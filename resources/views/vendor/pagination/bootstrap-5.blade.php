{{-- Phân trang (ghi đè mẫu bootstrap-5 của Laravel): 2 hàng canh giữa — các nút trang, rồi dòng tổng số --}}
@if ($paginator->hasPages())
    <nav class="d-flex flex-column align-items-center gap-2" aria-label="Phân trang">
        <ul class="pagination flex-wrap justify-content-center mb-0">
            {{-- Trang trước --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">&lsaquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Trang trước">&lsaquo;</a></li>
            @endif

            @foreach ($elements as $element)
                {{-- Dấu "..." --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Trang sau --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Trang sau">&rsaquo;</a></li>
            @else
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">&rsaquo;</span></li>
            @endif
        </ul>

        <div class="small text-muted">
            Hiển thị {{ $paginator->firstItem() }} – {{ $paginator->lastItem() }} trong tổng số {{ $paginator->total() }}
        </div>
    </nav>
@endif
