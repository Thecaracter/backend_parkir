<?php

namespace App\Http\Controllers\Api;

use App\Models\Informasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiMisiController;

class ApiInformasiController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'area' => 'required|in:A,B,C,D,E,F,G',
            'user_id' => 'required|exists:users,id',
            'kapasitas' => 'required|integer',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'poin' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            // Handle file upload
            $foto = $request->file('foto');
            $uniqueDigits = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $fotoName = date('Ymd') . '_' . $uniqueDigits . '.' . $foto->extension();

            // Cek dan buat direktori jika belum ada
            $uploadPath = public_path('parkir');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Upload file
            if (!$foto->move($uploadPath, $fotoName)) {
                throw new \Exception('Gagal mengupload file');
            }

            // Create record
            $informasi = Informasi::create([
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'area' => $request->area,
                'user_id' => $request->user_id,
                'kapasitas' => $request->kapasitas,
                'foto' => 'parkir/' . $fotoName,
                'poin' => $request->poin ?? 0
            ]);

            // Update misi
            $misiController = new ApiMisiController();
            if (!$misiController->updateProgress($request->user_id)) {
                throw new \Exception('Gagal memperbarui progress misi');
            }

            DB::commit();
            return $this->createdResponse($informasi, 'Data berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded file if exists
            if (isset($fotoName)) {
                $fotoPath = public_path('parkir/' . $fotoName);
                if (file_exists($fotoPath)) {
                    unlink($fotoPath);
                }
            }

            Log::error('Store Informasi Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return $this->serverErrorResponse('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jenis_kendaraan' => 'required|in:motor,mobil',
                'area' => 'required|in:A,B,C,D,E,F,G',
                'user_id' => 'required|exists:users,id'
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors()->first());
            }

            $oneHourAgo = Carbon::now()->subHour();

            $informasi = Informasi::with(['user:id,name'])
                ->where('jenis_kendaraan', $request->jenis_kendaraan)
                ->where('area', $request->area)
                ->where('created_at', '>=', $oneHourAgo)
                ->withCount([
                    'konfirmasi' => function ($query) use ($request) {
                        $query->where('user_id', $request->user_id);
                    }
                ])
                ->orderBy('poin', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    $item->is_confirmed = $item->konfirmasi_count > 0;
                    unset($item->konfirmasi_count);
                    return $item;
                });

            if ($informasi->isEmpty()) {
                return $this->okResponse(null, 'Tidak ada data dalam 1 jam terakhir');
            }

            return $this->okResponse($informasi, 'Data berhasil diambil');

        } catch (\Exception $e) {
            Log::error('Index Informasi Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return $this->serverErrorResponse('Terjadi kesalahan saat mengambil data');
        }
    }
}