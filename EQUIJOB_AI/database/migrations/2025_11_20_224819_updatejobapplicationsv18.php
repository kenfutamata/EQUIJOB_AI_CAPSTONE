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
            $table->date('dateHired')->nullable()->after('updated_at');
            $table->date('dateDisapproved')->nullable()->after('dateHired');
            $table->date('dateWithdrawn')->nullable()->after('dateDisapproved');
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
