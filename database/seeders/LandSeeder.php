<?php

namespace Database\Seeders;

use App\Models\Land;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'family_card_id' => "3172022401096461",
                'category_id' => 1,
                'area' => 145,
                'house_number' => "c2-01"
            ],
            [
                'family_card_id' => "3216060701130313",
                'category_id' => 2,
                'area' => 170,
                'house_number' => "c2-02"
            ]
        ];

        Land::insert($data);
    }
}
