<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiInformasiController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::post('/profile', [ApiAuthController::class, 'getProfile']);
    Route::post('/profile/update', [ApiAuthController::class, 'updateProfile']);

    // Informasi routes
    Route::post('/informasi/create', [ApiInformasiController::class, 'store']);
    Route::post('/informasi', [ApiInformasiController::class, 'index']);
});