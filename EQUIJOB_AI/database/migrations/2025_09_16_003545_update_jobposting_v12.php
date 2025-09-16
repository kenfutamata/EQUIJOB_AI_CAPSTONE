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
            if(!Schema::hasColumn('jobPosting', 'category')) {
                $table->enum('category', ['IT & Software', 'Healthcare', 'Education', 'Engineering', 'Business & Finance', 'Sales & Marketing', 
                'Customer Service', 'Human Resources', 'Design & Creatives', 'Hospitality & Tourism', 'Constuction', 'Manufacturing', 
                'Transport & Logistics', 'Government', 'Science & Research', 'Other'])->after('requirements');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobPosting', function (Blueprint $table) {
            if(Schema::hasColumn('jobPosting', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
