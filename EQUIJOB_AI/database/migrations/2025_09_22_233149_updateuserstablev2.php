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
            $table->string('firstName', 50)->change();
            $table->string('lastName', 50)->change();
            $table->string('email', 100)->change();
            $table->string('password', 100)->change();
            $table->string('phoneNumber', 20)->change();
            $table->string('pwdId', 50)->nullable()->change();
            $table->string('companyName', 100)->nullable()->change();
            $table->string('companyLogo', 255)->nullable()->change();
            $table->string('profilePicture', 255)->nullable()->change();
            $table->string('businessPermit', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstName')->change();
            $table->string('lastName')->change();
            $table->string('email')->change();
            $table->string('password')->change();
            $table->string('phoneNumber')->change();
            $table->string('pwdId')->change();
            $table->string('companyName')->change();
            $table->string('companyLogo')->change();
            $table->string('profilePicture')->change();
            $table->string('businessPermit')->change();
        });
    }
};
