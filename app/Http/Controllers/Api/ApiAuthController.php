<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
            // Validasi input
            $validated = $request->validate([
                'id' => 'required|numeric|exists:users,id',
                'name' => 'nullable|string|max:255',
                'email' => [
                    'nullable',
                    'string',
                    'email',
                    'max:255',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value) {
                            $exists = User::where('email', $value)
                                ->where('id', '!=', $request->id)
                                ->exists();

                            if ($exists) {
                                $fail('Email sudah digunakan.');
                            }
                        }
                    }
                ]
            ]);

            $user = User::find($request->id);

            // Filter data yang akan diupdate
            $updateData = array_filter([
                'name' => $request->name,
                'email' => $request->email
            ], function ($value) {
                return !is_null($value);
            });

            // Update data
            $user->update($updateData);

            return $this->okResponse([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'poin' => $user->poin
            ], 'Profile berhasil diupdate');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->getMessage());
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }
}