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
        Schema::create('pencairan_pinjaman', function (Blueprint $table) {
            $table->id('id_pencairan_pinjaman');
            $table->unsignedBigInteger('id_pinjaman');
            $table->foreign('id_pinjaman')->references('id_pinjaman')->on('pinjaman')->onDelete('cascade');
            $table->enum('metode_pengiriman_pinjaman', ['Transfer Rekening', 'Tunai']);
            $table->string('nomor_rekening')->nullable();
            $table->string('nama_bank')->nullable();
            $table->timestamp('tgl_pengajuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencairan_pinjaman');
    }
};
