<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parkir_vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['motorcycle', 'car', 'other']);
            $table->integer('perjam_pertama');
            $table->integer('perjam_berikutnya');
            $table->integer('max_perhari');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parkir_vehicle_types');
    }
};