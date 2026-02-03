<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'IT', 'description' => 'Technical assistance and hardware support'],
            ['name' => 'Maintenance', 'description' => 'Facilities and equipment maintenance'],
            ['name' => 'HR', 'description' => 'Human Resources and personnel management'],
            ['name' => 'Finance & Accounting', 'description' => 'Budgeting, payroll, and financial reports'],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(['name' => $dept['name']], $dept);
        }

        // Remove departments that are not in the new list
        $names = array_column($departments, 'name');
        Department::whereNotIn('name', $names)->delete();
    }
}
