<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 20 đầu sách mẫu bổ sung (chạy riêng: php artisan db:seed --class=SampleBookSeeder).
 * Chạy lại nhiều lần không bị trùng: sách nhận diện theo ISBN, tác giả / NXB / thể loại chưa có mới tạo.
 * ISBN dải 8935000000xxx là mã giả dùng cho dữ liệu mẫu.
 */
class SampleBookSeeder extends Seeder
{
    /** Phần đầu của mã ISBN giả, nối thêm số thứ tự 3 chữ số. */
    protected const ISBN_PREFIX = '8935000000';

    public function run(): void
    {
        DB::transaction(function () {
            foreach ($this->books() as $i => $item) {
                $book = Book::withTrashed()->updateOrCreate(
                    ['isbn' => sprintf('%s%03d', static::ISBN_PREFIX, $i + 1)],
                    [
                        'title' => $item['title'],
                        'published_year' => $item['year'],
                        'price' => $item['price'],
                        'stock_quantity' => $item['stock'],
                        'page_count' => $item['pages'],
                        'dimensions' => $item['size'],
                        'description' => $item['description'],
                        'publisher_id' => Publisher::firstOrCreate(['name' => $item['publisher']])->id,
                    ],
                );

                // Sách mẫu lỡ bị xóa mềm thì đưa trở lại danh sách
                if ($book->trashed()) {
                    $book->restore();
                }

                $book->authors()->sync(
                    collect($item['authors'])->map(fn ($name) => Author::firstOrCreate(['name' => $name])->id)
                );
                $book->categories()->sync(
                    collect((array) ($item['categories'] ?? $item['category']))
                        ->map(fn ($name) => Category::firstOrCreate(['name' => $name])->id)
                );
            }
        });
    }

