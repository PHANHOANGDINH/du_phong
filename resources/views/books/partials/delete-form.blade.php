{{-- Form xóa mềm (ẩn) cho nút Xóa trên thẻ sách. Nhận $book; đặt NGOÀI các form khác --}}
<form id="delete-book-{{ $book->id }}" action="{{ route('books.destroy', $book) }}" method="POST" class="d-none"
      onsubmit="return confirm(@js('Chuyển sách "'.$book->title.'" vào thùng rác?'))">
    @csrf
    @method('DELETE')
</form>
