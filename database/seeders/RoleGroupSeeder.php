<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //      DB::table('role_groups')->insertOrIgnore([
    //         [
    //             'role_name' => 'user',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'role_name' => 'admin',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'role_name' => 'detc',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'role_name' => 'excise inspector',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //     ]);
    // }

    public function run(): void
    {
        DB::table('role_groups')->insertOrIgnore([
            [
                'role_name' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'detc',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'excise inspector',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'hq',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
