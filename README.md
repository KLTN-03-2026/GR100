<div align="center">

# 🌱 VolunteerHub — Hệ Thống Quản Lý Tình Nguyện Viên

**Nền tảng kết nối tình nguyện viên với các chiến dịch hoạt động xã hội một cách thông minh, minh bạch và hiệu quả.**

[![Laravel](https://img.shields.io/badge/Backend-Laravel%2012-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Frontend-Vue.js%203-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net)
[![Vite](https://img.shields.io/badge/Build-Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
[![JWT](https://img.shields.io/badge/Auth-JWT-000000?style=for-the-badge&logo=jsonwebtokens&logoColor=white)](https://jwt.io)

</div>

---

## 📋 Mục Lục

- [Tổng Quan Dự Án](#-tổng-quan-dự-án)
- [Tính Năng Nổi Bật](#-tính-năng-nổi-bật)
- [Kiến Trúc Hệ Thống](#-kiến-trúc-hệ-thống)
- [Công Nghệ Sử Dụng](#-công-nghệ-sử-dụng)
- [Cấu Trúc Dự Án](#-cấu-trúc-dự-án)
- [Hệ Thống Gợi Ý Thông Minh](#-hệ-thống-gợi-ý-thông-minh)
- [Vai Trò Người Dùng](#-vai-trò-người-dùng)
- [API Endpoints](#-api-endpoints)
- [Cài Đặt & Khởi Chạy](#-cài-đặt--khởi-chạy)
- [Cấu Hình Môi Trường](#-cấu-hình-môi-trường)

---

## 🌟 Tổng Quan Dự Án

**VolunteerHub** là một hệ thống quản lý tình nguyện viên toàn diện, được xây dựng theo kiến trúc **Monorepo** với Backend (Laravel) và Frontend (Vue.js) tách biệt, giao tiếp qua **RESTful API**.

Hệ thống giải quyết bài toán kết nối giữa **tổ chức/cá nhân khởi xướng chiến dịch** và **tình nguyện viên** thông qua một nền tảng số hiện đại, hỗ trợ toàn bộ vòng đời chiến dịch: từ lên kế hoạch → kiểm duyệt → mở đăng ký → điều phối nhân sự → đánh giá sau chiến dịch.

Điểm đặc biệt của hệ thống là module **Gợi Ý Thông Minh (AI Recommendation)** sử dụng Content-Based Filtering, Cosine Similarity và công thức Haversine để tính toán mức độ phù hợp giữa tình nguyện viên và chiến dịch một cách minh bạch và có thể giải thích được.

---

## ✨ Tính Năng Nổi Bật

### 👤 Dành Cho Tình Nguyện Viên
- **Duyệt & Đăng Ký Chiến Dịch**: Tìm kiếm, lọc theo khu vực, kỹ năng, loại chiến dịch và thời gian
- **Hồ Sơ Năng Lực**: Quản lý kỹ năng, kinh nghiệm, chứng chỉ và lịch rảnh cá nhân
- **Gợi Ý Cá Nhân Hóa**: Nhận đề xuất chiến dịch phù hợp dựa trên hồ sơ cá nhân
- **Giám Sát & Báo Cáo**: Theo dõi tiến độ tham gia, đánh giá và phản hồi chiến dịch

### 🏢 Dành Cho Tổ Chức / Người Tạo Chiến Dịch
- **Quản Lý Chiến Dịch**: Tạo, chỉnh sửa, theo dõi tiến độ và kết thúc chiến dịch
- **Điều Phối Nhân Sự (AI-Assisted)**: Hệ thống tự động phân nhóm ứng viên thành _"Nên mời trước"_, _"Đề xuất"_, _"Chưa phù hợp"_
- **So Sánh Ứng Viên**: Modal so sánh chi tiết giữa tình nguyện viên và yêu cầu chiến dịch
- **Mời Tình Nguyện Viên**: Gửi lời mời trực tiếp đến các ứng viên phù hợp

### 🔍 Dành Cho Kiểm Duyệt Viên
- **Duyệt/Từ Chối Chiến Dịch**: Xem xét và kiểm duyệt nội dung các chiến dịch mới
- **Xử Lý Báo Cáo**: Tiếp nhận và xử lý phản hồi, báo cáo từ cộng đồng
- **Thống Kê Kiểm Duyệt**: Xem tổng quan hoạt động trong phạm vi kiểm duyệt

### 🛡️ Dành Cho Quản Trị Viên
- **Dashboard Tổng Quan**: Biểu đồ và số liệu thống kê toàn hệ thống
- **Quản Lý Người Dùng**: Tạo, chỉnh sửa, vô hiệu hóa và xóa tài khoản
- **Phân Quyền Chi Tiết**: Hệ thống quyền hạn (permissions) linh hoạt theo từng chức năng
- **Quản Lý Danh Mục**: Quản lý kỹ năng, khu vực, loại chiến dịch

---

## 🏗️ Kiến Trúc Hệ Thống

```
┌─────────────────────────────────────────────────────────┐
│                    CLIENT (Browser)                      │
│              Vue.js 3 + Vue Router + Axios               │
│              Đa ngôn ngữ: Tiếng Việt / English          │
└────────────────────┬────────────────────────────────────┘
                     │ HTTP / RESTful API (JWT Auth)
┌────────────────────▼────────────────────────────────────┐
│                 BACKEND (Laravel 12)                     │
│                                                          │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────┐  │
│  │ Controllers │  │  Services   │  │   Middleware     │  │
│  │ (11 files)  │  │ (AI/Match)  │  │  (Auth/Roles)    │  │
│  └─────────────┘  └─────────────┘  └─────────────────┘  │
│                                                          │
│           JWT Authentication (tymon/jwt-auth)            │
│           Queue System (Database Driver)                 │
│           Mail Service (SMTP/Gmail)                      │
└────────────────────┬────────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────────┐
│                   DATABASE (MySQL)                       │
│           26 migrations │ Seeder │ Factory               │
└─────────────────────────────────────────────────────────┘
```

---

## 🛠️ Công Nghệ Sử Dụng

### Backend
| Công Nghệ | Phiên Bản | Mục Đích |
|-----------|-----------|---------|
| **PHP** | ^8.2 | Ngôn ngữ lập trình chính |
| **Laravel** | ^12.0 | Framework backend |
| **tymon/jwt-auth** | ^2.1 | Xác thực JWT |
| **MySQL** | Latest | Cơ sở dữ liệu |
| **Laravel Queue** | Database | Xử lý tác vụ nền (gửi email) |
| **PHPUnit** | ^11.5 | Unit Testing |

### Frontend
| Công Nghệ | Phiên Bản | Mục Đích |
|-----------|-----------|---------|
| **Vue.js** | ^3.3.4 | Framework UI |
| **Vue Router** | ^4.0.13 | Điều hướng SPA |
| **vue-i18n** | ^9.14.5 | Đa ngôn ngữ (VI/EN) |
| **Axios** | ^1.7.2 | HTTP Client |
| **Vite** | ^4.4.5 | Build Tool |
| **jQuery** | ^4.0.0 | DOM Utilities |

---

## 📁 Cấu Trúc Dự Án

```
Volunteer-Management/
├── 📂 Backend/                        # Laravel API Server
│   ├── 📂 app/
│   │   ├── 📂 Http/
│   │   │   ├── 📂 Controllers/        # 11 Controllers (API handlers)
│   │   │   │   ├── XacThucController.php          # Đăng ký, đăng nhập, JWT
│   │   │   │   ├── NguoiDungController.php        # Quản lý người dùng
│   │   │   │   ├── ChienDichController.php        # CRUD chiến dịch
│   │   │   │   ├── ThamGiaChienDichController.php # Đăng ký tham gia
│   │   │   │   ├── KiemDuyetChienDichController.php # Kiểm duyệt
│   │   │   │   ├── RecommendationController.php   # Gợi ý AI
│   │   │   │   ├── TheoDoiPhanHoiController.php   # Theo dõi phản hồi
│   │   │   │   ├── ThongKeTongQuanController.php  # Thống kê Dashboard
│   │   │   │   ├── DanhMucController.php          # Danh mục (kỹ năng, khu vực)
│   │   │   │   └── TrangChuController.php         # Thông tin trang chủ
│   │   │   ├── 📂 Middleware/         # Auth, Role-based access
│   │   │   └── 📂 Requests/          # Form validation
│   │   ├── 📂 Models/                 # 17 Eloquent Models
│   │   │   ├── NguoiDung.php          # User model
│   │   │   ├── ChienDich.php          # Campaign model
│   │   │   ├── DangKyThamGia.php      # Registration model
│   │   │   └── ...
│   │   └── 📂 Services/              # Business Logic
│   │       ├── RecommendationService.php  # Core AI matching engine
│   │       ├── MatchingScoreService.php   # Tính điểm phù hợp
│   │       └── DistanceService.php        # Tính khoảng cách Haversine
│   ├── 📂 database/
│   │   ├── 📂 migrations/            # 26 migration files
│   │   └── 📂 seeders/               # 6 seeder files
│   └── 📂 routes/
│       └── api.php                   # Toàn bộ API routes (4 nhóm vai trò)
│
└── 📂 Frontend/                       # Vue.js SPA
    └── 📂 src/
        ├── 📂 components/
        │   ├── 📂 User/              # 12+ trang của người dùng/tổ chức
        │   │   ├── Danh_Sach_Chien_Dich.vue     # Danh sách chiến dịch
        │   │   ├── Chi_Tiet_Chien_Dich.vue      # Chi tiết chiến dịch
        │   │   ├── Quan_Ly_Chien_Dich.vue       # Quản lý chiến dịch (Org)
        │   │   ├── Dieu_Phoi_Nhan_Su.vue        # Điều phối nhân sự AI
        │   │   ├── Ho_So_Nang_Luc.vue           # Hồ sơ năng lực
        │   │   ├── Thong_Tin_Ca_Nhan.vue        # Thông tin cá nhân
        │   │   ├── Giam_Sat_Bao_Cao.vue         # Giám sát & báo cáo
        │   │   └── Theo_Doi_Phan_Hoi.vue        # Theo dõi phản hồi
        │   ├── 📂 Admin/             # 7 trang quản trị
        │   │   ├── Dashboard.vue               # Dashboard tổng quan
        │   │   ├── Quan_Ly_Nguoi_Dung.vue      # Quản lý users
        │   │   ├── Phan_Quyen.vue              # Phân quyền
        │   │   ├── Quan_Ly_Danh_Muc.vue        # Quản lý danh mục
        │   │   └── Quan_Ly_AI.vue              # Quản lý module AI
        │   └── 📂 Kiem_Duyet_Vien/   # Trang kiểm duyệt viên
        ├── 📂 i18n/                  # Đa ngôn ngữ VI/EN
        │   ├── vi.js                 # Tiếng Việt
        │   └── en.js                 # English
        ├── 📂 layout/                # Bố cục (Wrapper, sidebar, header)
        ├── 📂 router/                # Vue Router config
        └── 📂 services/              # API service layer
```

---

## 🤖 Hệ Thống Gợi Ý Thông Minh

VolunteerHub tích hợp một **engine gợi ý thông minh** theo hướng **White-box Decision Support** giúp kết nối tình nguyện viên với chiến dịch phù hợp nhất và hỗ trợ điều phối nhân sự cho người tổ chức.

### Thuật Toán & Công Nghệ

| Thành Phần | Thuật Toán |
|------------|------------|
| **Phù hợp kỹ năng** | Cosine Similarity + Độ phủ kỹ năng |
| **Phù hợp vị trí** | Công thức Haversine (khoảng cách thực tế) |
| **Phù hợp lịch** | Phân tích giao thoa lịch rảnh |
| **Độ tin cậy** | Lịch sử tham gia, đánh giá |
| **Kinh nghiệm** | Chuẩn hóa theo ngưỡng tối đa |

### Công Thức Điểm Phù Hợp Cuối

```
Điểm = (Khoảng cách × 30%) + (Kỹ năng × 30%) + (Lịch rảnh × 20%)
      + (Kinh nghiệm & Chứng chỉ × 10%) + (Bối cảnh × 5%) + (Độ tin cậy × 5%)
```

### Phân Nhóm Ứng Viên

```
≥ 80%  →  🟢 Nên mời trước
50-79% →  🟡 Tình nguyện viên đề xuất
< 50%  →  🔴 Chưa phù hợp / Cần bổ sung
```

---

## 👥 Vai Trò Người Dùng

| Vai Trò | Mô Tả | Quyền Hạn Chính |
|---------|-------|-----------------|
| **🙋 Tình Nguyện Viên** | Người dùng thông thường | Duyệt & đăng ký chiến dịch, quản lý hồ sơ, nhận gợi ý |
| **🏢 Tổ Chức** | Người tạo chiến dịch | Tạo/quản lý chiến dịch, điều phối nhân sự, đánh giá TNV |
| **🔍 Kiểm Duyệt Viên** | Người duyệt nội dung | Kiểm duyệt chiến dịch, xử lý báo cáo, xem thống kê |
| **🛡️ Quản Trị Viên** | Admin hệ thống | Toàn quyền quản lý users, phân quyền, danh mục, dashboard |

---

## 🔌 API Endpoints

### Xác Thực (Public)
```
POST   /api/xac-thuc/dang-ky          # Đăng ký tài khoản
POST   /api/xac-thuc/dang-nhap        # Đăng nhập → JWT Token
POST   /api/xac-thuc/quen-mat-khau    # Quên mật khẩu
POST   /api/xac-thuc/dat-lai-mat-khau # Đặt lại mật khẩu
POST   /api/xac-thuc/xac-thuc-email   # Xác thực email
```

### Chiến Dịch (Public)
```
GET    /api/chien-dich                # Danh sách chiến dịch
GET    /api/chien-dich/{id}           # Chi tiết chiến dịch
GET    /api/chien-dich/tim-kiem       # Tìm kiếm
GET    /api/chien-dich/bo-loc         # Bộ lọc có sẵn
```

### Tình Nguyện Viên (Auth Required)
```
GET    /api/goi-y                                         # Gợi ý cá nhân hóa
POST   /api/chien-dich/{id}/dang-ky                       # Đăng ký tham gia
POST   /api/tinh-nguyen-vien/chien-dich                   # Tạo chiến dịch mới
GET    /api/tinh-nguyen-vien/chien-dich/{id}/giam-sat-bao-cao # Báo cáo
POST   /api/chien-dich/{id}/moi-tinh-nguyen-vien          # Điều phối nhân sự
```

### Kiểm Duyệt Viên (Auth Required)
```
GET    /api/kiem-duyet/chien-dich                         # DS chờ duyệt
PUT    /api/kiem-duyet/chien-dich/{id}/duyet              # Duyệt chiến dịch
PUT    /api/kiem-duyet/chien-dich/{id}/tu-choi            # Từ chối
PUT    /api/kiem-duyet/bao-cao/{id}/xu-ly                 # Xử lý báo cáo
```

### Quản Trị Viên (Auth Required)
```
GET    /api/admin/dashboard                               # Dashboard
GET    /api/admin/nguoi-dung                              # Quản lý users
PUT    /api/admin/phan-quyen/{id}                         # Cập nhật quyền hạn
POST   /api/admin/danh-muc/{type}                         # Thêm danh mục
```

---

## 🚀 Cài Đặt & Khởi Chạy

### Yêu Cầu Hệ Thống
- **PHP** >= 8.2 (với extensions: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML)
- **Composer** >= 2.x
- **Node.js** >= 18.x & **npm** >= 9.x
- **MySQL** >= 8.0
- **XAMPP** / **Laragon** hoặc môi trường tương đương

---

### 1. Clone Dự Án

```bash
https://github.com/KLTN-03-2026/GR100.git
```

---

### 2. Cài Đặt Backend

```bash
cd Backend

# Cài đặt dependencies PHP
composer install

# Sao chép file cấu hình môi trường
cp .env.example .env

# Tạo Application Key
php artisan key:generate

# Tạo JWT Secret Key
php artisan jwt:secret
```

**Cấu hình Database** trong file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=volunteer_management
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# Chạy migration và seeder
php artisan migrate --seed

# Khởi động development server
php artisan serve
```

> API sẽ chạy tại: `http://localhost:8000`

---

### 3. Cài Đặt Frontend

```bash
cd ../Frontend

# Cài đặt dependencies Node.js
npm install

# Sao chép file cấu hình môi trường
cp .env.example .env
```

**Cấu hình API endpoint** trong file `.env`:
```env
VITE_API_URL=http://localhost:8000/api
```

```bash
# Khởi động development server
npm run dev
```

> Frontend sẽ chạy tại: `http://localhost:5173`

---

### 4. (Tùy Chọn) Khởi Động Đồng Thời Backend

Từ thư mục `Backend`, chạy lệnh sau để khởi động cùng lúc API server, Queue Worker, Pail Logger và Vite:

```bash
composer run dev
```

---

## ⚙️ Cấu Hình Môi Trường

### Backend (`Backend/.env`)

| Biến | Mô Tả | Giá Trị Mặc Định |
|------|-------|-----------------|
| `APP_NAME` | Tên ứng dụng | `VolunteerHub` |
| `APP_URL` | URL backend | `http://localhost` |
| `DB_DATABASE` | Tên database | `volunteer_management` |
| `FRONTEND_URL` | URL frontend (CORS) | `http://localhost:5173` |
| `JWT_SECRET` | Khóa bí mật JWT | *(generate bằng `php artisan jwt:secret`)* |
| `JWT_TTL` | Thời gian hết hạn JWT (phút) | `14400` (10 giờ) |
| `MAIL_HOST` | SMTP server | `smtp.gmail.com` |
| `QUEUE_CONNECTION` | Driver queue | `database` |

### Frontend (`Frontend/.env`)

| Biến | Mô Tả | Giá Trị Mặc Định |
|------|-------|-----------------|
| `VITE_API_URL` | URL của Backend API | `http://localhost:8000/api` |

---

## 🗄️ Database Schema

Hệ thống bao gồm **26 bảng** chính, được tổ chức theo các nhóm:

```
👤 Người Dùng            🎯 Chiến Dịch              📋 Hoạt Động
─────────────            ─────────────              ─────────────
nguoi_dungs              chien_dichs                dang_ky_tham_gias
ky_nangs                 loai_chien_dichs            lich_su_kiem_duyet_chien_dichs
nguoi_dung_ky_nangs      chien_dich_ky_nangs         phan_hoi_tnvs
khu_vucs                 hinh_anh_chien_dichs        bao_cao_chien_dichs
nguoi_dung_khu_vucs      ─────────────               danh_gia_tnvs
lich_ranhs               📍 Địa Lý                   thong_baos
kinh_nghiems             ─────────────               the_phan_hois
chung_chis               tinh_thanhs
                         phuong_xas
```

---

## 🌐 Hỗ Trợ Đa Ngôn Ngữ

Hệ thống hỗ trợ **2 ngôn ngữ** thông qua `vue-i18n`:

- 🇻🇳 **Tiếng Việt** — Ngôn ngữ chính
- 🇺🇸 **English** — Ngôn ngữ phụ

Người dùng có thể chuyển đổi ngôn ngữ trực tiếp trên giao diện. Toàn bộ text UI, thông báo lỗi và nội dung đều được quản lý qua file ngôn ngữ tập trung (`src/i18n/vi.js` và `src/i18n/en.js`).

---

## 📜 Giấy Phép

Dự án này được phát triển cho mục đích học thuật và nghiên cứu.

---

<div align="center">

**Được xây dựng với ❤️ bằng Laravel & Vue.js**

*VolunteerHub — Kết nối tình nguyện, lan tỏa yêu thương*

</div>
