<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiPinjaman extends Model
{
    use HasFactory;

    public $timestamps = false; // Menonaktifkan timestamps
    protected $table = 'transaksi_pinjaman';
    protected $primaryKey = 'id_transaksiPinjaman';
    protected $fillable = ['id_tanggungan', 'jatuh_tempo', 'tanggal_pembayaran', 'snap_token', 'keterangan'];

    // Relasi dengan Tanggungan
    public function tanggungan(): BelongsTo
    {
        return $this->belongsTo(Tanggungan::class, 'id_tanggungan');
    }
}