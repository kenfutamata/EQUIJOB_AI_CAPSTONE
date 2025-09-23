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
            $table->renameColumn('userID', 'userID');
            $table->renameColumn('first_name', 'firstName');
            $table->renameColumn('last_name', 'lastName');
            $table->renameColumn('type_of_disability', 'typeOfDisability');
            $table->renameColumn('aiGeneratedSummary', 'aiGeneratedSummary');
        });

        Schema::table('educations', function (Blueprint $table) {
            $table->renameColumn('resumeID', 'resumeID');
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->renameColumn('resumeID', 'resumeID');
            $table->renameColumn('jobTitle', 'jobTitle');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resume', function (Blueprint $table) {
            $table->renameColumn('userID', 'userID');
            $table->renameColumn('firstName', 'first_name');
            $table->renameColumn('lastName', 'last_name');
            $table->renameColumn('typeOfDisability', 'type_of_disability');
            $table->renameColumn('aiGeneratedSummary', 'aiGeneratedSummary');
        });

        Schema::table('educations', function (Blueprint $table) {
            $table->renameColumn('resumeID', 'resumeID');
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->renameColumn('resumeID', 'resumeID');
            $table->renameColumn('jobTitle', 'jobTitle');

        });
    }
};
