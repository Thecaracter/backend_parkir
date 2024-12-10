<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;

class ApiRankingController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {
            $rankings = User::orderBy('poin', 'desc')
                ->get()
                ->map(function ($user, $index) {
                    return [
                        'name' => $user->name,
                        'point' => $user->poin,
                        'rank' => 'Rank ' . ($index + 1)
                    ];
                });

            return $this->okResponse($rankings, 'Data ranking berhasil diambil');

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Terjadi kesalahan saat mengambil data ranking');
        }
    }
}