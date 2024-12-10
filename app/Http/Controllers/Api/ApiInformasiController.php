<?php

namespace App\Http\Controllers\Api;

use App\Models\Informasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

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

        try {
            $foto = $request->file('foto');

            $uniqueDigits = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $fotoName = date('Ymd') . '_' . $uniqueDigits . '.' . $foto->extension();

            $foto->move(public_path('parkir'), $fotoName);

            $informasi = Informasi::create([
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'area' => $request->area,
                'user_id' => $request->user_id,
                'kapasitas' => $request->kapasitas,
                'foto' => 'parkir/' . $fotoName,
                'poin' => $request->poin ?? 0
            ]);

            return $this->createdResponse($informasi, 'Data berhasil ditambahkan');

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Terjadi kesalahan saat menyimpan data');
        }
    }

    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jenis_kendaraan' => 'required|in:motor,mobil',
                'area' => 'required|in:A,B,C,D,E,F,G'
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors()->first());
            }

            $oneHourAgo = Carbon::now()->subHour();

            $informasi = Informasi::where('jenis_kendaraan', $request->jenis_kendaraan)
                ->where('area', $request->area)
                ->where('created_at', '>=', $oneHourAgo)
                ->orderBy('poin', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            if ($informasi->isEmpty()) {
                return $this->okResponse(null, 'Tidak ada data dalam 1 jam terakhir');
            }

            return $this->okResponse($informasi, 'Data berhasil diambil');

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Terjadi kesalahan saat mengambil data');
        }
    }
}