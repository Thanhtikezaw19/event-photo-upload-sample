<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'username' => 'member',
                'email' => 'member@example.com',
                'password' => Hash::make('password'),
                'role' => 'member',
            ],
        ];

        foreach ($users as $userData) {
            User::create([
                'username' => $userData['username'],
                'email' => $userData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Replace with your desired password
                'role' => $userData['role'],
            ]);
        }
    }
}
