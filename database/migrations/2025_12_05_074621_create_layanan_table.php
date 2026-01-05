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
        Schema::create('layanan', function (Blueprint $table) {
            $table->id();
            $table->string('foto_layanan', 250);
            $table->string('nama_layanan', 250);
            $table->text('deskripsi_layanan');
            $table->boolean('status')->default(true); // Perbaikan: boolean tidak butuh length
            $table->timestamps(); // Tambahkan ini jika ingin tracking waktu
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan');
    }
};
