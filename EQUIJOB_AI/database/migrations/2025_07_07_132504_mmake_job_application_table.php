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
            $table->integer('applicantID')->unsigned();
            $table->integer('jobID')->unsigned();
            $table->string('applicantName');
            $table->string('applicantEmail');
            $table->string('applicantPhone');
            $table->string('applicantAddress');
            $table->string('applicantSex');
            $table->string('applicantAge');
            $table->string('applicantDisabilityType');
            $table->string('uploadResume');
            $table->enum('status', ['Pending', 'For Interview', 'Accepted', 'Rejected'])->default('Pending');
            $table->string('interviewDate')->nullable();
            $table->string('interviewTime')->nullable();
            $table->string('remarks')->nullable();
            $table->text('googleAccessToken')->nullable();
            $table->text('googleRefreshToken')->nullable();
            $table->timestamp('googleTokenExpiry')->nullable(); 
            $table->foreign('applicantID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('jobID')->references('id')->on('job_posting')->onDelete('cascade');
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
