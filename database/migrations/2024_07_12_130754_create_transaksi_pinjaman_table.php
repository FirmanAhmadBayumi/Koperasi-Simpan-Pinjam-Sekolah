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
        Schema::create('transaksi_pinjaman', function (Blueprint $table) {
            $table->id('id_transaksiPinjaman');
            //foreign key
            $table->unsignedBigInteger('id_tanggungan');
            $table->foreign('id_tanggungan')->references('id_tanggungan')->on('tanggungan')->onDelete('cascade');

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
        Schema::dropIfExists('transaksi_pinjamen');
    }
};
