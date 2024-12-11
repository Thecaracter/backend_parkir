<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiBerandaController;
use App\Http\Controllers\Api\ApiRankingController;
use App\Http\Controllers\Api\ApiInformasiController;
use App\Http\Controllers\Api\ApiKonfirmasiController;
use App\Http\Controllers\Api\ApiMisiController;

Route::prefix('v1')->group(function () {

    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::post('/profile', [ApiAuthController::class, 'getProfile']);
    Route::post('/profile/update', [ApiAuthController::class, 'updateProfile']);


    Route::post('/informasi/create', [ApiInformasiController::class, 'store']);
    Route::post('/informasi', [ApiInformasiController::class, 'index']);


    Route::post('/konfirmasi', [ApiKonfirmasiController::class, 'store']);


    Route::get('/ranking', [ApiRankingController::class, 'index']);


    Route::get('/beranda/latest-confirmed', [ApiBerandaController::class, 'getLatestConfirmed']);


    Route::post('/misi', [ApiMisiController::class, 'getMisi']);
    Route::post('/misi/claim', [ApiMisiController::class, 'claimPoin']);
});