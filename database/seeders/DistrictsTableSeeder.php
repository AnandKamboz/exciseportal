<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('districts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Faridabad',
                'code' => 'HRFB',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Hisar',
                'code' => 'HRHS',
               'created_at' => now(),
                'updated_at' => now(),
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Bhiwani',
                'code' => 'HRBHA',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Gurugram',
                'code' => 'HRGR',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Karnal',
                'code' => 'HRKR',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Sonipat',
                'code' => 'HRSO',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Jind',
                'code' => 'HRJN',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Sirsa',
                'code' => 'HRSI',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Yamunanagar',
                'code' => 'HRYNA',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Panipat',
                'code' => 'HRPP',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Ambala',
                'code' => 'HRAM',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Charkhi-Dadri',
                'code' => 'HRBHA',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Kaithal',
                'code' => 'HRKH',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Rohtak',
                'code' => 'HRRH',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Palwal',
                'code' => 'HRPL',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Kurukshetra',
                'code' => 'HRKU',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Jhajjar',
                'code' => 'HRJR',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Fatehabad',
                'code' => 'HRFT',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            18 => 
            array (
                'id' => 19,
                // 'name' => 'Mahendragarh',
                'name' => 'Mahendergarh',
                'code' => 'HRNR',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Rewari',
                'code' => 'HRRE',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Panchkula',
                'code' => 'HRPK',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'Nuh',
                'code' => 'HRME',
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
    }
}
