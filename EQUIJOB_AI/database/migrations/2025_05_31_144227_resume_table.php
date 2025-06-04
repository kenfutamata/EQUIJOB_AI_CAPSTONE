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
        Schema::create('resume', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name'); 
            $table->date('dob')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('experience')->nullable();
            $table->string('photo')->nullable(); 
            $table->text('summary')->nullable();
            $table->text('skills')->nullable();
            $table->text('ai_generated_summary')->nullable();
            $table->timestamps();
        });

            Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resume_id')->constrained('resume')->onDelete('cascade');
            $table->string('employer')->nullable(); 
            $table->string('job_title')->nullable();
            $table->string('location')->nullable();
            $table->string('year')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

            Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resume_id')->constrained('resume')->onDelete('cascade');
            $table->string('school')->nullable();
            $table->string('degree')->nullable();
            $table->string('location')->nullable();
            $table->string('year')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume');
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('educations');
        Schema::table('resume', function (Blueprint $table) {
            $table->dropColumn(['ai_generated_summary']);
        });

    }
};
