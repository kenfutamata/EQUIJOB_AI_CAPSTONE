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
            if (!Schema::hasColumn('job_posting', 'company_logo')) {
                $table->string('company_logo')->nullable()->after('sex');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_posting', function (Blueprint $table) {
            if (Schema::hasColumn('job_posting', 'company_logo')) {
                $table->dropColumn('company_logo');
            }
        });
    }
};
