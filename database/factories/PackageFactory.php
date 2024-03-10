<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PackageFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => 'MAIN',
            'slug' => 'Earn 5% daily',
            'daily_income' => 5,
            'price' => 1000,
            'days_running' => 30,
            'status' => 'active'
        ];
    }

}
