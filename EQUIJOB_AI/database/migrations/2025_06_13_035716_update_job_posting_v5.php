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
            if (!Schema::hasColumn('job_posting', 'status')) {
                $table->enum('status',['Pending', 'Disapproved', 'Posted'])->nullable()->after('salary_range');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_posting', function (Blueprint $table) {
            if (Schema::hasColumn('job_posting', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
