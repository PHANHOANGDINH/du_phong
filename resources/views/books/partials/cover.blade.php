{{-- Ảnh bìa: nhận $book. Chưa có ảnh thì hiện bìa tạm màu nhạt (cố định theo id) kèm tên sách --}}
<div class="book-cover">
    @if ($book->cover_url)
        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" loading="lazy">
    @else
        @php $hue = ($book->id * 47) % 360; @endphp
        <div class="cover-placeholder" style="background: hsl({{ $hue }} 45% 92%); color: hsl({{ $hue }} 45% 25%); border-left: 6px solid hsl({{ $hue }} 45% 55%)">
            <span class="ph-title">{{ $book->title }}</span>
            <span class="ph-author">{{ $book->authors->pluck('name')->first() }}</span>
        </div>
    @endif
</div>
