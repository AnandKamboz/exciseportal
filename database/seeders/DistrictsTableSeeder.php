<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('districts')->insert([
            0 => [
                'id' => 1,
                'name' => 'Faridabad',
                'code' => 'HRFB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            1 => [
                'id' => 2,
                'name' => 'Hisar',
                'code' => 'HRHS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            2 => [
                'id' => 3,
                'name' => 'Bhiwani',
                'code' => 'HRBHA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            3 => [
                'id' => 4,
                'name' => 'Gurugram',
                'code' => 'HRGR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            4 => [
                'id' => 5,
                'name' => 'Karnal',
                'code' => 'HRKR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            5 => [
                'id' => 6,
                'name' => 'Sonipat',
                'code' => 'HRSO',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            6 => [
                'id' => 7,
                'name' => 'Jind',
                'code' => 'HRJN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            7 => [
                'id' => 8,
                'name' => 'Sirsa',
                'code' => 'HRSI',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            8 => [
                'id' => 9,
                'name' => 'Yamunanagar',
                'code' => 'HRYNA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            9 => [
                'id' => 10,
                'name' => 'Panipat',
                'code' => 'HRPP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            10 => [
                'id' => 11,
                'name' => 'Ambala',
                'code' => 'HRAM',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            11 => [
                'id' => 12,
                'name' => 'Charkhi-Dadri',
                'code' => 'HRBHA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            12 => [
                'id' => 13,
                'name' => 'Kaithal',
                'code' => 'HRKH',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            13 => [
                'id' => 14,
                'name' => 'Rohtak',
                'code' => 'HRRH',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            14 => [
                'id' => 15,
                'name' => 'Palwal',
                'code' => 'HRPL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            15 => [
                'id' => 16,
                'name' => 'Kurukshetra',
                'code' => 'HRKU',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            16 => [
                'id' => 17,
                'name' => 'Jhajjar',
                'code' => 'HRJR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            17 => [
                'id' => 18,
                'name' => 'Fatehabad',
                'code' => 'HRFT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            18 => [
                'id' => 19,
                'name' => 'Mahendergarh',
                'code' => 'HRNR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            19 => [
                'id' => 20,
                'name' => 'Rewari',
                'code' => 'HRRE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            20 => [
                'id' => 21,
                'name' => 'Panchkula',
                'code' => 'HRPK',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            21 => [
                'id' => 22,
                'name' => 'Nuh',
                'code' => 'HRME',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ================================
            // NEWLY ADDED SUB-DISTRICTS
            // ================================

            22 => [
                'id' => 23,
                'name' => 'Faridabad (East)',
                'code' => 'HRFBE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            23 => [
                'id' => 24,
                'name' => 'Faridabad (West)',
                'code' => 'HRFBW',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            24 => [
                'id' => 25,
                'name' => 'Faridabad (North)',
                'code' => 'HRFBN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            25 => [
                'id' => 26,
                'name' => 'Faridabad (South)',
                'code' => 'HRFBS',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            26 => [
                'id' => 27,
                'name' => 'Gurugram (East)',
                'code' => 'HRGRE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            27 => [
                'id' => 28,
                'name' => 'Gurugram (West)',
                'code' => 'HRGRW',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            28 => [
                'id' => 29,
                'name' => 'Gurugram (North)',
                'code' => 'HRGRN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            29 => [
                'id' => 30,
                'name' => 'Gurugram (South)',
                'code' => 'HRGRS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
