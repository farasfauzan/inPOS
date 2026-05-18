<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['username' => 'admin', 'full_name' => 'Admin', 'password' => Hash::make('admin123'), 'role' => 'admin'],
            ['username' => 'kasir', 'full_name' => 'Kasir', 'password' => Hash::make('kasir123'), 'role' => 'kasir'],
        ];
        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['username' => $user['username']],
                array_merge($user, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}