<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $currentYear = now()->year;

        // Create 50 random users
        for ($i = 0; $i < 50; $i++) {

            // Random date between Jan 1 and today of current year
            $randomDate = Carbon::createFromDate($currentYear, 1, 1)
                ->addDays(rand(0, now()->dayOfYear - 1))
                ->setTime(rand(0, 23), rand(0, 59), rand(0, 59));

            User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => bcrypt('password'), // default password
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
        }
    }
}
