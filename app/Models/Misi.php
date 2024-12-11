<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Misi extends Model
{
    use HasFactory;

    protected $table = 'misi';

    protected $fillable = [
        'user_id',
        'tipe',
        'jumlah',
        'selesai',
        'poin_diklaim'
    ];

    protected $casts = [
        'selesai' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}