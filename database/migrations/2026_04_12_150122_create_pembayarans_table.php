<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();

            // relasi ke tabel periksa (manual biar aman)
            $table->unsignedBigInteger('periksa_id');

            $table->foreign('periksa_id')
                  ->references('id')
                  ->on('periksa')
                  ->cascadeOnDelete();

            // total biaya dari pemeriksaan
            $table->integer('total_bayar');

            // file bukti pembayaran (gambar)
            $table->string('bukti_pembayaran')->nullable();

            // status pembayaran
            $table->enum('status', ['pending', 'lunas'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
