# Secure Mini Note 

Secure Mini Note là một ứng dụng ghi chú cá nhân đơn giản, được xây dựng dựa trên ngôn ngữ **PHP thuần** và **MySQL Database**. Dự án tập trung vào việc xử lý các luồng logic cơ bản và thắt chặt các cơ chế an toàn thông tin (Security) ở tầng ứng dụng web.

## Công nghệ sử dụng
* **Backend:** PHP (Dùng PDO làm trình điều khiển kết nối)
* **Database:** MySQL
* **Frontend:** HTML5, CSS3 cơ bản
* **Tools:** XAMPP (Apache Port 8080), phpMyAdmin, Git/GitHub

## Các tính năng chính
* **Authentication:** Đăng ký tài khoản mới, Đăng nhập và Đăng xuất an toàn bằng Session.
* **CRUD Notes:** Cho phép người dùng Xem danh sách, Tạo mới, Chỉnh sửa, Xem chi tiết và Xóa ghi chú cá nhân.
* **Search Engine:** Tìm kiếm ghi chú linh hoạt theo tiêu đề hoặc nội dung.
* **Access Control:** Phân quyền nghiêm ngặt, đảm bảo người dùng chỉ tương tác được với dữ liệu của chính mình.

## Cấu trúc thư mục
```txt
mini_note/
│
├── config.php       # Cấu hình kết nối Database bằng PDO
├── register.php     # Giao diện và logic Đăng ký tài khoản
├── login.php        # Giao diện và logic Đăng nhập hệ thống
├── logout.php       # Xử lý xóa Session và Đăng xuất
│
├── index.php        # Trang chính (Hiển thị danh sách Note & Tìm kiếm)
├── create.php       # Giao diện và logic Tạo note mới
├── view.php         # Giao diện và logic Xem chi tiết một note
├── edit.php         # Giao diện và logic Chỉnh sửa note
├── delete.php       # Logic Xóa note an toàn
│
└── database.sql     # File kết xuất cấu trúc Database để khôi phục 
```
Hướng dẫn cài đặt và chạy ứng dụng

Bước 1: Chuẩn bị môi trường
Cài đặt phần mềm XAMPP Server.

Kích hoạt hai dịch vụ Apache và MySQL trên bảng điều khiển XAMPP Control Panel.
(Lưu ý: Nếu cổng mặc định bị trùng, hãy đảm bảo Apache đã được cấu hình chuyển sang cổng 8080).

Bước 2: Triển khai mã nguồn
Sao chép toàn bộ thư mục dự án mini_note vào thư mục lưu trữ web của XAMPP:
```txt
C:\xampp\htdocs\mini_note
```
Bước 3: Khởi tạo Cơ sở dữ liệu
Truy cập vào công cụ quản trị phpMyAdmin thông qua đường dẫn:
```txt
http://localhost:8080/phpmyadmin/
```

Tạo một cơ sở dữ liệu mới với tên gọi: mini_note

Chọn database mini_note, bấm vào mục Import (Nhập) và tải lên file dữ liệu database.sql nằm trong thư mục dự án.

Bước 4: Khởi chạy website
Mở trình duyệt web bất kỳ và truy cập vào hệ thống theo đường dẫn:
```txt
http://localhost:8080/mini_note/login.php
```
Các cơ chế bảo mật đã áp dụng
Là một dự án chú trọng vào An toàn thông tin, ứng dụng đã cài đặt sẵn các lớp phòng thủ cơ bản:

Password Hashing: Sử dụng thuật toán băm một chiều password_hash() với cấu hình PASSWORD_DEFAULT để mã hóa mật khẩu trong DB. Xác thực bằng password_verify().

SQL Injection Prevention: Toàn bộ các tác vụ truy vấn dữ liệu (Đăng nhập, Thêm/Sửa/Xóa/Tìm kiếm Note) đều sử dụng PDO Prepared Statements kết hợp tham số hóa dữ liệu đầu vào.

Stored XSS Mitigation: Sử dụng cơ chế lọc htmlspecialchars() với bộ cờ ENT_QUOTES tại mọi vùng hiển thị dữ liệu thô do người dùng nhập lên để vô hiệu hóa mã độc JavaScript.

Broken Object Level Authorization (IDOR): Ép kiểu dữ liệu intval() cho tham số trên URL và thực hiện ràng buộc chặt chẽ user_id từ Session hệ thống vào mệnh đề WHERE của tất cả các câu lệnh chỉnh sửa/xem/xóa. Kẻ tấn công không thể thay đổi ID trên URL để đọc trộm hoặc phá hoại dữ liệu của người dùng khác.