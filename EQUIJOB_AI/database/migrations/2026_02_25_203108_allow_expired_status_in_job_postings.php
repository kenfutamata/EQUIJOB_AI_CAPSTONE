<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // This removes the restriction entirely so 'Expired' is allowed
        DB::statement('ALTER TABLE "jobPosting" DROP CONSTRAINT IF EXISTS jobposting_status_check');
    }

    public function down(): void
    {
        // No need to add it back for now to prevent further crashes
    }
};