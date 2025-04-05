<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Santiago Giraldo',
            'email' => 'santiagogil271@gmail.com',
            'password' => Hash::make('12345'),
            'phone' => '(302) 371-8033',
            'status' => 'active',
            'department_id' => 1
        ])->assignRole('admin_room_911');

        User::create([
            'name' => 'Santiago Worker',
            'email' => 'work@gmail.com',
            'password' => Hash::make('12345'),
            'phone' => '(302) 371-8034',
            'status' => 'active',
            'department_id' => 1
        ])->assignRole('worker');
    }
}
