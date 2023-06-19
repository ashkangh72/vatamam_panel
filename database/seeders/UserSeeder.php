<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();
       User::create([
           "name" => "MMD",
           "username" => "mmd",
           "password" =>bcrypt("123"),
           "email" => "mmd@gmail.com",
           "gender" => 1,
           "level" => "creator"
       ]);
    }
}
