<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'poin' => 0,
            ]);

            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'poin' => $user->poin
            ];

            return $this->createdResponse($userData, 'Registrasi berhasil');

        } catch (\Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->unauthorizedResponse('Email atau password salah');
            }

            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'poin' => $user->poin
            ];

            return $this->okResponse($userData, 'Login berhasil');

        } catch (\Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function getProfile(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|numeric'
            ]);

            $user = User::find($request->user_id);

            if (!$user) {
                return $this->notFoundResponse('User tidak ditemukan');
            }

            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'poin' => $user->poin
            ];

            return $this->okResponse($userData, 'Data profile berhasil diambil');

        } catch (\Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|numeric',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255'
            ]);

            $user = User::find($request->user_id);

            if (!$user) {
                return $this->notFoundResponse('User tidak ditemukan');
            }

            $existingUser = User::where('email', $request->email)
                ->where('id', '!=', $request->user_id)
                ->first();

            if ($existingUser) {
                return $this->badRequestResponse('Email sudah digunakan');
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'poin' => $user->poin
            ];

            return $this->okResponse($userData, 'Profile berhasil diupdate');

        } catch (\Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }
}