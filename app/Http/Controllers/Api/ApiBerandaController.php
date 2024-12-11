<?php

namespace App\Http\Controllers\Api;

use App\Models\Informasi;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ApiBerandaController extends Controller
{
    use ApiResponse;

    public function getLatestConfirmed()
    {
        try {
            $latestData = Informasi::with(['user:id,name', 'konfirmasi'])
                ->select('id', 'jenis_kendaraan', 'area', 'user_id', 'kapasitas', 'foto', 'poin', 'created_at')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get()
                ->map(function ($informasi) {
                    return [
                        'id' => $informasi->id,
                        'jenis_kendaraan' => $informasi->jenis_kendaraan,
                        'area' => $informasi->area,
                        'kapasitas' => $informasi->kapasitas,
                        'foto' => $informasi->foto,
                        'poin' => $informasi->poin,
                        'user_name' => $informasi->user->name,
                        'total_konfirmasi' => $informasi->konfirmasi->count(),
                        'created_at' => Carbon::parse($informasi->created_at)->format('Y-m-d H:i:s')
                    ];
                });

            if ($latestData->isEmpty()) {
                return $this->okResponse(null, 'Belum ada data');
            }

            return $this->okResponse($latestData, 'Data berhasil diambil');

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Terjadi kesalahan saat mengambil data');
        }
    }
}