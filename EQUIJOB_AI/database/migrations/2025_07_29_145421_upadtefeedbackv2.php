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
        Schema::table('feedbacks', function (Blueprint $table) {
            if (!Schema::hasColumn('feedbacks', 'feedbackType')) {
                $table->enum('feedbackType', ['Job Rating',  'Job Application Issues', 'AI-Job Matching Issues', 'Resume Builder Problems', 'Other'])->default('Other')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            if (Schema::hasColumn('feedbacks', 'feedbackType')) {
                $table->dropColumn('feedbackType');
            }
        });
    }
};
