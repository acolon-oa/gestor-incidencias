<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'IT Support', 'description' => 'Technical assistance and hardware support'],
            ['name' => 'Human Resources', 'description' => 'Personnel management and employee relations'],
            ['name' => 'Customer Success', 'description' => 'Client satisfaction and user advocacy'],
            ['name' => 'Finance & Accounting', 'description' => 'Budgeting, payroll, and financial reports'],
            ['name' => 'Engineering', 'description' => 'Software development and infrastructure'],
            ['name' => 'Legal & Compliance', 'description' => 'Regulatory standards and legal advice'],
            ['name' => 'Marketing', 'description' => 'Brand management and communications'],
            ['name' => 'Sales', 'description' => 'Account management and revenue growth'],
            ['name' => 'Facilities & Security', 'description' => 'Office maintenance and safety'],
            ['name' => 'Operations', 'description' => 'General administration and logistics'],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(['name' => $dept['name']], $dept);
        }
    }
}
