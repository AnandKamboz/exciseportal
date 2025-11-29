<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


// class RoleTypesSeeder extends Seeder
// {
//     public function run(): void
//     {
//         DB::table('role_types')->insertOrIgnore([
//             [
//                 'user_id' => 1,
//                 'role_id' => 3,
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ],
//             [
//                 'user_id' => 2,
//                 'role_id' => 4,
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ],
//         ]);
//     }
// }

class RoleTypesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('role_types')->insertOrIgnore([
            [
                'user_id' => 1,
                'role_id' => 3, // detc
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'role_id' => 4, // inspector
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'role_id' => 5, // HQ
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

