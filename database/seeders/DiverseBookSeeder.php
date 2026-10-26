<?php

namespace Database\Seeders;

/**
 * 30 đầu sách đa dạng: tên sách giữ nguyên ngôn ngữ gốc (Anh, Pháp, Đức, Tây Ban Nha, Nga,
 * Nhật, Trung, Hàn, Việt), thêm thể loại mới và nhiều sách đồng tác giả.
 * Chạy riêng: php artisan db:seed --class=DiverseBookSeeder (chạy lại không bị trùng).
 */
class DiverseBookSeeder extends SampleBookSeeder
{
    protected const ISBN_PREFIX = '8935000001';

    protected function books(): array
    {
        return [
            // ---- Tiếng Anh ----
            ['title' => '1984', 'authors' => ['George Orwell'], 'categories' => ['Tiểu Thuyết Nước Ngoài'], 'publisher' => 'Penguin Books', 'year' => 2021, 'price' => 210000, 'stock' => 14, 'pages' => 328, 'size' => '12.9 x 19.8 cm',
                'description' => '[Tiếng Anh] Tiểu thuyết phản địa đàng về một xã hội bị giám sát toàn diện bởi "Anh Cả".'],
            ['title' => "Harry Potter and the Philosopher's Stone", 'authors' => ['J.K. Rowling'], 'categories' => ['Tiểu Thuyết Nước Ngoài', 'Thiếu Nhi'], 'publisher' => 'Bloomsbury', 'year' => 2014, 'price' => 245000, 'stock' => 26, 'pages' => 352, 'size' => '13 x 19.8 cm',
                'description' => '[Tiếng Anh] Năm học đầu tiên của cậu bé phù thủy Harry Potter tại trường Hogwarts.'],
            ['title' => 'Good Omens', 'authors' => ['Terry Pratchett', 'Neil Gaiman'], 'categories' => ['Tiểu Thuyết Nước Ngoài'], 'publisher' => 'Penguin Books', 'year' => 2019, 'price' => 265000, 'stock' => 4, 'pages' => 432, 'size' => '12.9 x 19.8 cm',
                'description' => '[Tiếng Anh] Một thiên thần và một ác quỷ hợp sức ngăn ngày tận thế. Sách đồng tác giả.'],
            ['title' => 'The Pragmatic Programmer', 'authors' => ['David Thomas', 'Andrew Hunt'], 'categories' => ['Khoa Học - Công Nghệ'], 'publisher' => 'Addison-Wesley', 'year' => 2019, 'price' => 890000, 'stock' => 6, 'pages' => 352, 'size' => '18.7 x 23.1 cm',
                'description' => '[Tiếng Anh] Những nguyên tắc thực hành giúp lập trình viên làm việc hiệu quả và chuyên nghiệp.'],
            ['title' => 'Clean Code', 'authors' => ['Robert C. Martin'], 'categories' => ['Khoa Học - Công Nghệ'], 'publisher' => 'Prentice Hall', 'year' => 2008, 'price' => 950000, 'stock' => 0, 'pages' => 464, 'size' => '17.8 x 23.3 cm',
                'description' => '[Tiếng Anh] Cách viết mã nguồn sạch, dễ đọc và dễ bảo trì.'],
            ['title' => 'Design Patterns: Elements of Reusable Object-Oriented Software', 'authors' => ['Erich Gamma', 'Richard Helm', 'Ralph Johnson', 'John Vlissides'], 'categories' => ['Khoa Học - Công Nghệ'], 'publisher' => 'Addison-Wesley', 'year' => 1994, 'price' => 1150000, 'stock' => 3, 'pages' => 395, 'size' => '19.1 x 24.1 cm',
                'description' => '[Tiếng Anh] 23 mẫu thiết kế hướng đối tượng kinh điển của nhóm "Gang of Four" (4 tác giả).'],
            ['title' => 'Sapiens: A Brief History of Humankind', 'authors' => ['Yuval Noah Harari'], 'categories' => ['Lịch Sử', 'Khoa Học - Công Nghệ'], 'publisher' => 'Harper', 'year' => 2015, 'price' => 320000, 'stock' => 19, 'pages' => 464, 'size' => '13.5 x 20.3 cm',
                'description' => '[Tiếng Anh] Lược sử loài người từ thời đồ đá đến cách mạng khoa học.'],
            ['title' => 'Freakonomics', 'authors' => ['Steven D. Levitt', 'Stephen J. Dubner'], 'categories' => ['Kinh Tế'], 'publisher' => 'William Morrow', 'year' => 2009, 'price' => 280000, 'stock' => 11, 'pages' => 336, 'size' => '13.5 x 20.3 cm',
                'description' => '[Tiếng Anh] Kinh tế học lý giải những hiện tượng đời thường bất ngờ. Sách đồng tác giả.'],

            // ---- Tiếng Pháp ----
            ['title' => 'Le Petit Prince', 'authors' => ['Antoine de Saint-Exupéry'], 'categories' => ['Tiểu Thuyết Nước Ngoài', 'Thiếu Nhi'], 'publisher' => 'Gallimard', 'year' => 2007, 'price' => 175000, 'stock' => 30, 'pages' => 96, 'size' => '11 x 18 cm',
                'description' => '[Tiếng Pháp] Câu chuyện về hoàng tử bé đến từ tiểu tinh cầu B612.'],
            ['title' => "L'Étranger", 'authors' => ['Albert Camus'], 'categories' => ['Tiểu Thuyết Nước Ngoài', 'Triết Học'], 'publisher' => 'Gallimard', 'year' => 2005, 'price' => 160000, 'stock' => 8, 'pages' => 192, 'size' => '11 x 18 cm',
                'description' => '[Tiếng Pháp] Tiểu thuyết tiêu biểu cho triết lý phi lý của Camus qua nhân vật Meursault.'],
            ['title' => 'Les Misérables', 'authors' => ['Victor Hugo'], 'categories' => ['Tiểu Thuyết Nước Ngoài'], 'publisher' => 'Le Livre de Poche', 'year' => 2018, 'price' => 390000, 'stock' => 2, 'pages' => 1664, 'size' => '11 x 17.8 cm',
                'description' => '[Tiếng Pháp] Số phận Jean Valjean và những người khốn khổ trong nước Pháp thế kỷ 19.'],

            // ---- Tiếng Đức ----
            ['title' => 'Die Verwandlung', 'authors' => ['Franz Kafka'], 'categories' => ['Tiểu Thuyết Nước Ngoài'], 'publisher' => 'Reclam', 'year' => 2016, 'price' => 95000, 'stock' => 12, 'pages' => 80, 'size' => '9.6 x 14.8 cm',
                'description' => '[Tiếng Đức] Gregor Samsa thức dậy và thấy mình biến thành một con côn trùng khổng lồ.'],
            ['title' => 'Also sprach Zarathustra', 'authors' => ['Friedrich Nietzsche'], 'categories' => ['Triết Học'], 'publisher' => 'Reclam', 'year' => 2016, 'price' => 150000, 'stock' => 5, 'pages' => 400, 'size' => '9.6 x 14.8 cm',
                'description' => '[Tiếng Đức] Tác phẩm triết học dưới dạng thơ văn xuôi về tư tưởng "siêu nhân".'],

            // ---- Tiếng Tây Ban Nha ----
            ['title' => 'Cien años de soledad', 'authors' => ['Gabriel García Márquez'], 'categories' => ['Tiểu Thuyết Nước Ngoài'], 'publisher' => 'Debolsillo', 'year' => 2017, 'price' => 290000, 'stock' => 9, 'pages' => 496, 'size' => '12.5 x 19 cm',
                'description' => '[Tiếng Tây Ban Nha] Bảy thế hệ dòng họ Buendía ở thị trấn Macondo, đỉnh cao của hiện thực huyền ảo.'],
            ['title' => 'Don Quijote de la Mancha', 'authors' => ['Miguel de Cervantes'], 'categories' => ['Tiểu Thuyết Nước Ngoài'], 'publisher' => 'Alfaguara', 'year' => 2015, 'price' => 420000, 'stock' => 0, 'pages' => 1376, 'size' => '15.5 x 23.5 cm',
                'description' => '[Tiếng Tây Ban Nha] Cuộc phiêu lưu của chàng hiệp sĩ mộng mơ Don Quijote và Sancho Panza.'],

            // ---- Tiếng Nga ----
            ['title' => 'Преступление и наказание', 'authors' => ['Фёдор Достоевский'], 'categories' => ['Tiểu Thuyết Nước Ngoài', 'Trinh Thám'], 'publisher' => 'Азбука', 'year' => 2020, 'price' => 230000, 'stock' => 7, 'pages' => 608, 'size' => '11.5 x 18 cm',
                'description' => '[Tiếng Nga] "Tội ác và hình phạt" - cuộc giằng xé nội tâm của Raskolnikov sau một vụ án mạng.'],
            ['title' => 'Война и мир', 'authors' => ['Лев Толстой'], 'categories' => ['Tiểu Thuyết Nước Ngoài', 'Lịch Sử'], 'publisher' => 'Эксмо', 'year' => 2019, 'price' => 480000, 'stock' => 4, 'pages' => 1360, 'size' => '13 x 20.5 cm',
                'description' => '[Tiếng Nga] "Chiến tranh và hòa bình" - xã hội Nga trong thời kỳ chiến tranh với Napoléon.'],

            // ---- Tiếng Nhật ----
            ['title' => 'ノルウェイの森', 'authors' => ['村上春樹'], 'categories' => ['Tiểu Thuyết Nước Ngoài'], 'publisher' => '講談社', 'year' => 2004, 'price' => 185000, 'stock' => 13, 'pages' => 304, 'size' => '10.5 x 14.8 cm',
                'description' => '[Tiếng Nhật] "Rừng Na Uy" của Murakami Haruki - tình yêu và mất mát của tuổi trẻ ở Tokyo.'],
            ['title' => '容疑者Xの献身', 'authors' => ['東野圭吾'], 'categories' => ['Trinh Thám', 'Tiểu Thuyết Nước Ngoài'], 'publisher' => '文藝春秋', 'year' => 2008, 'price' => 170000, 'stock' => 10, 'pages' => 400, 'size' => '10.5 x 14.8 cm',
                'description' => '[Tiếng Nhật] "Phía sau nghi can X" của Higashino Keigo - cuộc đấu trí giữa hai thiên tài.'],
            ['title' => 'ONE PIECE 1', 'authors' => ['尾田栄一郎'], 'categories' => ['Truyện Tranh - Manga'], 'publisher' => '集英社', 'year' => 1997, 'price' => 95000, 'stock' => 40, 'pages' => 208, 'size' => '11.3 x 17.6 cm',
                'description' => '[Tiếng Nhật] Luffy Mũ Rơm bắt đầu hành trình trở thành Vua Hải Tặc.'],
            ['title' => '名探偵コナン 1', 'authors' => ['青山剛昌'], 'categories' => ['Truyện Tranh - Manga', 'Trinh Thám'], 'publisher' => '小学館', 'year' => 1994, 'price' => 95000, 'stock' => 1, 'pages' => 184, 'size' => '11.3 x 17.6 cm',
                'description' => '[Tiếng Nhật] Tập đầu tiên của "Thám tử lừng danh Conan" bản gốc tiếng Nhật.'],

            // ---- Tiếng Trung ----
            ['title' => '三体', 'authors' => ['刘慈欣'], 'categories' => ['Tiểu Thuyết Nước Ngoài', 'Khoa Học - Công Nghệ'], 'publisher' => '重庆出版社', 'year' => 2008, 'price' => 210000, 'stock' => 16, 'pages' => 302, 'size' => '15 x 21 cm',
                'description' => '[Tiếng Trung] "Tam thể" của Lưu Từ Hân - cuộc tiếp xúc đầu tiên giữa loài người và nền văn minh ngoài Trái Đất.'],
            ['title' => '活着', 'authors' => ['余华'], 'categories' => ['Tiểu Thuyết Nước Ngoài'], 'publisher' => '作家出版社', 'year' => 2012, 'price' => 140000, 'stock' => 0, 'pages' => 191, 'size' => '14 x 21 cm',
                'description' => '[Tiếng Trung] "Phải sống" của Dư Hoa - cuộc đời nhiều mất mát của lão nông Phúc Quý.'],
            ['title' => '论语', 'authors' => ['孔子'], 'categories' => ['Triết Học'], 'publisher' => '中华书局', 'year' => 2006, 'price' => 120000, 'stock' => 6, 'pages' => 248, 'size' => '14 x 20.3 cm',
                'description' => '[Tiếng Trung] "Luận ngữ" - những lời dạy của Khổng Tử do học trò ghi chép lại.'],

            // ---- Tiếng Hàn ----
            ['title' => '채식주의자', 'authors' => ['한강'], 'categories' => ['Tiểu Thuyết Nước Ngoài'], 'publisher' => '창비', 'year' => 2007, 'price' => 195000, 'stock' => 21, 'pages' => 247, 'size' => '13.5 x 19.5 cm',
                'description' => '[Tiếng Hàn] "Người ăn chay" của Han Kang - tác giả đoạt giải Nobel Văn học 2024.'],
            ['title' => '82년생 김지영', 'authors' => ['조남주'], 'categories' => ['Tiểu Thuyết Nước Ngoài'], 'publisher' => '민음사', 'year' => 2016, 'price' => 165000, 'stock' => 3, 'pages' => 192, 'size' => '13 x 19.5 cm',
                'description' => '[Tiếng Hàn] "Kim Ji Young, sinh năm 1982" - cuộc sống của một người phụ nữ Hàn Quốc bình thường.'],

            // ---- Tiếng Việt ----
            ['title' => 'Truyện Kiều', 'authors' => ['Nguyễn Du'], 'categories' => ['Văn Học'], 'publisher' => 'Văn học', 'year' => 2020, 'price' => 99000, 'stock' => 24, 'pages' => 320, 'size' => '14.5 x 20.5 cm',
                'description' => '[Tiếng Việt] Kiệt tác thơ lục bát về cuộc đời mười lăm năm lưu lạc của Thúy Kiều.'],
            ['title' => 'Việt Nam Sử Lược', 'authors' => ['Trần Trọng Kim'], 'categories' => ['Lịch Sử'], 'publisher' => 'NXB Hội Nhà Văn', 'year' => 2021, 'price' => 185000, 'stock' => 15, 'pages' => 640, 'size' => '16 x 24 cm',
                'description' => '[Tiếng Việt] Bộ thông sử Việt Nam viết bằng chữ quốc ngữ đầu tiên.'],
            ['title' => 'Đại Việt Sử Ký Toàn Thư', 'authors' => ['Lê Văn Hưu', 'Phan Phu Tiên', 'Ngô Sĩ Liên'], 'categories' => ['Lịch Sử'], 'publisher' => 'Văn học', 'year' => 2022, 'price' => 650000, 'stock' => 2, 'pages' => 1200, 'size' => '16 x 24 cm',
                'description' => '[Tiếng Việt] Bộ quốc sử do nhiều sử gia biên soạn qua các triều đại (3 tác giả).'],
            ['title' => 'Lược Sử Thời Gian', 'authors' => ['Stephen Hawking'], 'categories' => ['Khoa Học - Công Nghệ'], 'publisher' => 'NXB Trẻ', 'year' => 2023, 'price' => 125000, 'stock' => 18, 'pages' => 272, 'size' => '14 x 20.5 cm',
                'description' => '[Tiếng Việt - bản dịch] Từ Vụ Nổ Lớn đến lỗ đen, giải thích vũ trụ cho người đọc phổ thông.'],
        ];
    }
}
