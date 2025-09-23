<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Define the name of the constraint to make the code cleaner
        $constraintName = 'resume_typeOfDisability_check';

        // Use a try-catch block in case the constraint doesn't exist, to avoid errors
        try {
            // First, attempt to drop the old constraint
            DB::statement("ALTER TABLE resume DROP CONSTRAINT {$constraintName};");
        } catch (\Illuminate\Database\QueryException $e) {
            // Log if the constraint didn't exist, but don't stop the migration
            \Illuminate\Support\Facades\Log::info("Constraint {$constraintName} did not exist, creating new one.");
        }

        // IMPORTANT: List ALL the values you want to allow in this check.
        // I have included the most common ones plus 'Not Specified'.
        // Add or remove values to match your application's needs.
        $allowedValues = [
            'Physical',
            'Hearing',
            'Visual',
            'Mental',
            'Intellectual',
            'None',
            'Not Specified' // The crucial addition
        ];

        // Create a comma-separated string of quoted values for the SQL query
        $sqlValues = "'" . implode("', '", $allowedValues) . "'";

        // Now, add the new, correct constraint with the complete list of allowed values
        DB::statement("
            ALTER TABLE resume ADD CONSTRAINT {$constraintName}
            CHECK (typeOfDisability IN ({$sqlValues}))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is the code to run if you ever need to "undo" this migration.
        // It's good practice to include it.
        $constraintName = 'resume_typeOfDisability_check';
        DB::statement("ALTER TABLE resume DROP CONSTRAINT {$constraintName};");
    }
};