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
            $table->string('postion'); 
            $table->string('companyName');
            $table->enum('sex', ['Male', 'Female']);
            $table->integer('age'); 
            $table->enum('disability_type', ['Physical', 'Visual', 'Hearing', 'Intellectual']);
            $table->string('educational_attainment'); 
            $table->string('salary_range'); 
            $table->text('job_posting_objectives'); 
            $table->text('requirements'); 
            $table->string('status')->default('Pending'); // e.g., active, closed, pending
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
