<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KonfigurasiPinjaman extends Model
{
    use HasFactory;
    protected $table = 'konfigurasi_pinjaman';
    protected $primaryKey = 'id_konfigurasiPinjaman';
    protected $fillable = ['id_user', 'bunga_pinjaman', 'maks_pinjaman', 'maks_tenor'];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
