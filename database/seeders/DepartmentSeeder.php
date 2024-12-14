<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function __construct(protected int $count = 10)
    {
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::factory($this->count)->create();
    }
}
