<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::upsert([
            ['name' => 'admin_room_911', 'label' => 'Admin rom 911'],
            ['name' => 'worker', 'label' => 'Worker']
        ], ['name']);
    }
}
