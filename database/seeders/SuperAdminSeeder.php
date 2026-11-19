<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use InvalidArgumentException;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('is_superadmin', true)->exists()){
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => bcrypt('Password@123'),
                'is_superadmin' => true
            ]);
        }
    }
}
