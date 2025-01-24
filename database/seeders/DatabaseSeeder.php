<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pinjaman;
use App\Models\Tanggungan;
use App\Models\SimpananPokok;
use App\Models\TransaksiPokok;
use Illuminate\Database\Seeder;
use App\Models\TransaksiPinjaman;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // // Buat satu pengguna dengan usertype 'admin'
        // User::factory()->create([
        //     'usertype' => 'admin',
        // ]);

        // // Buat 12 pengguna lainnya sebagai pengguna biasa
        // User::factory(12)->create()->each(function ($user) {
        //     // Buat simpanan pokok untuk setiap user
        //     SimpananPokok::factory()->create([
        //         'id_user' => $user->id_user,
        //     ]);
        // });

        // Buat 1 pengguna dengan role "admin"
        User::factory()->admin()->create([
            'email' => 'admin@example.com',
        ]);

        // Buat 5 pengguna dengan role "user"
        User::factory()->count(5)->create();
    }
}