<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfilSekolah extends Model
{
    use HasFactory;
    protected $table = 'profil_sekolah';
    protected $primaryKey = 'id_profilSekolah';
    protected $fillable = ['id_user', 'logo_sekolah', 'nama_sekolah', 'alamat_sekolah'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
