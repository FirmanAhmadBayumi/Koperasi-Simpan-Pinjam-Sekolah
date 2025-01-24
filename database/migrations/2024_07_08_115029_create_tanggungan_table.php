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

            $table->float('bunga_pinjaman')->default(0.08);
            $table->float('total_pinjaman');
            $table->float('iuran_perBulan');
            $table->float('sisa_pinjaman');
            $table->float('sisa_tenor');
            $table->string('status_pinjaman');
            $table->string('snap_tokenLunas')->nullable();
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
