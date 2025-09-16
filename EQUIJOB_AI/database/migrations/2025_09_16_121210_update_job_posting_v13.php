<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jobPosting', function (Blueprint $table) {
            if (!Schema::hasColumn('jobPosting', 'category')) {
                $table->string('category')->nullable()->after('requirements');
            }
        });

        DB::statement("
            ALTER TABLE \"jobPosting\"
            ADD CONSTRAINT jobposting_category_check
            CHECK (category IN (
                'IT & Software', 'Healthcare', 'Education', 'Engineering',
                'Business & Finance', 'Sales & Marketing', 'Customer Service',
                'Human Resources', 'Design & Creatives', 'Hospitality & Tourism',
                'Construction', 'Manufacturing', 'Transport & Logistics',
                'Government', 'Science & Research', 'Other'
            ));
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE "jobPosting" DROP CONSTRAINT IF EXISTS jobposting_category_check');

        Schema::table('jobPosting', function (Blueprint $table) {
            if (Schema::hasColumn('jobPosting', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
