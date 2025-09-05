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
        Schema::table('jobPosting', function (Blueprint $table) {
            $table->string('educationalAttainment')->nullable()->change();
            $table->text('experience')->nullable()->change();
            $table->text('skills')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobPosting', function (Blueprint $table) {
            $table->string('educationalAttainment')->nullable(false)->change();
            $table->text('experience')->nullable()->change(false);
            $table->text('skills')->nullable()->change(false);
        });
    }
};
