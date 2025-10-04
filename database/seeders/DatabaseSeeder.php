<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // $this->call([
        //     RoleGroupSeeder::class,
        //     DistrictsTableSeeder::class,
        //     UserSeeder::class,
        //     RoleGroup::class,
        //     RoleTypesSeeder::class,
        // ]);

        $this->call([
            RoleGroupSeeder::class,
            DistrictsTableSeeder::class,
            UserSeeder::class,
            RoleTypesSeeder::class, // Remove RoleGroup::class
        ]);
    }
}