    /**
     * Mỗi sách: title, authors[], category (hoặc categories[]), publisher, year, price, stock, pages, size, description.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function books(): array
    {
        return [
            ['title' => 'Dế Mèn Phiêu Lưu Ký', 'authors' => ['Tô Hoài'], 'category' => 'Thiếu Nhi', 'publisher' => 'NXB Kim Đồng', 'year' => 2023, 'price' => 45000, 'stock' => 35, 'pages' => 144, 'size' => '13 x 20.5 cm',
                'description' => 'Cuộc phiêu lưu của chú Dế Mèn qua thế giới loài vật, tác phẩm thiếu nhi kinh điển của văn học Việt Nam.'],
            ['title' => 'Cho Tôi Xin Một Vé Đi Tuổi Thơ', 'authors' => ['Nguyễn Nhật Ánh'], 'category' => 'Văn Học', 'publisher' => 'NXB Trẻ', 'year' => 2023, 'price' => 85000, 'stock' => 42, 'pages' => 220, 'size' => '13 x 20 cm',
                'description' => 'Những trò chơi và suy nghĩ hồn nhiên của tuổi thơ qua góc nhìn của cậu bé Mũi.'],
            ['title' => 'Mắt Biếc', 'authors' => ['Nguyễn Nhật Ánh'], 'category' => 'Văn Học', 'publisher' => 'NXB Trẻ', 'year' => 2022, 'price' => 110000, 'stock' => 3, 'pages' => 300, 'size' => '13 x 20 cm',
                'description' => 'Mối tình đơn phương của Ngạn dành cho Hà Lan, cô bạn có đôi mắt biếc ở làng Đo Đo.'],
            ['title' => 'Tôi Thấy Hoa Vàng Trên Cỏ Xanh', 'authors' => ['Nguyễn Nhật Ánh'], 'category' => 'Văn Học', 'publisher' => 'NXB Trẻ', 'year' => 2023, 'price' => 125000, 'stock' => 0, 'pages' => 378, 'size' => '13 x 20 cm',
                'description' => 'Câu chuyện về hai anh em Thiều và Tường cùng những ngày tháng tuổi thơ ở một làng quê nghèo.'],
            ['title' => 'Số Đỏ', 'authors' => ['Vũ Trọng Phụng'], 'category' => 'Văn Học', 'publisher' => 'Văn học', 'year' => 2021, 'price' => 79000, 'stock' => 18, 'pages' => 260, 'size' => '14 x 20.5 cm',
                'description' => 'Tiểu thuyết trào phúng về con đường "đổi đời" của Xuân Tóc Đỏ trong xã hội thành thị thời Pháp thuộc.'],
            ['title' => 'Chí Phèo', 'authors' => ['Nam Cao'], 'category' => 'Văn Học', 'publisher' => 'NXB Hội Nhà Văn', 'year' => 2020, 'price' => 55000, 'stock' => 25, 'pages' => 180, 'size' => '13 x 20.5 cm',
                'description' => 'Tuyển tập truyện ngắn của Nam Cao, nổi bật với bi kịch bị cự tuyệt quyền làm người của Chí Phèo.'],
            ['title' => 'Vợ Nhặt', 'authors' => ['Kim Lân'], 'category' => 'Văn Học', 'publisher' => 'NXB Hội Nhà Văn', 'year' => 2020, 'price' => 48000, 'stock' => 2, 'pages' => 150, 'size' => '13 x 20.5 cm',
                'description' => 'Tuyển tập truyện ngắn Kim Lân, khắc họa khát vọng sống của người nông dân trong nạn đói năm 1945.'],
            ['title' => 'Nỗi Buồn Chiến Tranh', 'authors' => ['Bảo Ninh'], 'category' => 'Văn Học', 'publisher' => 'NXB Trẻ', 'year' => 2022, 'price' => 135000, 'stock' => 12, 'pages' => 340, 'size' => '14 x 20.5 cm',
                'description' => 'Những ký ức ám ảnh về chiến tranh và tình yêu qua dòng hồi tưởng của người lính Kiên.'],
            ['title' => 'Cánh Đồng Bất Tận', 'authors' => ['Nguyễn Ngọc Tư'], 'category' => 'Văn Học', 'publisher' => 'NXB Trẻ', 'year' => 2021, 'price' => 90000, 'stock' => 20, 'pages' => 220, 'size' => '13 x 20 cm',
                'description' => 'Tập truyện về những phận người lênh đênh trên những cánh đồng miền Tây Nam Bộ.'],
            ['title' => 'Đắc Nhân Tâm', 'authors' => ['Dale Carnegie'], 'category' => 'Tâm Lý - Kĩ Năng Sống', 'publisher' => 'NXB Tổng Hợp TPHCM', 'year' => 2023, 'price' => 86000, 'stock' => 55, 'pages' => 320, 'size' => '14.5 x 20.5 cm',
                'description' => 'Cuốn sách kinh điển về nghệ thuật giao tiếp, thu phục lòng người và tạo dựng các mối quan hệ.'],
            ['title' => 'Tuổi Trẻ Đáng Giá Bao Nhiêu', 'authors' => ['Rosie Nguyễn'], 'category' => 'Tâm Lý - Kĩ Năng Sống', 'publisher' => 'NXB Hội Nhà Văn', 'year' => 2022, 'price' => 90000, 'stock' => 30, 'pages' => 285, 'size' => '14 x 20.5 cm',
                'description' => 'Những chia sẻ về học tập, trải nghiệm và định hướng cho người trẻ.'],
            ['title' => 'Nhà Lãnh Đạo Không Chức Danh', 'authors' => ['Robin Sharma'], 'category' => 'Kinh Tế', 'publisher' => 'NXB Trẻ', 'year' => 2021, 'price' => 92000, 'stock' => 16, 'pages' => 280, 'size' => '14 x 20.5 cm',
                'description' => 'Bài học về tinh thần lãnh đạo dành cho mọi người, dù ở bất kỳ vị trí nào.'],
            ['title' => 'Cha Giàu Cha Nghèo', 'authors' => ['Robert T. Kiyosaki'], 'category' => 'Kinh Tế', 'publisher' => 'NXB Trẻ', 'year' => 2022, 'price' => 115000, 'stock' => 27, 'pages' => 380, 'size' => '14 x 20.5 cm',
                'description' => 'Những bài học về tư duy tài chính cá nhân qua câu chuyện của hai người cha.'],
            ['title' => 'Nghĩ Giàu Làm Giàu', 'authors' => ['Napoleon Hill'], 'category' => 'Kinh Tế', 'publisher' => 'NXB Tổng Hợp TPHCM', 'year' => 2023, 'price' => 110000, 'stock' => 14, 'pages' => 400, 'size' => '14.5 x 20.5 cm',
                'description' => 'Mười ba nguyên tắc thành công được đúc kết từ cuộc đời của những người giàu có.'],
            ['title' => 'Doraemon - Tập 1', 'authors' => ['Fujiko F Fujio'], 'category' => 'Thiếu Nhi', 'publisher' => 'NXB Kim Đồng', 'year' => 2023, 'price' => 25000, 'stock' => 60, 'pages' => 192, 'size' => '11.3 x 17.6 cm',
                'description' => 'Chú mèo máy đến từ thế kỷ 22 cùng những bảo bối thần kỳ giúp đỡ cậu bé Nobita.'],
            ['title' => 'Thám Tử Lừng Danh Conan - Tập 1', 'authors' => ['Gosho Aoyama'], 'category' => 'Thiếu Nhi', 'publisher' => 'NXB Kim Đồng', 'year' => 2023, 'price' => 25000, 'stock' => 4, 'pages' => 184, 'size' => '11.3 x 17.6 cm',
                'description' => 'Thám tử trung học Kudo Shinichi bị teo nhỏ và bắt đầu cuộc đời mới với cái tên Edogawa Conan.'],
            ['title' => 'Kính Vạn Hoa - Tập 1', 'authors' => ['Nguyễn Nhật Ánh'], 'category' => 'Thiếu Nhi', 'publisher' => 'NXB Kim Đồng', 'year' => 2022, 'price' => 65000, 'stock' => 22, 'pages' => 260, 'size' => '13 x 19 cm',
                'description' => 'Những câu chuyện học trò vui nhộn của bộ ba Quý ròm, Tiểu Long và nhỏ Hạnh.'],
            ['title' => 'English Grammar in Use', 'authors' => ['Raymond Murphy'], 'category' => 'Sách Học Ngoại Ngữ', 'publisher' => 'Thế Giới', 'year' => 2021, 'price' => 185000, 'stock' => 9, 'pages' => 390, 'size' => '19.5 x 26 cm',
                'description' => 'Sách ngữ pháp tiếng Anh trình độ trung cấp, có giải thích và bài tập kèm đáp án.'],
            ['title' => '3000 Từ Vựng Tiếng Anh Thông Dụng Nhất', 'authors' => ['The Windy'], 'category' => 'Sách Học Ngoại Ngữ', 'publisher' => 'NXB Đại học Quốc gia Hà Nội', 'year' => 2022, 'price' => 69000, 'stock' => 33, 'pages' => 240, 'size' => '13 x 19 cm',
                'description' => 'Tổng hợp 3000 từ vựng tiếng Anh thông dụng kèm phiên âm và ví dụ.'],
            ['title' => 'Toán 12 - Tập Một', 'authors' => ['Bộ Giáo dục và Đào tạo'], 'category' => 'Sách Giáo Khoa - Tham Khảo', 'publisher' => 'NXB Giáo Dục', 'year' => 2024, 'price' => 28000, 'stock' => 0, 'pages' => 160, 'size' => '19 x 26.5 cm',
                'description' => 'Sách giáo khoa Toán lớp 12, tập một.'],
        ];
    }
}
