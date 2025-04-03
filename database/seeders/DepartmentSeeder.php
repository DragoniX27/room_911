<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::upsert([
            ['name' => 'Deparment 1'],
            ['name' => 'Deparment 2'],
            ['name' => 'Deparment 3'],
        ], ['name']);
    }
}
