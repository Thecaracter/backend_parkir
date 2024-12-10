<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Informasi extends Model
{
    use HasFactory;

    protected $table = 'informasi';

    protected $fillable = [
        'jenis_kendaraan',
        'area',
        'user_id',
        'kapasitas',
        'foto',
        'poin'
    ];

    protected $casts = [
        'jenis_kendaraan' => 'string',
        'kapasitas' => 'integer',
        'poin' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function konfirmasi(): HasMany
    {
        return $this->hasMany(Konfirmasi::class);
    }
}