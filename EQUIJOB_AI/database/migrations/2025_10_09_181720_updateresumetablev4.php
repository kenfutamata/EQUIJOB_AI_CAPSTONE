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
        Schema::table('resume', function (Blueprint $table) {
            $table->enum('typeOfDisability', ['Deaf or Hard of Hearing', 'Intellectual Disability', 'Learning Disability', 'Mental Disability', 'Physical Disability (Orthopedic)', 'Psychosocial Disability', 'Speech and Language Impairment', 'Visual Disability', 'Cancer (RA11215)', 'Rare Disease (RA10747)'])->nullable()->after('skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};