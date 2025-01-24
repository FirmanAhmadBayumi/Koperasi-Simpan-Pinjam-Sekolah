<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'usertype',
        'email',
        'NIP',
        'jenis_kelamin',
        'alamat',
        'no_tlp',
        'shu',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function simpananPokok(): HasMany
    {
        return $this->hasMany(SimpananPokok::class, 'id_simpanan_pokok');
    }

    public function pinjaman(): HasMany
    {
        return $this->hasMany(Pinjaman::class, 'id_user');
    }

    public function tanggungan(): HasOne{
        return $this->hasOne(Tanggungan::class, 'id_tanggungan');   
    }

    public function transaksiPokok(): HasOne{
        return $this->hasOne(TransaksiPokok::class, 'id_transaksiPokok');
    }
}
