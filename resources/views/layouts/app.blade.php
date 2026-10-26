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
            --shop-bg: #f0f0f0;
            --shop-dark: #1f2d3d;      /* nút chính, mục đang chọn */
            --shop-price: #c92127;     /* giá bán */
            --shop-muted: #7a7e83;
            --shop-radius: 8px;
        }
        body { background: var(--shop-bg); color: #333; }

        /* Header */
        .shop-header { background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
        .shop-header .navbar-brand { color: var(--shop-price); font-weight: 700; letter-spacing: .3px; }
        .shop-header .nav-link { color: #444; font-weight: 500; }
        .shop-header .nav-link.active, .shop-header .nav-link:hover { color: var(--shop-price); }

        /* Khối trắng bo góc */
        .shop-box { background: #fff; border-radius: var(--shop-radius); }
        .btn-shop { background: var(--shop-dark); color: #fff; }
        .btn-shop:hover { background: #111c27; color: #fff; }
        .text-price { color: var(--shop-price); }

        /* Thẻ sách trong lưới */
        .book-card { background: #fff; border-radius: var(--shop-radius); padding: 12px; height: 100%;
                     display: flex; flex-direction: column; transition: box-shadow .15s, transform .15s; position: relative; }
        .book-card:hover { box-shadow: 0 6px 18px rgba(0,0,0,.10); transform: translateY(-2px); }
        .book-card .book-title { font-size: .875rem; color: #333; line-height: 1.35; height: 2.7em; overflow: hidden;
                                 display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin: 10px 0 6px; }
        .book-card .book-price { color: var(--shop-price); font-weight: 700; font-size: 1.05rem; white-space: nowrap; }
        .book-card .badge { white-space: nowrap; }
        .book-card .card-actions { position: absolute; top: 18px; right: 18px; display: flex; gap: 4px; opacity: 0; transition: opacity .15s; }
        .book-card:hover .card-actions, .book-card:focus-within .card-actions { opacity: 1; }
        .book-card .card-actions .btn { width: 30px; height: 30px; padding: 0; line-height: 28px; box-shadow: 0 1px 4px rgba(0,0,0,.2); }

        /* Ảnh bìa / bìa tạm khi chưa có ảnh */
        .book-cover { aspect-ratio: 3 / 4; width: 100%; border-radius: 4px; overflow: hidden; background: #f6f6f6;
                      display: flex; align-items: center; justify-content: center; }
        .book-cover img { width: 100%; height: 100%; object-fit: contain; }
        .cover-placeholder { width: 100%; height: 100%; padding: 14% 12%; color: #fff; display: flex; flex-direction: column;
                             justify-content: space-between; text-align: left; }
        .cover-placeholder .ph-title { font-weight: 700; line-height: 1.25; font-size: clamp(.8rem, 1.4vw, 1.05rem);
                                       display: -webkit-box; -webkit-line-clamp: 5; -webkit-box-orient: vertical; overflow: hidden; }
        .cover-placeholder .ph-author { font-size: .7rem; opacity: .85; }

        /* Thanh lọc bên trái */
        .filter-title { font-weight: 700; font-size: .9rem; margin-bottom: .5rem; }
        .filter-list { list-style: none; padding: 0; margin: 0 0 1rem; }
        .filter-list a { display: block; padding: 5px 8px; border-radius: 4px; color: #444; font-size: .875rem; text-decoration: none; }
        .filter-list a:hover { background: #f3f4f6; }
        .filter-list a.active { background: var(--shop-dark); color: #fff; }
        .filter-radio a { position: relative; padding-left: 26px; }
        .filter-radio a::before { content: ""; position: absolute; left: 8px; top: 50%; width: 12px; height: 12px; margin-top: -6px;
                                  border: 1.5px solid #9aa0a6; border-radius: 50%; }
        .filter-radio a.active { background: transparent; color: var(--shop-dark); font-weight: 600; }
        .filter-radio a.active::before { border: 4px solid #2f6fdc; }

        /* Dải sách liên quan */
        .related-row { display: grid; grid-auto-flow: column; grid-auto-columns: minmax(180px, 1fr); gap: 12px; overflow-x: auto; padding-bottom: 6px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md shop-header mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('books.index') }}"><i class="bi bi-book-half"></i> Nhà Sách</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('books.*') && ! request()->routeIs('books.trash')) active @endif" href="{{ route('books.index') }}">Sách</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('categories.*')) active @endif" href="{{ route('categories.index') }}">Thể loại</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('authors.*')) active @endif" href="{{ route('authors.index') }}">Tác giả</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('publishers.*')) active @endif" href="{{ route('publishers.index') }}">Nhà xuất bản</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('books.trash')) active @endif" href="{{ route('books.trash') }}"><i class="bi bi-trash3"></i> Thùng rác</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
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
    </div>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
