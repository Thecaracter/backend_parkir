<?php

namespace App\Http\Controllers\Api;

use App\Models\Informasi;
use App\Models\Konfirmasi;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ApiKonfirmasiController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'informasi_id' => 'required|exists:informasi,id'
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors()->first());
            }

            // Check apakah sudah pernah konfirmasi
            $existingKonfirmasi = Konfirmasi::where('user_id', $request->user_id)
                ->where('informasi_id', $request->informasi_id)
                ->first();

            if ($existingKonfirmasi) {
                return $this->badRequestResponse('Anda sudah mengkonfirmasi informasi ini');
            }

            // Check apakah user mencoba konfirmasi postingannya sendiri
            $informasi = Informasi::with('user')->find($request->informasi_id);
            if ($informasi->user_id == $request->user_id) {
                return $this->badRequestResponse('Anda tidak bisa mengkonfirmasi postingan sendiri');
            }

            // Buat konfirmasi baru
            $konfirmasi = Konfirmasi::create([
                'user_id' => $request->user_id,
                'informasi_id' => $request->informasi_id
            ]);

            // Tambah 5 poin ke pembuat postingan
            $informasi->user->increment('poin', 5);

            // Tambah 5 poin ke informasi
            $informasi->increment('poin', 5);

            return $this->createdResponse($konfirmasi, 'Konfirmasi berhasil ditambahkan');

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Terjadi kesalahan saat menambah konfirmasi');
        }
    }
}
