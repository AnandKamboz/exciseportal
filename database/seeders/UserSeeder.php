<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = ["Kapil Sir", "Anand Kamboj"];
        $email = ["kapilsir@gmail.com", "anand@gmail.com"];
        $mobile = ["9999999999", "8888888888"];
        $district = ["11", null];

        $secureIds = [];

        foreach ($name as $index => $userName) {
            $secureId = Str::uuid();
            while (in_array($secureId, $secureIds)) {
                $secureId = Str::uuid();
            }
            $secureIds[] = $secureId;

            $userData[] = [
                'secure_id' => $secureId,
                'name' => $userName,
                'email' => $email[$index],
                'mobile' => $mobile[$index],
                'district' => $district[$index],
            ];
        }

        DB::table('users')->insert($userData);

    }
}
