<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\users;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    protected static ?string $password = null;
    protected $model = users::class;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

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
        ]);
    }
}
