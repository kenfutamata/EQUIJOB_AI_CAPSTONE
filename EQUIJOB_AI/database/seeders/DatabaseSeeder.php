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
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'Admin',
            'status' => 'Active',
            'phone_number' => '1234567890',
            'date_of_birth' => '1990-01-01',
            'type_of_disability' => 'Physical',
            'pwd_id' => 'none',
            'upload_pwd_card' => 'none',
        ]);
    }
}
