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
            Schema::table('resume', function (Blueprint $table) {
            if (!Schema::hasColumn('resume', 'disability_type')) {
            $table->enum('type_of_disability', ['Physical', 'Visual', 'Hearing', 'Intellectual'])->nullable();        
            }
            if (!Schema::hasColumn('resume', 'ai_generated_summary')) {
                $table->text('ai_generated_summary')->nullable()->after('skills');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resume', function (Blueprint $table) {
            $table->dropColumn('disability_type');
        });
    }
};
