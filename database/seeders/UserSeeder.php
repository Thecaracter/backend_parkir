<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'johndoe@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'poin' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'janesmith@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'poin' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mikejohnson@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'poin' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}