<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SimpananPokok extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_simpanan_pokok';
    protected $fillable = ['id_user', 'iuran', 'total_simpanan', 'status_simpanan'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function transaksiPokok(): HasMany
    {
        return $this->hasMany(TransaksiPokok::class, 'id_simpanan_pokok');
    }
}