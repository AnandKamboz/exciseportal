<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'HQ User',
            'Hanish Gupta', 'Ajay Saroha', 'Deepa Chaudhary', 'Arvind Kumar',
            'Samir Yadav', 'Suman Sindhu', 'ANAND SINGH', 'Anupama Singh',
            'SHOBHINI MALA', 'Nanha Ram Phullay', 'Shriya Chahar',
            'Smt Taruna Lamba', 'Aditendra Singh Takshak', 'Saroj Devi',
            'Sarthak Kohli', 'SEEMA BIDLAN B', 'Abhishek Batra',
            'Jagbir Jakhar', 'Vijay Kumar', 'Priyanka Yadav', 'Vijay Kumar',
            'Harveer Singh Chauhan', 'Gaurav Chahal', 'Preeti Choudhary',
            'AMITA TANWAR', 'Anju Singh', 'Neel Ratan', 'Sneh Lata' , 'Aruna Singh' , 'Carkhi',"ETO"
        ];

        $emails = [
            'hq@gmail.com',
            'hanish.gupta@detc.in', 'ajay.saroha@detc.in', 'deepa.chaudhary@detc.in', 'arvind.kumar@detc.in',
            'samir.yadav@detc.in', 'suman.sindhu@detc.in', 'anand.singh@detc.in', 'anupama.singh@detc.in',
            'shobhini.mala@detc.in', 'nanha.phullay@detc.in', 'shriya.chahar@detc.in',
            'taruna.lamba@detc.in', 'aditendra.takshak@detc.in', 'saroj.devi@detc.in',
            'sarthak.kohli@detc.in', 'seema.bidlan@detc.in', 'abhishek.batra@detc.in',
            'jagbir.jakhar@detc.in', 'vijay.kumar1@detc.in', 'priyanka.yadav@detc.in', 'vijay.kumar2@detc.in',
            'harveer.chauhan@detc.in', 'gaurav.chahal@detc.in', 'preeti.choudhary@detc.in',
            'amita.tanwar@detc.in', 'anju.singh@detc.in','neel@gmail.com' ,'s@gmail.com' , 'a@gmail.com' , 'c@gmail.com',
            'eto@gmail.com',
        ];

        $mobiles = [
            '9536026914',
            '9814649092', '9996780009', '8289009223', '7042576767',
            '9871128848', '9971304777', '9253000368', '9871439529',
            '8800217875', '9582739627', '9988882669', '9468090618',
            '9810782769', '7206851236', '7700000004', '9991554577',
            '9728900022', '8283803804', '9968119852', '9813438953',
            '8607371000', '7837870199', '9988063434',
            '8901274148', '7988243732',
            '7988243730',  '9466788666',
            '9250902999' , '9311737801','1212121212','9999999999',
        ];

        $districtIds = [
            null,
            11, 3, 23, 24, 25, 26, 18,
            27, 28, 29, 30,
            2, 9, 17, 7,
            13, 5, 16, 22,19, 15, 21,
            10, 20, 14,
            8, 6, 4,1,12,4,
        ];

        $ward = [
            null, null, null, null, null, null, null, null, null, null,
            null, null, null, null, null, null, null, null, null, null,
            null, null, null, null, null, null, null, null, null, null,
            null, 8,
        ];


        // $wardIds = [
        //     null,
        //     1, 2, 3, 7,
        //     8, 9, 10, 11,
        //     12, 13, 14, 15,
        //     16, 17, 18, 19,
        //     20, 21, 22,
        //     23, 24, 25,
        //     26, 27,
        //     null, null,
        //     null,null,null,null
        // ];

        $userData = [];

        foreach ($names as $i => $name) {
            $userData[] = [
                'secure_id' => Str::uuid(),
                'name'      => $name,
                'email'     => $emails[$i],
                'mobile'    => $mobiles[$i],
                'district'  => $districtIds[$i],
                'ward_no'   => $ward[$i],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($userData);
    }
}
