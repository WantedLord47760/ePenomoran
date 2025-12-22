<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Pemimpin User',
            'email' => 'pimpinan@test.com',
            'password' => bcrypt('password'),
            'role' => 'pemimpin',
        ]);

        \App\Models\User::create([
            'name' => 'Operator User',
            'email' => 'operator@test.com',
            'password' => bcrypt('password'),
            'role' => 'operator',
        ]);
    }
}
