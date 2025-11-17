<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB; // <-- Import the DB facade
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For PostgreSQL, we need to use a raw DB statement to modify an ENUM (which is a CHECK constraint).
        // The change() method is not reliable for this RDBMS.

        // 1. Drop the old CHECK constraint. The default name is `table_column_check`.
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_status_check');

        // 2. Add the new CHECK constraint with the updated values.
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_status_check CHECK (status IN ('Inactive', 'For Activation', 'Active'))");

        // 3. Ensure the default is set correctly (optional, but good practice to be explicit).
        DB::statement("ALTER TABLE users ALTER COLUMN status SET DEFAULT 'Inactive'");
    }

    /**
     * Reverse the migrations.
     * This is an example of how you might reverse it. You'd revert to the previous state.
     * We assume the previous state was ('Inactive', 'Active').
     */
    public function down(): void
    {
        // 1. Drop the new CHECK constraint.
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_status_check');

        // 2. Re-add the old one (if you know what it was).
        // This makes the migration truly reversible.
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_status_check CHECK (status IN ('Inactive', 'Active'))");
        
        // 3. Reset the default.
        DB::statement("ALTER TABLE users ALTER COLUMN status SET DEFAULT 'Inactive'");
    }
};