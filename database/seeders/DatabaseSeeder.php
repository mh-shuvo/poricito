<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call location seeder
        $this->call(LocationSeeder::class);

        // Create admin user
        User::create([
            'name' => 'Mohammad Mehedi Hasan',
            'email' => 'mehedifci907@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create contributor user
        User::create([
            'name' => 'Royal',
            'email' => 'royal@poricito.local',
            'password' => bcrypt('password'),
            'role' => 'contributor',
        ]);

        // Create test user
        User::factory()->create([
            'name' => 'Mosharraf',
            'email' => 'mdmhriyad6472@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'contributor',
        ]);
    }
}
