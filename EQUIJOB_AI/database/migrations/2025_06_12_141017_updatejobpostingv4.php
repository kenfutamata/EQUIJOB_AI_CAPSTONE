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
            if (!Schema::hasColumn('job_posting', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('requirements');
            }
            if (!Schema::hasColumn('job_posting', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('requirements');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_posting', function (Blueprint $table) {
            if (Schema::hasColumn('job_posting', 'contact_phone')) {
                $table->dropColumn('contact_phone');
            }
            if (Schema::hasColumn('job_posting', 'contact_email')) {
                $table->dropColumn('contact_email');
            }
        });
    }
};
