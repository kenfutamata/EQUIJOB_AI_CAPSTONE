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
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('first_name', 'firstName');
            $table->renameColumn('lastName', 'lastName');
            $table->renameColumn('phoneNumber', 'phoneNumber');
            $table->renameColumn('dateOfBirth', 'dateOfBirth');
            $table->renameColumn('typeOfDisability', 'typeOfDisability');
            $table->renameColumn('pwdId', 'pwdId');
            $table->renameColumn('companyName', 'companyName');
            $table->renameColumn('companyLogo', 'companyLogo');
            $table->renameColumn('profilePicture', 'profilePicture');
            $table->renameColumn('businessPermit', 'businessPermit');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('firstName', 'first_name');
            $table->renameColumn('lastName', 'lastName');
            $table->renameColumn('phoneNumber', 'phoneNumber');
            $table->renameColumn('dateOfBirth', 'dateOfBirth');
            $table->renameColumn('typeOfDisability', 'typeOfDisability');
            $table->renameColumn('pwdId', 'pwdId');
            $table->renameColumn('companyName', 'companyName');
            $table->renameColumn('companyLogo', 'companyLogo');
            $table->renameColumn('profilePicture', 'profilePicture');
            $table->renameColumn('businessPermit', 'businessPermit');
        });
    }
};
