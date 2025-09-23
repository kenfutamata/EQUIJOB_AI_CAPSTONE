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
        Schema::table('resume', function (Blueprint $table) {
            $table->string('firstName', 50)->change();
            $table->string('lastName', 50)->change();
            $table->string('address', 100)->nullable()->change();
            $table->string('email', 100)->change();
            $table->string('phone', 20)->nullable()->change();
            $table->string('photo', 255)->nullable()->change();
        });
        Schema::table('experiences', function (Blueprint $table) {
            $table->string('jobTitle', 100)->nullable()->change();
            $table->string('employer', 100)->nullable()->change();
            $table->string('location', 100)->nullable()->change();
            $table->string('year', 20)->nullable()->change();
            $table->text('description')->nullable()->change();
        });
        Schema::table('educations', function (Blueprint $table) {
            $table->string('school', 100)->nullable()->change();
            $table->string('degree', 100)->nullable()->change();
            $table->string('location', 100)->nullable()->change();
            $table->string('year', 20)->nullable()->change();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resume', function (Blueprint $table) {
            $table->string('firstName')->change();
            $table->string('lastName')->change();
            $table->string('address')->nullable()->change();
            $table->string('email')->change();
            $table->string('phone')->nullable()->change();
            $table->string('photo')->nullable()->change();
        });
        Schema::table('experiences', function (Blueprint $table) {
            $table->string('jobTitle')->nullable()->change();
            $table->string('employer')->nullable()->change();
            $table->string('location')->nullable()->change();
            $table->string('year')->nullable()->change();
            $table->text('description')->nullable()->change();
        });
        Schema::table('educations', function (Blueprint $table) {
            $table->string('school')->nullable()->change();
            $table->string('degree')->nullable()->change();
            $table->string('location')->nullable()->change();
            $table->string('year')->nullable()->change();
        });
    }
};
