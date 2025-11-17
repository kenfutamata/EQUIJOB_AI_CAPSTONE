<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create PROVINCES table
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('provinceName');
        });

        // Create CITIES table
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provinceId')
                  ->constrained('provinces', 'id')
                  ->cascadeOnDelete();
            $table->string('cityName');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
        Schema::dropIfExists('provinces');
    }
};
