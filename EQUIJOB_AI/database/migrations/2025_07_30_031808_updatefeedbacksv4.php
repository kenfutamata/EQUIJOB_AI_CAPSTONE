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
            $table->foreignId('jobPostingID')->nullable()->constrained('jobPosting')->onDelete('cascade');
            $table->foreignId('applicantID')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('email')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->enum('feedbackType', ['Job Rating',  'Job Application Issues', 'AI-Job Matching Issues', 'Resume Builder Problems', 'Other']);
            $table->text('feedbackText')->nullable();
            $table->enum('rating', ['1', '2', '3', '4', '5'])->nullable();
            $table->timestamps();
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
