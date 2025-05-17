<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tanggungan', function (Blueprint $table) {
            $table->id('id_tanggungan');
            //foreign key
            $table->unsignedBigInteger('id_pinjaman');
            $table->foreign('id_pinjaman')->references('id_pinjaman')->on('pinjaman')->onDelete('cascade');

            $table->double('bunga_pinjaman');
            $table->double('total_pinjaman');
            $table->double('iuran_perBulan');
            $table->double('sisa_pinjaman');
            $table->double('sisa_tenor');
            $table->enum('status_pinjaman', ['Lunas', 'Belum Lunas']);
            $table->string('snap_tokenLunas', 40)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanggungan');
    }
};
