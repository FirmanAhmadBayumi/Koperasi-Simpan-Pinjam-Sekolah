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
        Schema::create('transaksi_pokoks', function (Blueprint $table) {
            $table->id('id_transaksiPokok');
            //foreign key
            $table->unsignedBigInteger('id_simpanan_pokok');
            $table->foreign('id_simpanan_pokok')->references('id_simpanan_pokok')->on('simpanan_pokoks')->onDelete('cascade');

            $table->timestamp('jatuh_tempo');
            $table->timestamp('tanggal_pembayaran')->nullable();
            $table->string('snap_token')->nullable();
            $table->string('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pokoks');
    }
};
