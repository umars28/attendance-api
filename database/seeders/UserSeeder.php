<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'nama' => 'Ananda Bayu',
            'email' => 'bayu@gmail.com',
            'npp' => '12345',
            'npp_supervisor' => '11111',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'nama' => 'Umar',
            'email' => 'umar@gmail.com',
            'npp' => '6789',
            'npp_supervisor' => '11111',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'nama' => 'Supervisor 1',
            'email' => 'spv1@gmail.com',
            'npp' => '11111',
            'npp_supervisor' => null,
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'nama' => 'Supervisor 2',
            'email' => 'spv2@gmail.com',
            'npp' => '22222',
            'npp_supervisor' => null,
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
