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
                'rt_rw' => "001/014"
            ],
            [
                'name' => "ADMIN",
                'email' => "admin_rt@gmail.com",
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // password
                'remember_token' => Str::random(10),
                'role' => "admin_rt",
                'nik' => "13001170414",
                'rt_rw' => "001/014"
            ],
            [
                'name' => "ADMIN",
                'email' => "admin_rw@gmail.com",
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // password
                'remember_token' => Str::random(10),
                'role' => "admin_rw",
                'nik' => "13001170414",
                'rt_rw' => "001/014"
            ],
            [
                'name' => "HUANG CHIN CHANG",
                'email' => "huang@gmail.com",
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // password
                'remember_token' => Str::random(10),
                'role' => "user",
                'nik' => "1301170324",
                'rt_rw' => "001/014"
            ]
        ];

        User::insert($data);
    }
}
