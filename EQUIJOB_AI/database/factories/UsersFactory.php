<?php

namespace Database\Factories;

use App\Models\users;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users>
 */
class UsersFactory extends Factory
{
    protected $model = users::class;
    /**
     * Static property to store the hashed password.
     *
     * @var string|null
     */
    protected static ?string $password = null;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstName' => 'admin',
            'lastName' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'status' => 'active',
            'phoneNumber' => $this->faker->phoneNumber(),
            'dateOfBirth' => $this->faker->date(),
            'typeOfDisability' => $this->faker->word(),
            'pwdId' => $this->faker->word(),
            'upload_pwd_card' => $this->faker->word(),
        ];
    }
}
