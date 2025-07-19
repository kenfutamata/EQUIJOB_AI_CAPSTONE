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
            if (!Schema::hasColumn('jobPosting', 'sex')) {
                $table->enum('sex', ['All', 'Male', 'Female'])->default('All')->after('companyName');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobPosting', function (Blueprint $table) {
            if (Schema::hasColumn('jobPosting', 'sex')) {
                $table->dropColumn('sex');
            }
        });
    }
};
