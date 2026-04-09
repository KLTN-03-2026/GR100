# 🛠️ Volunteer Management - Backend API (Laravel)

Đây là tài liệu mô tả chi tiết cho hệ thống Backend của dự án **Quản lý Tình nguyện viên (Volunteer Management)**. Hệ thống backend đóng vai trò là xương sống xử lý các logic nghiệp vụ, quản lý cơ sở dữ liệu và cung cấp các RESTful API cho ứng dụng Frontend.

---

## 🚀 Công nghệ sử dụng

Hệ thống được xây dựng trên nền tảng vững chắc và hiện đại:
- **Framework:** [Laravel](https://laravel.com/) (PHP)
- **Cơ sở dữ liệu:** MySQL / MariaDB (thông qua XAMPP)
- **Xác thực:** Laravel Sanctum / JWT (JSON Web Tokens)
- **Môi trường chạy:** PHP >= 8.1, Composer

---

## 📂 Cấu trúc thư mục (Directory Structure)

Các thành phần chính trong mã nguồn backend mà bạn cần lưu ý:

```text
Backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/   # Chứa các Controller xử lý logic API (Auth, Category, Volunteer,...)
│   │   ├── Middleware/    # Chứa các Middleware kiểm tra quyền truy cập, xác thực
│   │   └── Requests/      # Định nghĩa Form Request validation (kiểm tra dữ liệu đầu vào)
│   ├── Models/            # Định nghĩa các model tương tác với Database (User, Event, Task,...)
├── config/                # Chứa các file cấu hình toàn hệ thống (database, cors, auth,...)
├── database/
│   ├── migrations/        # Các file tạo và thay đổi cấu trúc bảng cơ sở dữ liệu
│   └── seeders/           # Tạo dữ liệu giả / dữ liệu mẫu khởi tạo (Mock data)
├── routes/
│   ├── api.php            # Nơi định nghĩa các endpoint RESTful API (VD: /api/login, /api/categories)
│   └── web.php            # Định nghĩa các route web mặc định (ít dùng trong môi trường API context)
├── storage/               # Lưu trữ file tải lên, logs hệ thống, caches.
└── .env                   # Tệp cấu hình biến môi trường (Thông tin Database, API keys,...)
```

---

## ⚙️ Yêu cầu hệ thống (Prerequisites)

Trước khi cài đặt, hãy đảm bảo máy tính của bạn đã có:
1. **[XAMPP](https://www.apachefriends.org/)** (hoặc WAMP/Laragon) bao gồm PHP (>= 8.1) và MySQL.
2. **[Composer](https://getcomposer.org/)** (Công cụ quản lý thư viện PHP).
3. **[Git](https://git-scm.com/)** (Để tải source code).

---

## 🛠️ Hướng dẫn Cài đặt & Chạy dự án (Installation & Setup)

Làm theo các bước sau để khởi chạy server backend ở máy trạm của bạn:

**Bước 1: Cài đặt thư viện (Dependencies)**  
Mở Terminal/Command Prompt tại thư mục `Backend`, chạy lệnh:
```bash
composer install
```

**Bước 2: Cấu hình biến môi trường (.env)**  
Lấy file mẫu và nhân bản ra file môi trường thật:
```bash
cp .env.example .env
```
Mở file `.env` vừa được tạo, tìm cấu hình Database và chỉnh sửa sao cho khớp với MySQL cục bộ (trên XAMPP):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=volunteer_manage
DB_USERNAME=root
DB_PASSWORD=
```
*(Đảm bảo bạn đã khởi động Apache và MySQL trên giao diện XAMPP Control Panel, đồng thời tạo một database trống mang tên `volunteer_manage` trong phpMyAdmin)*.

**Bước 3: Khởi tạo Application Key**  
Sinh ra chuỗi mã khoá bảo mật cho Laravel:
```bash
php artisan key:generate
```

**Bước 4: Chạy Migration và Seeder (Khởi tạo DB)**  
Tạo ra các bảng trong database và chèn dữ liệu mẫu:
```bash
php artisan migrate --seed
```

**Bước 5: Khởi chạy Server**  
Mở server ở cổng mặc định 8000:
```bash
php artisan serve
```
Backend sẽ hoạt động tại địa chỉ: `http://localhost:8000`.

---

## 🔗 Tổng quan các API chính (API Endpoints Overview)

Mọi API route đều được thêm tiền tố `/api/`.

### 1. Xác thực (Authentication)
Module dùng để đăng nhập, đăng ký và lấy thông tin người dùng.
*   `POST /api/register` - Đăng ký tài khoản mới.
*   `POST /api/login` - Đăng nhập vào hệ thống.
*   `POST /api/logout` - Đăng xuất.
*   `GET /api/user` - Lấy thông tin user hiện tại.

### 2. Danh mục (Categories)
Quản lý các loại danh mục thông tin trong dự án (Skills, Event Types,...).
*   `GET /api/categories` - Danh sách danh mục.
*   `POST /api/categories` - Tạo danh mục mới.
*   `PUT /api/categories/{id}` - Cập nhật danh mục.
*   `DELETE /api/categories/{id}` - Xoá danh mục.

*(Các route trên được cấu hình chuẩn REST và định nghĩa trong tệp `routes/api.php`)*

---

## 🛡️ Thiết kế & Best Practices
Để mã nguồn sạch sẽ và dễ bảo trì, đội ngũ Backend tuân thủ:
*   **Form Requests**: Luôn tách rời logic validate dũ liệu ra khỏi Controller.
*   **Eloquent ORM**: Tận dụng triệt để sức mạnh của Relationships để truy cập dữ liệu liên kết.
*   **API Resources**: Định dạng đầu ra JSON của API đồng nhất.
*   **Bảo mật**: Chống SQL Injection, XSS và phân quyền bằng Laravel Middleware / Sanctum Token.
