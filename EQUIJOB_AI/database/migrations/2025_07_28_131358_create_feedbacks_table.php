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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobPostingID')->constrained('jobPosting')->onDelete('cascade')->nullable();
            $table->foreignId('applicantID')->constrained('users')->onDelete('cascade')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('email')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->text('feedbackText')->nullable();
            $table->enum('rating', ['1', '2', '3', '4', '5'])->default('5')->nullable();
            $table->timestamps();
            $allowedTypes = [
                'Job Application Issues',
                'AI-Job Matching Issues',
                'Resume Builder Problems',
                'Other',
                'Job Rating',
                'Contact Us'
            ];
            $table->string('feedbackType')->checkIn($allowedTypes);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
