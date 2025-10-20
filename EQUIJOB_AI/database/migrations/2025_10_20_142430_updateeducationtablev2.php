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
        // You may need to run "composer require doctrine/dbal" for this to work.
        Schema::table('educations', function (Blueprint $table) {
            $table->string('school', 255)->nullable()->change();
            $table->string('degree', 255)->nullable()->change();
            $table->string('location', 255)->nullable()->change();
            $table->text('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('educations', function (Blueprint $table) {
            $table->string('school', 100)->nullable()->change();
            $table->string('degree', 100)->nullable()->change();
            $table->string('location', 100)->nullable()->change();
            $table->string('description', 255)->nullable()->change();
        });
    }
};