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
        Schema::create('job_posting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_provider_id')->constrained('users')->onDelete('cascade');
            $table->string('title'); 
            $table->string('description'); 
            $table->string('skills'); 
            $table->string('requirements'); 
            $table->string('location'); 
            $table->string('salary_range');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posting');
    }
};
