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
        Schema::create('jobPosting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobProviderID')->constrained('users')->onDelete('cascade');
            $table->string('postion');
            $table->string('companyName');
            $table->enum('sex', ['Male', 'Female']);
            $table->string('companyLogo')->nullable();
            $table->integer('age');
            $table->enum('disabilityType', ['Physical', 'Visual', 'Hearing', 'Intellectual']);
            $table->string('educationalAttainment');
            $table->string('salaryRange');
            $table->text('jobPostingObjectives');
            $table->text('experience')->nullable();
            $table->text('skills')->nullable();
            $table->text('description')->nullable();
            $table->text('requirements');
            $table->string('status')->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobPosting');
    }
};
