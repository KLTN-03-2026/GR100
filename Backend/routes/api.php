<?php

use App\Http\Controllers\XacThucController;
use App\Http\Controllers\DanhMucController;
use App\Http\Controllers\ChienDichController;
use App\Http\Controllers\ThamGiaChienDichController;
use App\Http\Controllers\TrangChuController;
use App\Http\Controllers\NguoiDungController;
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
});
