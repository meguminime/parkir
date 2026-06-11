<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parkir_locations', function (Blueprint $table) {
            $table->id();
            $table->string('location_name', 100);
            $table->integer('max_motorcycle');
            $table->integer('max_car');
            $table->integer('max_other');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parkir_locations');
    }
};
