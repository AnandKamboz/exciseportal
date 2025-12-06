<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictWardSeeder extends Seeder
{
    // public function run(): void
    // {
    //     $data = [
    //         ['district_name' => 'Ambala', 'ward_count' => 10],
    //         ['district_name' => 'Bhiwani', 'ward_count' => 4],
    //         ['district_name' => 'Faridabad (East)', 'ward_count' => 9],
    //         ['district_name' => 'Faridabad (North)', 'ward_count' => 7],
    //         ['district_name' => 'Faridabad (South)', 'ward_count' => 8],
    //         ['district_name' => 'Faridabad (West)', 'ward_count' => 12],
    //         ['district_name' => 'Fatehabad', 'ward_count' => 2],
    //         ['district_name' => 'Gurugram (East)', 'ward_count' => 12],
    //         ['district_name' => 'Gurugram (North)', 'ward_count' => 14],
    //         ['district_name' => 'Gurugram (South)', 'ward_count' => 11],
    //         ['district_name' => 'Gurugram (West)', 'ward_count' => 11],
    //         ['district_name' => 'Hisar', 'ward_count' => 8],
    //         ['district_name' => 'Jagadhari', 'ward_count' => 10],
    //         ['district_name' => 'Jhajjar', 'ward_count' => 5],
    //         ['district_name' => 'Jind', 'ward_count' => 6],
    //         ['district_name' => 'Kaithal', 'ward_count' => 5],
    //         ['district_name' => 'Karnal', 'ward_count' => 10],
    //         ['district_name' => 'Kurukshetra', 'ward_count' => 3],
    //         ['district_name' => 'Mewat', 'ward_count' => 2],
    //         ['district_name' => 'Narnaul', 'ward_count' => 2],
    //         ['district_name' => 'Palwal', 'ward_count' => 4],
    //         ['district_name' => 'Panchkula', 'ward_count' => 5],
    //         ['district_name' => 'Panipat', 'ward_count' => 12],
    //         ['district_name' => 'Rewari', 'ward_count' => 5],
    //         ['district_name' => 'Rohtak', 'ward_count' => 12],
    //         ['district_name' => 'Sirsa', 'ward_count' => 7],
    //         ['district_name' => 'Sonepat', 'ward_count' => 10],
    //     ];

    //     DB::table('district_wards')->insert($data);
    // }

    public function run(): void
{
    // district_name => district_id mapping
    $districtMap = [
        'Ambala' => 11,
        'Bhiwani' => 3,
        'Faridabad (East)' => 23,
        'Faridabad (North)' => 25,
        'Faridabad (South)' => 26,
        'Faridabad (West)' => 24,
        'Fatehabad' => 18,
        'Gurugram (East)' => 27,
        'Gurugram (North)' => 29,
        'Gurugram (South)' => 30,
        'Gurugram (West)' => 28,
        'Hisar' => 2,
        'Jagadhari' => null, // not in districts table
        'Jhajjar' => 17,
        'Jind' => 7,
        'Kaithal' => 13,
        'Karnal' => 5,
        'Kurukshetra' => 16,
        'Mewat' => 22, // old name of Nuh
        'Narnaul' => null, // not in districts table
        'Palwal' => 15,
        'Panchkula' => 21,
        'Panipat' => 10,
        'Rewari' => 20,
        'Rohtak' => 14,
        'Sirsa' => 8,
        'Sonepat' => 6,
    ];

    $data = [
        ['district_name' => 'Ambala', 'ward_count' => 10],
        ['district_name' => 'Bhiwani', 'ward_count' => 4],
        ['district_name' => 'Faridabad (East)', 'ward_count' => 9],
        ['district_name' => 'Faridabad (North)', 'ward_count' => 7],
        ['district_name' => 'Faridabad (South)', 'ward_count' => 8],
        ['district_name' => 'Faridabad (West)', 'ward_count' => 12],
        ['district_name' => 'Fatehabad', 'ward_count' => 2],
        ['district_name' => 'Gurugram (East)', 'ward_count' => 12],
        ['district_name' => 'Gurugram (North)', 'ward_count' => 14],
        ['district_name' => 'Gurugram (South)', 'ward_count' => 11],
        ['district_name' => 'Gurugram (West)', 'ward_count' => 11],
        ['district_name' => 'Hisar', 'ward_count' => 8],
        ['district_name' => 'Jagadhari', 'ward_count' => 10],
        ['district_name' => 'Jhajjar', 'ward_count' => 5],
        ['district_name' => 'Jind', 'ward_count' => 6],
        ['district_name' => 'Kaithal', 'ward_count' => 5],
        ['district_name' => 'Karnal', 'ward_count' => 10],
        ['district_name' => 'Kurukshetra', 'ward_count' => 3],
        ['district_name' => 'Mewat', 'ward_count' => 2],    // maps to Nuh (22)
        ['district_name' => 'Narnaul', 'ward_count' => 2],
        ['district_name' => 'Palwal', 'ward_count' => 4],
        ['district_name' => 'Panchkula', 'ward_count' => 5],
        ['district_name' => 'Panipat', 'ward_count' => 12],
        ['district_name' => 'Rewari', 'ward_count' => 5],
        ['district_name' => 'Rohtak', 'ward_count' => 12],
        ['district_name' => 'Sirsa', 'ward_count' => 7],
        ['district_name' => 'Sonepat', 'ward_count' => 10],
    ];

    // Add district_id to each row automatically
    $finalData = [];
    foreach ($data as $row) {
        $row['district_id'] = $districtMap[$row['district_name']] ?? null;
        $row['created_at'] = now();
        $row['updated_at'] = now();
        $finalData[] = $row;
    }

    DB::table('district_wards')->insert($finalData);
}

}
