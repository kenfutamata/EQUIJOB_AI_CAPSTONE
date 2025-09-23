<?php

namespace Database\Seeders;

use App\Models\users;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        users::factory()->create([
            'firstName' => 'admin',
            'lastName' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'Admin',
            'status' => 'Active',
            'phoneNumber' => '1234567890',
            'dateOfBirth' => '1990-01-01',
            'typeOfDisability' => 'Physical',
            'pwdId' => 'none',
            'upload_pwd_card' => 'none',
            'companyName' => 'Admin Company',
            'companyLogo' => 'admin_logo.png',
        ]);
    }
}
