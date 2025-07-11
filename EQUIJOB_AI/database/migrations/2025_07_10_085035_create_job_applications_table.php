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
        Schema::create('jobApplications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobPostingID')->constrained('jobPosting')->onDelete('cascade');
            $table->foreignId('applicantID')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Pending', 'For Interview', 'For Hired', 'Rejected'])->default('Pending');
            $table->string('uploadResume');
            $table->string('uploadApplicationLetter');
            $table->date('interviewDate')->nullable();
            $table->time('interviewTime')->nullable();
            $table->text('googleAccessToken')->nullable();
            $table->text('googleRefreshToken')->nullable();
            $table->timestamp('googleTokenExpiry')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobApplications');
    }
};
