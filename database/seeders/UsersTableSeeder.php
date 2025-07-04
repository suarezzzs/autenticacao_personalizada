<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // add 3 users to the database
        for($index = 1; $index <= 3; $index++){
            User::create([
                "username" => "user$index",
                "email" => "user$index@gmail.com",
                "password" => bcrypt("Admin123"),
                "email_verified_at" => Carbon::now(),
                "active" => true,
            ]);
        };
    }
}
