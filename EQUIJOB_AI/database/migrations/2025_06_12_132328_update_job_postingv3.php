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
        Schema::table('job_posting', function (Blueprint $table) {
            if (!Schema::hasColumn('job_posting', 'experience')) {
                $table->text('experience')->nullable()->after('job_posting_objectives');
            }
            if (!Schema::hasColumn('job_posting', 'skills')) {
                $table->text('skills')->nullable()->after('experience');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_posting', function (Blueprint $table) {
            if (Schema::hasColumn('job_posting', 'experience')) {
                $table->dropColumn('experience');
            }
            if (Schema::hasColumn('job_posting', 'skills')) {
                $table->dropColumn('skills');
            }
        });
    }
};
