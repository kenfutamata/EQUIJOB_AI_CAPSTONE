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
            if (!Schema::hasColumn('jobPosting', 'contactPhone')) {
                $table->string('contactPhone', 11)->nullable()->after('requirements');
            }
            if (!Schema::hasColumn('jobPosting', 'contactEmail')) {
                $table->string('contactEmail', 100)->nullable()->after('requirements');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobPosting', function (Blueprint $table) {
            if (Schema::hasColumn('jobPosting', 'contactPhone')) {
                $table->dropColumn('contactPhone');
            }
            if (Schema::hasColumn('jobPosting', 'contactEmail')) {
                $table->dropColumn('contactEmail');
            }
        });
    }
};
