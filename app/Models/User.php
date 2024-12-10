<?php

namespace App\Models;

use App\Models\Informasi;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'poin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'poin' => 'integer',
    ];

    protected $attributes = [
        'poin' => 0,
    ];

    public function informasi(): HasMany
    {
        return $this->hasMany(Informasi::class);
    }
    public function konfirmasi(): HasMany
    {
        return $this->hasMany(Konfirmasi::class);
    }
}