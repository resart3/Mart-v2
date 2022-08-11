<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Str;

class UserSeeder extends Seeder
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
                'name' => "ADMIN",
                'email' => "admin@gmail.com",
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // password
                'remember_token' => Str::random(10),
                'role' => "superuser",
                'nik' => "13001170414",
                'family_card_id' => "3172022401096461"
            ],
            [
                'name' => "ADMIN",
                'email' => "admin_rt@gmail.com",
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // password
                'remember_token' => Str::random(10),
                'role' => "admin-rt",
                'nik' => "13001170414",
                'family_card_id' => "3172022401096461"
            ],
            [
                'name' => "ADMIN",
                'email' => "admin_rw@gmail.com",
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // password
                'remember_token' => Str::random(10),
                'role' => "admin-rw",
                'nik' => "13001170414",
                'family_card_id' => "3172022401096461"
            ],
            [
                'name' => "HUANG CHIN CHANG",
                'email' => "huang@gmail.com",
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // password
                'remember_token' => Str::random(10),
                'role' => "user",
                'nik' => "1301170324",
                'family_card_id' => "3172022401096461"
            ]
        ];

        User::insert($data);
    }
}
