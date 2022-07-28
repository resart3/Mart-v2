<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
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
                'category_name' => "Rumah dengan luas tanah s.d 150m2",
                'amount' => "150.000"
            ],
            [
                'category_name' => "Rumah dengan luas tanah 151 s.d 200 m2",
                'amount' => "200.000"
            ]
        ];

        Category::insert($data);
    }
}
