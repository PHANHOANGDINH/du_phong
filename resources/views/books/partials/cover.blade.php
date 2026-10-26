{{-- Ảnh bìa: nhận $book. Chưa có ảnh thì vẽ bìa tạm có màu (cố định theo id) kèm tên sách --}}
<div class="book-cover">
    @if ($book->cover_url)
        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" loading="lazy">
    @else
        @php $hue = ($book->id * 47) % 360; @endphp
        <div class="cover-placeholder" style="background: linear-gradient(150deg, hsl({{ $hue }} 55% 42%), hsl({{ ($hue + 40) % 360 }} 60% 28%))">
            <span class="ph-title">{{ $book->title }}</span>
            <span class="ph-author">{{ $book->authors->pluck('name')->first() }}</span>
        </div>
    @endif
</div>
