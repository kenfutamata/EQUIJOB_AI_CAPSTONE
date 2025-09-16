<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $enumType = 'job_category_enum';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            DO $$
            BEGIN
                IF NOT EXISTS (
                    SELECT 1 FROM pg_type WHERE typname = '{$this->enumType}'
                ) THEN
                    CREATE TYPE {$this->enumType} AS ENUM (
                        'IT & Software', 'Healthcare', 'Education', 'Engineering',
                        'Business & Finance', 'Sales & Marketing', 'Customer Service',
                        'Human Resources', 'Design & Creatives', 'Hospitality & Tourism',
                        'Construction', 'Manufacturing', 'Transport & Logistics',
                        'Government', 'Science & Research', 'Other'
                    );
                END IF;
            END
            $$;
        ");

        if (!Schema::hasColumn('jobPosting', 'category')) {
            DB::statement("ALTER TABLE \"jobPosting\" ADD COLUMN category {$this->enumType} NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Drop column if exists
        if (Schema::hasColumn('jobPosting', 'category')) {
            Schema::table('jobPosting', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }

        // 2. Drop ENUM type if exists
        DB::statement("DROP TYPE IF EXISTS {$this->enumType}");
    }
};
