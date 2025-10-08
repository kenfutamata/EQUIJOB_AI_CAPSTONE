<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old constraint if any
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_typeofdisability_check');

        // Normalize invalid entries to NULL
        DB::statement("
            UPDATE users
            SET \"typeOfDisability\" = NULL
            WHERE \"typeOfDisability\" IS NOT NULL
            AND \"typeOfDisability\" NOT IN (
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

        DB::statement("
            ALTER TABLE users
            ADD CONSTRAINT users_typeofdisability_check
            CHECK (
                \"typeOfDisability\" IS NULL OR
                \"typeOfDisability\" IN (
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

    public function down(): void
    {
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_typeofdisability_check');
    }
};
