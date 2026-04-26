<?php

namespace App\Http\Controllers;

use App\Models\ChienDich;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KiemDuyetChienDichController extends Controller
{
    public function boLoc()
    {
        $baseQuery = ChienDich::query()->whereNull('chien_dichs.xoa_luc');

        $categories = (clone $baseQuery)
            ->join('loai_chien_dichs', 'chien_dichs.loai_chien_dich_id', '=', 'loai_chien_dichs.id')
            ->select(
                'loai_chien_dichs.id',
                'loai_chien_dichs.ten',
                DB::raw('COUNT(chien_dichs.id) as total')
            )
            ->groupBy('loai_chien_dichs.id', 'loai_chien_dichs.ten')
            ->orderBy('loai_chien_dichs.ten')
            ->get();

        $statusCounts = (clone $baseQuery)
            ->select('chien_dichs.trang_thai', DB::raw('COUNT(chien_dichs.id) as total'))
            ->groupBy('chien_dichs.trang_thai')
            ->pluck('total', 'chien_dichs.trang_thai');

        return response()->json([
            'status' => 1,
            'message' => 'Lấy bộ lọc kiểm duyệt chiến dịch thành công.',
            'data' => [
                'tabs' => [
                    ['value' => 'pending', 'api_value' => 'cho_duyet', 'count' => (int) ($statusCounts['cho_duyet'] ?? 0)],
                    ['value' => 'approved', 'api_value' => 'da_duyet', 'count' => (int) ($statusCounts['da_duyet'] ?? 0)],
                    ['value' => 'pending_cancel', 'api_value' => 'yeu_cau_huy', 'count' => (int) ($statusCounts['yeu_cau_huy'] ?? 0)],
                    ['value' => 'cancelled', 'api_value' => 'da_huy', 'count' => (int) ($statusCounts['da_huy'] ?? 0)],
                    ['value' => 'all', 'api_value' => 'all', 'count' => (int) ((clone $baseQuery)->count())],
                    ['value' => 'active', 'api_value' => 'dang_dien_ra', 'count' => (int) ($statusCounts['dang_dien_ra'] ?? 0)],
                    ['value' => 'completed', 'api_value' => 'hoan_thanh', 'count' => (int) ($statusCounts['hoan_thanh'] ?? 0)],
                ],
                'categories' => $categories->map(fn ($item) => [
                    'value' => (string) $item->id,
                    'label' => $item->ten,
                    'count' => (int) $item->total,
                ])->values(),
                'priorities' => [
                    ['value' => 'urgent', 'api_value' => 'khan_cap'],
                    ['value' => 'high', 'api_value' => 'cao'],
                    ['value' => 'medium', 'api_value' => 'trung_binh'],
                    ['value' => 'low', 'api_value' => 'thap'],
                ],
            ],
        ]);
    }

    public function danhSach(Request $request)
    {
        $query = ChienDich::query()
            ->whereNull('xoa_luc')
            ->with([
                'loaiChienDich:id,ten,bieu_tuong,mau_sac',
                'nguoiTao:id,ho_ten,email',
                'duyetBoi:id,ho_ten,email',
            ]);

        if ($request->filled('trang_thai') && $request->trang_thai !== 'all') {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('tu_khoa')) {
            $keyword = trim($request->tu_khoa);
            $query->where(function ($sub) use ($keyword) {
                $sub->where('tieu_de', 'like', "%{$keyword}%")
                    ->orWhere('dia_diem', 'like', "%{$keyword}%")
                    ->orWhereHas('nguoiTao', function ($nguoiTaoQuery) use ($keyword) {
                        $nguoiTaoQuery->where('ho_ten', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($request->filled('loai_chien_dich_id')) {
            $query->where('loai_chien_dich_id', $request->integer('loai_chien_dich_id'));
        }

        if ($request->filled('muc_do_uu_tien')) {
            $query->where('muc_do_uu_tien', $request->muc_do_uu_tien);
        }

        $campaigns = $query->orderByRaw("FIELD(trang_thai, 'yeu_cau_huy', 'cho_duyet', 'dang_dien_ra', 'da_duyet', 'hoan_thanh', 'tu_choi', 'da_huy', 'nhap')")
            ->orderByDesc('tao_luc')
            ->get();

        return response()->json([
            'status' => 1,
            'message' => 'Lấy danh sách chiến dịch kiểm duyệt thành công.',
            'data' => $campaigns->map(fn ($cd) => $this->mapCampaignSummary($cd))->values(),
            'thong_ke' => [
                'tong' => ChienDich::whereNull('xoa_luc')->count(),
                'cho_duyet' => ChienDich::whereNull('xoa_luc')->where('trang_thai', 'cho_duyet')->count(),
                'da_duyet' => ChienDich::whereNull('xoa_luc')->where('trang_thai', 'da_duyet')->count(),
                'yeu_cau_huy' => ChienDich::whereNull('xoa_luc')->where('trang_thai', 'yeu_cau_huy')->count(),
                'da_huy' => ChienDich::whereNull('xoa_luc')->where('trang_thai', 'da_huy')->count(),
                'dang_dien_ra' => ChienDich::whereNull('xoa_luc')->where('trang_thai', 'dang_dien_ra')->count(),
                'hoan_thanh' => ChienDich::whereNull('xoa_luc')->where('trang_thai', 'hoan_thanh')->count(),
            ],
        ]);
    }

    public function chiTiet($id)
    {
        $cd = $this->findCampaignForReview($id);
        if (!$cd) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy chiến dịch.'], 404);
        }

        return response()->json([
            'status' => 1,
            'message' => 'Lấy chi tiết chiến dịch thành công.',
            'data' => $this->mapCampaignDetail($cd),
        ]);
    }

    public function danhSachFeedback($id)
    {
        $cd = $this->findCampaignForReview($id);
        if (!$cd) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy chiến dịch.'], 404);
        }

        return response()->json([
            'status' => 1,
            'data' => $cd->feedbacks->map(fn ($feedback) => $this->mapFeedback($feedback))->values(),
        ]);
    }

     public function danhSachBaoCao($id)
    {
        $cd = $this->findCampaignForReview($id);
        if (!$cd) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy chiến dịch.'], 404);
        }

        return response()->json([
            'status' => 1,
            'data' => $cd->baoCaos->map(fn ($baoCao) => $this->mapBaoCao($baoCao))->values(),
        ]);
    }

    private function findCampaignForReview($id): ?ChienDich
    {
        return ChienDich::where('id', $id)
            ->whereNull('xoa_luc')
            ->with([
                'loaiChienDich:id,ten,bieu_tuong,mau_sac',
                'kyNangs:ky_nangs.id,ten',
                'nguoiTao:id,ho_ten,email',
                'duyetBoi:id,ho_ten,email',
                'dangKyThamGias.nguoiDung:id,ho_ten,email',
                'dangKyThamGias.nguoiDung.kyNangs:id,ten',
                'dangKyThamGias.nguoiDung.khuVucs:id,ten',
                'feedbacks.nguoiDung:id,ho_ten,email',
                'feedbacks.thePhanHois:id,ten',
                'baoCaos.nguoiGui:id,ho_ten,email',
                'baoCaos.nguoiXuLy:id,ho_ten,email',
                'lichSuKiemDuyets.nguoiThucHien:id,ho_ten,email',
            ])
            ->first();
    }

    private function mapCampaignDetail(ChienDich $cd): array
    {
        return array_merge($this->mapCampaignSummary($cd), [
            'mo_ta' => $cd->mo_ta,
            'han_dang_ky' => $cd->han_dang_ky?->format('Y-m-d'),
            'so_luong_toi_thieu' => $cd->so_luong_toi_thieu,
            'ky_nangs' => $cd->kyNangs->map(fn ($kyNang) => [
                'id' => $kyNang->id,
                'ten' => $kyNang->ten,
            ])->values(),
            'danh_sach_dang_ky' => $cd->dangKyThamGias
                ->sortByDesc(fn ($dangKy) => $dangKy->dang_ky_luc ?? $dangKy->tao_luc)
                ->values()
                ->map(fn ($dangKy) => $this->mapDangKyThamGia($dangKy))
                ->values(),
            'feedbacks' => $cd->feedbacks->map(fn ($feedback) => $this->mapFeedback($feedback))->values(),
            'bao_caos' => $cd->baoCaos->map(fn ($baoCao) => $this->mapBaoCao($baoCao))->values(),
            'lich_su_kiem_duyet' => $cd->lichSuKiemDuyets->sortByDesc('tao_luc')->values()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'hanh_dong' => $item->hanh_dong,
                    'tu_trang_thai' => $item->tu_trang_thai,
                    'den_trang_thai' => $item->den_trang_thai,
                    'ghi_chu' => $item->ghi_chu,
                    'du_lieu_bo_sung' => $item->du_lieu_bo_sung,
                    'tao_luc' => $item->tao_luc?->format('Y-m-d H:i:s'),
                    'nguoi_thuc_hien' => $item->nguoiThucHien ? [
                        'id' => $item->nguoiThucHien->id,
                        'ho_ten' => $item->nguoiThucHien->ho_ten,
                        'email' => $item->nguoiThucHien->email,
                    ] : null,
                ];
            })->values(),
        ]);
    }

    private function mapCampaignSummary(ChienDich $cd): array
    {
        return [
            'id' => $cd->id,
            'tieu_de' => $cd->tieu_de,
            'dia_diem' => $cd->dia_diem,
            'vi_do' => $cd->vi_do,
            'kinh_do' => $cd->kinh_do,
            'ngay_bat_dau' => $cd->ngay_bat_dau?->format('Y-m-d'),
            'ngay_ket_thuc' => $cd->ngay_ket_thuc?->format('Y-m-d'),
            'so_luong_toi_da' => $cd->so_luong_toi_da,
            'so_dang_ky' => $cd->so_dang_ky,
            'muc_do_uu_tien' => $cd->muc_do_uu_tien,
            'trang_thai' => $cd->trang_thai,
            'ly_do_tu_choi' => $cd->ly_do_tu_choi,
            'ly_do_huy_yeu_cau' => $cd->trang_thai === 'yeu_cau_huy' ? $cd->ly_do_tu_choi : null,
            'loai_chien_dich' => $cd->loaiChienDich,
            'nguoi_tao' => $cd->nguoiTao,
            'duyet_boi' => $cd->duyetBoi,
            'tao_luc' => $cd->tao_luc?->format('Y-m-d H:i:s'),
            'duyet_luc' => $cd->duyet_luc?->format('Y-m-d H:i:s'),
        ];
    }

    private function mapDangKyThamGia($dangKy): array
    {
        $nguoiDung = $dangKy->nguoiDung;

        return [
            'id' => $dangKy->id,
            'trang_thai' => $dangKy->trang_thai,
            'dang_ky_luc' => $dangKy->dang_ky_luc?->format('Y-m-d H:i:s'),
            'duyet_luc' => $dangKy->duyet_luc?->format('Y-m-d H:i:s'),
            'xac_nhan_luc' => $dangKy->xac_nhan_luc?->format('Y-m-d H:i:s'),
            'huy_luc' => $dangKy->huy_luc?->format('Y-m-d H:i:s'),
            'ly_do_huy' => $dangKy->ly_do_huy,
            'ghi_chu' => $dangKy->ghi_chu,
            'nguoi_dung' => $nguoiDung ? [
                'id' => $nguoiDung->id,
                'ho_ten' => $nguoiDung->ho_ten,
                'email' => $nguoiDung->email,
                'ky_nangs' => $nguoiDung->kyNangs->map(fn ($kyNang) => [
                    'id' => $kyNang->id,
                    'ten' => $kyNang->ten,
                ])->values(),
                'khu_vucs' => $nguoiDung->khuVucs->map(fn ($khuVuc) => [
                    'id' => $khuVuc->id,
                    'ten' => $khuVuc->ten,
                ])->values(),
            ] : null,
        ];
    }

    private function mapFeedback($feedback): array
    {
        return [
            'id' => $feedback->id,
            'so_sao' => $feedback->so_sao,
            'nhan_xet' => $feedback->nhan_xet,
            'tao_luc' => $feedback->tao_luc?->format('Y-m-d H:i:s'),
            'nguoi_dung' => $feedback->nguoiDung ? [
                'id' => $feedback->nguoiDung->id,
                'ho_ten' => $feedback->nguoiDung->ho_ten,
                'email' => $feedback->nguoiDung->email,
            ] : null,
            'the_phan_hoi' => $feedback->thePhanHois->map(fn ($the) => [
                'id' => $the->id,
                'ten' => $the->ten,
            ])->values(),
        ];
    }

    private function mapBaoCao($baoCao): array
    {
        return [
            'id' => $baoCao->id,
            'phan_loai' => $baoCao->phan_loai,
            'tieu_de' => $baoCao->tieu_de,
            'noi_dung' => $baoCao->noi_dung,
            'trang_thai' => $baoCao->trang_thai,
            'phan_hoi_xu_ly' => $baoCao->phan_hoi_xu_ly,
            'xu_ly_luc' => $baoCao->xu_ly_luc?->format('Y-m-d H:i:s'),
            'tao_luc' => $baoCao->tao_luc?->format('Y-m-d H:i:s'),
            'nguoi_gui' => $baoCao->nguoiGui ? [
                'id' => $baoCao->nguoiGui->id,
                'ho_ten' => $baoCao->nguoiGui->ho_ten,
                'email' => $baoCao->nguoiGui->email,
            ] : null,
            'nguoi_xu_ly' => $baoCao->nguoiXuLy ? [
                'id' => $baoCao->nguoiXuLy->id,
                'ho_ten' => $baoCao->nguoiXuLy->ho_ten,
                'email' => $baoCao->nguoiXuLy->email,
            ] : null,
        ];
    }
}
