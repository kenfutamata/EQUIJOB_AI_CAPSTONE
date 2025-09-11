<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the old constraint if it exists
        DB::statement('ALTER TABLE "jobPosting" DROP CONSTRAINT IF EXISTS "jobPosting_status_check"');

        // Add the new constraint with Disapproved included
        DB::statement("
            ALTER TABLE \"jobPosting\"
            ADD CONSTRAINT jobPosting_status_check
            CHECK (status IN ('Pending', 'For Posting', 'Disapproved', 'Occupied'))
        ");
    }

    public function down(): void
    {
        // Rollback to only Pending + For Posting
        DB::statement('ALTER TABLE "jobPosting" DROP CONSTRAINT IF EXISTS "jobPosting_status_check"');

        DB::statement("
            ALTER TABLE \"jobPosting\"
            ADD CONSTRAINT jobPosting_status_check
            CHECK (status IN ('Pending', 'For Posting'))
        ");
    }
};
