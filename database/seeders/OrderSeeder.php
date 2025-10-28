<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $currentYear = now()->year;

        $users = User::all();

        foreach ($users as $user) {
            // Each user gets between 1 and 5 orders
            $numOrders = rand(1, 5);

            for ($i = 0; $i < $numOrders; $i++) {
                // Random date within the current year
                $randomDate = Carbon::createFromDate($currentYear, 1, 1)
                    ->addDays(rand(0, now()->dayOfYear - 1));

                Order::create([
                    'user_id' => $user->id,
                    'order_number' => strtoupper($faker->bothify('ORD-####??')),
                    'total_amount' => $faker->randomFloat(2, 10, 1000),
                    'order_date' => $randomDate,
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }
        }
    }
}
