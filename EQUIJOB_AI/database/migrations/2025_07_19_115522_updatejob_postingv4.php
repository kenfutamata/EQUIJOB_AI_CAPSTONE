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
        Schema::table('jobPosting', function (Blueprint $table) {
            if (Schema::hasColumn('jobPosting', 'status')) {
                $table->enum('status', ['Pending', 'For Posting', 'Occupied'])->default('Pending')->after('contactEmail');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobPosting', function (Blueprint $table) {
            if (Schema::hasColumn('jobPosting', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
