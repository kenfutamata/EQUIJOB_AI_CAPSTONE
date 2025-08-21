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
        Schema::table('jobApplications', function (Blueprint $table) {
            if (!Schema::hasColumn('jobApplications', 'reminderSentAt')) {
                $table->timestamp('reminderSentAt')->nullable()->after('interviewDate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobApplications', function (Blueprint $table) {
            if (Schema::hasColumn('jobApplications', 'reminderSentAt')) {
                $table->dropColumn('reminderSentAt');
            }
        });
    }
};
