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
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'Admin',
            'status' => 'Active',
            'phone_number' => '1234567890',
            'date_of_birth' => '1990-01-01',
            'type_of_disability' => 'none',
            'pwd_id' => 'none',
            'upload_pwd_card' => 'none',
            'company_name' => 'Admin Company',
            'company_logo' => 'admin_logo.png',
        ]);
    }
}
