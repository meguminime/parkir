<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parkir_transactions', function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel parkir_locations
            $table->foreignId('id_lokasi')->constrained('parkir_locations')->onDelete('cascade');
            $table->string('no_tiket', 255)->unique();
            $table->string('no_polisi', 15)->nullable();
            // Foreign key ke tabel parkir_vehicle_types
            $table->foreignId('id_jenis')->constrained('parkir_vehicle_types')->onDelete('cascade');
            $table->dateTime('masuk');
            $table->dateTime('keluar')->nullable();
            $table->integer('perjam_pertama');
            $table->integer('perjam_berikutnya');
            $table->integer('max_perhari');
            $table->integer('total_jam')->nullable();
            $table->integer('total_bayar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parkir_transactions');
    }
};