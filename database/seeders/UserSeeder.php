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
                'password' => bcrypt('123123'), // password
                'remember_token' => Str::random(10),
                'role' => "superuser",
                'nik' => "13001170414"
            ],
            [
                'name' => "USER",
                'email' => "user@gmail.com",
                'email_verified_at' => now(),
                'password' => bcrypt('123123'), // password
                'remember_token' => Str::random(10),
                'role' => "user",
                'nik' => "1301170324"
            ]
        ];

        User::insert($data);
    }
}
