<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Hasmany;

class Pinjaman extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'pinjaman';
    protected $primaryKey = 'id_pinjaman';
    protected $fillable = ['id_user', 'tgl_pengajuan', 'besar_pinjaman', 'tenor_pinjaman', 'keterangan'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function tanggungan(): HasMany
    {
        return $this->hasMany(Tanggungan::class, 'id_pinjaman');
    }
}