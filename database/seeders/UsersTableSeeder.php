<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin1',
                'email' => 'admin@gmail.com',
                'password' => '123123',
                'role_id' => 1,
            ],
            [
                'name' => 'User1',
                'email' => 'user@gmail.com',
                'password' => '123123',
                'role_id' => 2,
            ],
        ]);
    }
}
