<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Misi;
use App\Models\User;
use App\Models\Informasi;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ApiMisiController extends Controller
{
    use ApiResponse;

    private const POIN_MISI = [
        'pertama' => 10,
        'harian' => 25,
        'mingguan' => 100
    ];

    private const TARGET_MISI = [
        'pertama' => 1,
        'harian' => 3,
        'mingguan' => 10
    ];

    public function getMisi(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id'
            ]);

            $this->inisialisasiMisi($request->user_id);

            $misiPertama = Misi::where('user_id', $request->user_id)
                ->where('tipe', 'pertama')
                ->first();

            $misiHarian = Misi::where('user_id', $request->user_id)
                ->where('tipe', 'harian')
                ->whereDate('created_at', today())
                ->first();

            $misiMingguan = Misi::where('user_id', $request->user_id)
                ->where('tipe', 'mingguan')
                ->latest()
                ->first();

            $daftarMisi = [];

            if ($misiPertama) {
                $daftarMisi[] = [
                    'id' => $misiPertama->id,
                    'tipe' => 'pertama',
                    'judul' => 'Berbagi Informasi Pertama',
                    'deskripsi' => 'Dapatkan 10 poin untuk informasi pertama',
                    'target' => self::TARGET_MISI['pertama'],
                    'progress' => $misiPertama->jumlah,
                    'poin' => self::POIN_MISI['pertama'],
                    'selesai' => $misiPertama->selesai,
                    'poin_diklaim' => $misiPertama->poin_diklaim
                ];
            }

            if ($misiHarian) {
                $daftarMisi[] = [
                    'id' => $misiHarian->id,
                    'tipe' => 'harian',
                    'judul' => 'Berbagi 3 Informasi Hari Ini',
                    'deskripsi' => 'Dapatkan 25 poin dengan berbagi 3 informasi',
                    'target' => self::TARGET_MISI['harian'],
                    'progress' => $misiHarian->jumlah,
                    'poin' => self::POIN_MISI['harian'],
                    'selesai' => $misiHarian->selesai,
                    'poin_diklaim' => $misiHarian->poin_diklaim
                ];
            }

            if ($misiMingguan && $misiMingguan->created_at->addDays(5)->isFuture()) {
                $daftarMisi[] = [
                    'id' => $misiMingguan->id,
                    'tipe' => 'mingguan',
                    'judul' => 'Berbagi 10 Informasi dalam 5 Hari',
                    'deskripsi' => 'Dapatkan 100 poin dengan berbagi 10 informasi',
                    'target' => self::TARGET_MISI['mingguan'],
                    'progress' => $misiMingguan->jumlah,
                    'poin' => self::POIN_MISI['mingguan'],
                    'selesai' => $misiMingguan->selesai,
                    'poin_diklaim' => $misiMingguan->poin_diklaim,
                    'sisa_hari' => now()->diffInDays($misiMingguan->created_at->addDays(5))
                ];
            }

            return $this->okResponse($daftarMisi, 'Data misi berhasil diambil');

        } catch (\Exception $e) {
            Log::error('Get Misi Error: ' . $e->getMessage());
            return $this->serverErrorResponse('Terjadi kesalahan saat mengambil data misi');
        }
    }

    private function inisialisasiMisi($userId)
    {
        try {
            DB::beginTransaction();

            if (!Misi::where('user_id', $userId)->where('tipe', 'pertama')->exists()) {
                Misi::create([
                    'user_id' => $userId,
                    'tipe' => 'pertama',
                    'jumlah' => 0,
                    'selesai' => false,
                    'poin_diklaim' => false
                ]);
            }

            if (
                !Misi::where('user_id', $userId)
                    ->where('tipe', 'harian')
                    ->whereDate('created_at', today())
                    ->exists()
            ) {
                Misi::create([
                    'user_id' => $userId,
                    'tipe' => 'harian',
                    'jumlah' => 0,
                    'selesai' => false,
                    'poin_diklaim' => false
                ]);
            }

            $misiMingguan = Misi::where('user_id', $userId)
                ->where('tipe', 'mingguan')
                ->latest()
                ->first();

            if (!$misiMingguan || $misiMingguan->created_at->addDays(5)->isPast()) {
                Misi::create([
                    'user_id' => $userId,
                    'tipe' => 'mingguan',
                    'jumlah' => 0,
                    'selesai' => false,
                    'poin_diklaim' => false
                ]);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Inisialisasi Misi Error: ' . $e->getMessage());
            return false;
        }
    }

    public function updateProgress($userId)
    {
        try {
            DB::beginTransaction();

            // Update misi pertama
            $misiPertama = Misi::where('user_id', $userId)
                ->where('tipe', 'pertama')
                ->first();

            if ($misiPertama) {
                $jumlahInfo = Informasi::where('user_id', $userId)->count();
                $this->updateJumlahMisi($misiPertama, $jumlahInfo);

                Log::info('Update Misi Pertama:', [
                    'jumlah_info' => $jumlahInfo,
                    'misi_id' => $misiPertama->id,
                    'selesai' => $misiPertama->selesai
                ]);
            }

            // Update misi harian
            $misiHarian = Misi::where('user_id', $userId)
                ->where('tipe', 'harian')
                ->whereDate('created_at', today())
                ->first();

            if ($misiHarian) {
                $jumlahInfo = Informasi::where('user_id', $userId)
                    ->whereDate('created_at', today())
                    ->count();
                $this->updateJumlahMisi($misiHarian, $jumlahInfo);

                Log::info('Update Misi Harian:', [
                    'jumlah_info' => $jumlahInfo,
                    'misi_id' => $misiHarian->id,
                    'selesai' => $misiHarian->selesai
                ]);
            }

            // Update misi mingguan
            $misiMingguan = Misi::where('user_id', $userId)
                ->where('tipe', 'mingguan')
                ->latest()
                ->first();

            if ($misiMingguan && $misiMingguan->created_at->addDays(5)->isFuture()) {
                $jumlahInfo = Informasi::where('user_id', $userId)
                    ->whereBetween('created_at', [
                        $misiMingguan->created_at,
                        $misiMingguan->created_at->copy()->addDays(5)
                    ])
                    ->count();
                $this->updateJumlahMisi($misiMingguan, $jumlahInfo);

                Log::info('Update Misi Mingguan:', [
                    'jumlah_info' => $jumlahInfo,
                    'misi_id' => $misiMingguan->id,
                    'selesai' => $misiMingguan->selesai
                ]);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Progress Error: ' . $e->getMessage());
            return false;
        }
    }

    private function updateJumlahMisi($misi, $jumlah)
    {
        $misi->jumlah = $jumlah;

        if ($jumlah >= self::TARGET_MISI[$misi->tipe] && !$misi->selesai) {
            $misi->selesai = true;

            Log::info('Misi selesai:', [
                'tipe' => $misi->tipe,
                'jumlah' => $jumlah,
                'target' => self::TARGET_MISI[$misi->tipe]
            ]);
        }

        return $misi->save();
    }

    public function claimPoin(Request $request)
    {
        try {
            $request->validate([
                'misi_id' => 'required|exists:misi,id',
                'user_id' => 'required|exists:users,id'
            ]);

            DB::beginTransaction();

            $misi = Misi::where('id', $request->misi_id)
                ->where('user_id', $request->user_id)
                ->where('selesai', true)
                ->where('poin_diklaim', false)
                ->first();

            if (!$misi) {
                return $this->error('Misi tidak ditemukan atau sudah diklaim');
            }

            $user = User::find($request->user_id);
            $user->increment('poin', self::POIN_MISI[$misi->tipe]);

            $misi->poin_diklaim = true;
            $misi->save();

            DB::commit();

            return $this->okResponse([
                'poin_didapat' => self::POIN_MISI[$misi->tipe],
                'total_poin' => $user->poin
            ], 'Poin berhasil diklaim');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Claim Poin Error: ' . $e->getMessage());
            return $this->serverErrorResponse('Gagal mengklaim poin');
        }
    }
}