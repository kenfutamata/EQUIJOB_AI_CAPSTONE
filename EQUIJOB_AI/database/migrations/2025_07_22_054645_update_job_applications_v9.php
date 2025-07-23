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
            if (!Schema::hasColumn('jobApplications', 'status')) {
                $table->enum('status', ['Pending', 'For Interview', 'On-Offer', 'Accepted', 'Rejected', 'Withdrawn'])->default('Pending')->after('uploadResume');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
