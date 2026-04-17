<?php

use App\Http\Controllers\XacThucController;
use App\Http\Controllers\DanhMucController;
use App\Http\Controllers\ChienDichController;
use App\Http\Controllers\ThamGiaChienDichController;
use App\Http\Controllers\TrangChuController;
use App\Http\Controllers\NguoiDungController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\TheoDoiPhanHoiController;
use App\Http\Controllers\ThongKeTongQuanController;
use Illuminate\Support\Facades\Route;

// =========================================== DANH MỤC (Public) ========================================
Route::get('/danh-muc/ky-nang', [DanhMucController::class, 'getKyNang']);
Route::get('/danh-muc/khu-vuc', [DanhMucController::class, 'getKhuVuc']);
Route::get('/danh-muc/tinh-thanh', [DanhMucController::class, 'getTinhThanh']);
Route::get('/danh-muc/phuong-xa', [DanhMucController::class, 'getPhuongXa']);
Route::get('/danh-muc/loai-chien-dich', [ChienDichController::class, 'danhSachLoai']);
Route::get('/trang-chu', [TrangChuController::class, 'thongTin']);
Route::get('/chien-dich/bo-loc', [ThamGiaChienDichController::class, 'boLoc']);
Route::get('/chien-dich/tim-kiem', [ThamGiaChienDichController::class, 'timKiem']);
Route::get('/chien-dich', [ThamGiaChienDichController::class, 'danhSach']);
Route::get('/chien-dich/{id}', [ThamGiaChienDichController::class, 'chiTiet']);

// =========================================== XÁC THỰC ===============================================
Route::post('/xac-thuc/dang-ky', [XacThucController::class, 'dangKy']);
Route::post('/xac-thuc/xac-thuc-email', [XacThucController::class, 'xacThucEmail']);
Route::post('/xac-thuc/dang-nhap', [XacThucController::class, 'dangNhap']);
Route::post('/xac-thuc/dang-xuat', [XacThucController::class, 'dangXuat']);
Route::post('/xac-thuc/quen-mat-khau', [XacThucController::class, 'quenMatKhau']);
Route::post('/xac-thuc/dat-lai-mat-khau', [XacThucController::class, 'datLaiMatKhau']);
Route::get('/xac-thuc/thong-tin', [XacThucController::class, 'layThongTin']);

// =========================================== NGƯỜI DÙNG ===============================================
Route::middleware('auth:api')->group(function () {
    Route::middleware('permission:account_center.view')->group(function () {
        Route::get('/nguoi-dung/thong-tin', [NguoiDungController::class, 'layThongTin']);
    });
    Route::middleware('permission:account_center.manage')->group(function () {
        Route::post('/nguoi-dung/cap-nhat-thong-tin', [NguoiDungController::class, 'capNhatThongTin']);
        Route::post('/nguoi-dung/doi-mat-khau', [NguoiDungController::class, 'doiMatKhau']);
    });

    // API dành riêng cho Tình nguyện viên
    Route::middleware('permission:competency_profile.view')->group(function () {
        Route::get('/nguoi-dung/ho-so-nang-luc', [NguoiDungController::class, 'layHoSoNangLuc']);
    });

    Route::middleware('permission:competency_profile.manage')->group(function () {
        Route::put('/nguoi-dung/ho-so-nang-luc', [NguoiDungController::class, 'luuHoSoNangLuc']);
    });
});

// =========================================== Tình Nguyện Viên ==========================================
Route::middleware(['auth:api', 'tinhNguyenVien'])->group(function () {
    Route::middleware('permission:volunteer_campaigns.view,campaign_coordination.view,campaign_report_monitoring.view')->group(function () {
        Route::get('/tinh-nguyen-vien/chien-dich', [ChienDichController::class, 'danhSach']);
        Route::get('/tinh-nguyen-vien/chien-dich/{id}', [ChienDichController::class, 'chiTiet']);
        Route::get('/tinh-nguyen-vien/chien-dich/{id}/giam-sat-bao-cao', [ChienDichController::class, 'giamSatBaoCao']);
    });
    Route::middleware('permission:volunteer_campaigns.manage')->group(function () {
        Route::post('/tinh-nguyen-vien/chien-dich', [ChienDichController::class, 'taoMoi']);
        Route::put('/tinh-nguyen-vien/chien-dich/{id}', [ChienDichController::class, 'capNhat']);
        Route::put('/tinh-nguyen-vien/chien-dich/{id}/trang-thai', [ChienDichController::class, 'capNhatTrangThai']);
        Route::put('/tinh-nguyen-vien/chien-dich/{id}/dang-ky/{registrationId}/trang-thai', [ChienDichController::class, 'capNhatTrangThaiDangKy']);
        Route::put('/tinh-nguyen-vien/chien-dich/{id}/huy', [ChienDichController::class, 'huyChienDich']);
    });
    Route::middleware('permission:campaign_participation.manage')->group(function () {
        Route::post('/chien-dich/{id}/dang-ky', [ThamGiaChienDichController::class, 'dangKy']);
        Route::put('/chien-dich/{id}/huy-dang-ky', [ThamGiaChienDichController::class, 'huyDangKy']);
        Route::put('/chien-dich/{id}/xac-nhan-tham-gia', [ThamGiaChienDichController::class, 'xacNhanThamGia']);
    });
     Route::middleware('permission:campaign_coordination.manage')->group(function () {
        Route::post('/chien-dich/{id}/moi-tinh-nguyen-vien', [RecommendationController::class, 'moiTinhNguyenVien']);
    });

    Route::middleware('permission:feedback_tracking.view')->group(function () {
        Route::get('/tinh-nguyen-vien/theo-doi-phan-hoi', [TheoDoiPhanHoiController::class, 'tongQuan']);
    });

    Route::middleware('permission:feedback_tracking.manage')->group(function () {
        Route::post('/tinh-nguyen-vien/theo-doi-phan-hoi/bao-cao', [TheoDoiPhanHoiController::class, 'taoBaoCao']);
        Route::post('/tinh-nguyen-vien/theo-doi-phan-hoi/danh-gia-chien-dich', [TheoDoiPhanHoiController::class, 'danhGiaChienDich']);
    });

    Route::middleware('permission:ai_recommendation.view,campaign_coordination.view')->group(function () {
        Route::get('/goi-y', [RecommendationController::class, 'goiY']);
    });
});
