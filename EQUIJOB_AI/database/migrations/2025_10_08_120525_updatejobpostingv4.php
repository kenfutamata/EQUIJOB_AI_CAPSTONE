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
        DB::statement('ALTER TABLE "jobPosting" DROP CONSTRAINT IF EXISTS "jobPosting_disabilityType_check"');

        DB::statement("
            UPDATE \"jobPosting\"
            SET \"disabilityType\" = NULL
            WHERE \"disabilityType\" IS NOT NULL
            AND \"disabilityType\" NOT IN (
                'Deaf or Hard of Hearing',
                'Intellectual Disability',
                'Learning Disability',
                'Mental Disability',
                'Physical Disability (Orthopedic)',
                'Psychosocial Disability',
                'Speech and Language Impairment',
                'Visual Disability',
                'Cancer (RA11215)',
                'Rare Disease (RA10747)'
            )
        ");

-        DB::statement("
            ALTER TABLE \"jobPosting\"
            ADD CONSTRAINT \"jobPosting_disabilityType_check\"
            CHECK (
                \"disabilityType\" IS NULL OR
                \"disabilityType\" IN (
                    'Deaf or Hard of Hearing',
                    'Intellectual Disability',
                    'Learning Disability',
                    'Mental Disability',
                    'Physical Disability (Orthopedic)',
                    'Psychosocial Disability',
                    'Speech and Language Impairment',
                    'Visual Disability',
                    'Cancer (RA11215)',
                    'Rare Disease (RA10747)'
                )
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE "jobPosting" DROP CONSTRAINT IF EXISTS "jobPosting_disabilityType_check"');
    }
};