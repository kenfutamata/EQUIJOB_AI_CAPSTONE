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
            $table->string('position',100)->change();
            $table->string('companyName', 100)->change();
            $table->string('companyLogo', 100)->change();
            $table->string('educationalAttainment', 100)->nullable()->change();
            $table->string('salaryRange', 100)->nullable()->change();
            $table->string('contactPhone', 20)->change();
            $table->string('contactEmail', 100)->change();
            $table->date('date_of_birth')->nullable()->after('address');
            $table->string('profile_picture')->nullable()->after('date_of_birth');
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
