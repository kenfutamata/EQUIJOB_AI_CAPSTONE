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
        // First, drop the old, potentially corrupted constraint just in case.
        DB::statement('ALTER TABLE "resume" DROP CONSTRAINT IF EXISTS "resume_typeOfDisability_check"');

        // Now, add the new, clean, and correct constraint.
        DB::statement("
            ALTER TABLE \"resume\"
            ADD CONSTRAINT \"resume_typeOfDisability_check\"
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE "resume" DROP CONSTRAINT IF EXISTS "resume_typeOfDisability_check"');
    }
};