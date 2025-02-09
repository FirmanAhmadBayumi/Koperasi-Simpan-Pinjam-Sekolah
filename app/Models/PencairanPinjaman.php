<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PencairanPinjaman extends Model
{
    use HasFactory;

    protected $table = 'pencairan_pinjaman';
    protected $primaryKey = 'id_pencairanPinjaman';

    protected $fillable = [
        'id_pinjaman',
        'metode_pengiriman_pinjaman',
        'nomor_rekening',
        'nama_bank',
        'keterangan',
        'tgl_pengajuan',
        'tgl_verifikasi',
    ];

    // Relasi ke tabel Pinjaman
    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'id_pinjaman');
    }
}
