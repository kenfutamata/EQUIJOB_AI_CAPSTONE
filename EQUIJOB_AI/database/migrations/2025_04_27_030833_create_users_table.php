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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('address')->nullable();
            $table->string('phoneNumber');
            $table->date('dateOfBirth')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->enum('typeOfDisability', ['Physical', 'Visual', 'Hearing', 'Intellectual'])->nullable();
            $table->string('pwdId')->nullable();
            $table->string('upload_pwd_card')->nullable();
            $table->enum('role', ['Applicant', 'Job Provider', 'Admin']);
            $table->enum('status', ['Active', 'Inactive'])->default('inactive');
            $table->string('companyName')->nullable();
            $table->string('companyLogo')->nullable();
            $table->string('profilePicture')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('userID')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
