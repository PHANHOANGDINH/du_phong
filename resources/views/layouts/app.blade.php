{{-- Layout dùng chung toàn hệ thống (Bootstrap 5 + Icons) --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản lý Sách')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --shop-bg: #f4f5f7;
            --shop-border: #e5e7eb;
            --shop-dark: #1f2937;     /* nút chính */
            --shop-price: #c92127;    /* giá bán */
            --shop-radius: 10px;
        }
        body { background: var(--shop-bg); color: #333; }

        /* Nội dung canh giữa, rộng tối đa 1200px */
        .page { max-width: 1200px; margin: 0 auto; padding: 0 16px 32px; }

        /* Header */
        .shop-header { background: #fff; border-bottom: 1px solid var(--shop-border); }
        .shop-header .inner { max-width: 1200px; margin: 0 auto; padding: 0 16px; }
        .shop-header .brand { color: var(--shop-price); font-weight: 700; font-size: 1.15rem; text-decoration: none; }
        .shop-header .nav-link { color: #4b5563; font-weight: 500; padding: 1rem .9rem; border-bottom: 2px solid transparent; }
        .shop-header .nav-link:hover { color: #111; }
        .shop-header .nav-link.active { color: var(--shop-price); border-bottom-color: var(--shop-price); }

        /* Khối trắng */
        .shop-box { background: #fff; border: 1px solid var(--shop-border); border-radius: var(--shop-radius); }
        .page-title { font-size: 1.35rem; font-weight: 700; margin: 0; }
        .btn-shop { background: var(--shop-dark); color: #fff; }
        .btn-shop:hover { background: #111827; color: #fff; }
        .text-price { color: var(--shop-price); }

        /* Thẻ sách */
        .book-card { background: #fff; border: 1px solid var(--shop-border); border-radius: var(--shop-radius); padding: 10px;
                     height: 100%; display: flex; flex-direction: column; position: relative; transition: border-color .15s; }
        .book-card:hover { border-color: #c7cbd1; }
        .book-card .book-title { font-size: .875rem; color: #222; line-height: 1.35; height: 2.7em; overflow: hidden;
                                 display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin: 10px 0 6px; }
        .book-card .book-price { color: var(--shop-price); font-weight: 700; white-space: nowrap; }
        .book-card .badge { white-space: nowrap; font-weight: 500; }
        .book-card .card-actions { position: absolute; top: 16px; right: 16px; display: flex; gap: 4px; opacity: 0; transition: opacity .15s; }
        .book-card:hover .card-actions, .book-card:focus-within .card-actions { opacity: 1; }
        .book-card .card-actions .btn { width: 30px; height: 30px; padding: 0; line-height: 28px; border: 1px solid var(--shop-border); }

        /* Ảnh bìa / bìa tạm */
        .book-cover { aspect-ratio: 3 / 4; width: 100%; border-radius: 6px; overflow: hidden; background: #f3f4f6;
                      display: flex; align-items: center; justify-content: center; }
        .book-cover img { width: 100%; height: 100%; object-fit: contain; }
        .cover-placeholder { width: 100%; height: 100%; padding: 14% 12%; display: flex; flex-direction: column; justify-content: space-between; }
        .cover-placeholder .ph-title { font-weight: 600; line-height: 1.3; font-size: clamp(.8rem, 1.3vw, 1rem);
                                       display: -webkit-box; -webkit-line-clamp: 5; -webkit-box-orient: vertical; overflow: hidden; }
        .cover-placeholder .ph-author { font-size: .7rem; opacity: .75; }
        .cover-sm .cover-placeholder span { display: none; } /* bìa tạm cỡ nhỏ: chỉ giữ màu */

        /* Bộ lọc dạng Accordion */
        .filter-accordion .accordion-button { font-size: .9rem; font-weight: 600; padding: .8rem 1rem; color: #222; }
        .filter-accordion .accordion-button:not(.collapsed) { background: #fff; color: #222; box-shadow: none; }
        .filter-accordion .accordion-button:focus { box-shadow: none; }
        .filter-accordion .accordion-body { padding: 0 1rem 1rem; }
        .filter-accordion .form-check { font-size: .875rem; margin-bottom: 4px; }
        .filter-scroll { max-height: 220px; overflow-y: auto; padding-right: 4px; }

        /* Danh sách đơn giản (thể loại, tác giả, NXB, thùng rác) */
        .simple-list .item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-top: 1px solid var(--shop-border);
                             color: inherit; text-decoration: none; }
        .simple-list .item:first-child { border-top: 0; }
        a.item:hover { background: #f9fafb; }
        .simple-list .item .meta { font-size: .8rem; color: #6b7280; }
        .min-w-0 { min-width: 0; } /* cho phép text-truncate hoạt động trong flex */
        .count-pill { font-size: .75rem; background: #f3f4f6; color: #374151; border-radius: 999px; padding: 2px 10px; white-space: nowrap; }
        .tile { display: block; padding: 18px; color: inherit; text-decoration: none; height: 100%; }
        .tile:hover { border-color: #c7cbd1; }

        /* Dải sách liên quan */
        /* Khung chọn trong form (tác giả, thể loại): cùng chiều cao, danh sách bên trong cuộn khi dài */
        .pick-box { height: 340px; display: flex; flex-direction: column; }
        .pick-box .pick-list { flex: 1 1 auto; min-height: 0; overflow-y: auto; padding-right: 4px; }
    </style>
</head>
<body>
    @php
        $navItems = [
            ['books.index', 'books.*', 'Sách'],
            ['categories.index', 'categories.*', 'Thể loại'],
            ['authors.index', 'authors.*', 'Tác giả'],
            ['publishers.index', 'publishers.*', 'Nhà xuất bản'],
            ['books.trash', 'books.trash', 'Thùng rác'],
        ];
    @endphp
    <header class="shop-header mb-4">
        <div class="inner d-flex flex-wrap align-items-center justify-content-between">
            <a class="brand py-3" href="{{ route('books.index') }}"><i class="bi bi-book-half"></i> Nhà Sách</a>
            <nav class="nav justify-content-center flex-grow-1">
                @foreach ($navItems as [$route, $pattern, $label])
                    @php $active = request()->routeIs($pattern) && ($pattern !== 'books.*' || ! request()->routeIs('books.trash')); @endphp
                    <a class="nav-link @if($active) active @endif" href="{{ route($route) }}">{{ $label }}</a>
                @endforeach
            </nav>
            <span class="d-none d-md-block" style="width: 110px"></span>{{-- cân với logo để menu nằm giữa --}}
        </div>
    </header>

    <div class="page">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ô tìm nhanh trong danh sách tick (tác giả...): chỉ ẩn/hiện dòng theo chữ gõ vào, không gửi lên server
        function filterChecklist(input, listId) {
            const keyword = input.value.trim().toLowerCase();
            document.querySelectorAll('#' + listId + ' .form-check').forEach((row) => {
                row.style.display = row.dataset.name.includes(keyword) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
