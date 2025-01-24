<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tanggungan extends Model
{
    use HasFactory;

    protected $table = 'tanggungan';
    protected $primaryKey = 'id_tanggungan';
    protected $fillable = ['id_pinjaman', 'bunga_pinjaman', 'iuran_perBulan', 'total_pinjaman', 'sisa_pinjaman', 'sisa_tenor', 'status_pinjaman', 'snap_tokenLunas'];

    public function pinjaman(): BelongsTo
    {
        return $this->belongsTo(Pinjaman::class, 'id_pinjaman');
    }

    public function transaksiPinjaman(): HasMany
    {
        return $this->hasMany(TransaksiPinjaman::class, 'id_tanggungan');
    }
}
