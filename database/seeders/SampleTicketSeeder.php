<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;

class SampleTicketSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first(); // Simplificando para el ejemplo
        $depts = Department::all();

        if ($depts->isEmpty()) {
            return;
        }

        $tickets = [
            ['title' => 'Server Maintenance', 'description' => 'Scheduled monthly server updates and security patches.', 'status' => 'open', 'priority' => 'medium', 'dept' => 'IT'],
            ['title' => 'Office AC Repair', 'description' => 'The air conditioning in the main hall is leaking.', 'status' => 'in_progress', 'priority' => 'high', 'dept' => 'Maintenance'],
            ['title' => 'New Hire Onboarding', 'description' => 'Prepare equipment and accounts for the new HR manager.', 'status' => 'open', 'priority' => 'low', 'dept' => 'HR'],
            ['title' => 'Payroll Discrepancy', 'description' => 'Review January payroll for the engineering team.', 'status' => 'closed', 'priority' => 'urgent', 'dept' => 'Finance & Accounting'],
            ['title' => 'Network Upgrade', 'description' => 'Installing new routers for better WiFi coverage.', 'status' => 'open', 'priority' => 'medium', 'dept' => 'IT'],
        ];

        foreach ($tickets as $t) {
            $dept = $depts->where('name', $t['dept'])->first();
            $deptId = $dept ? $dept->id : $depts->first()->id;

            Ticket::create([
                'title' => $t['title'],
                'description' => $t['description'],
                'status' => $t['status'],
                'priority' => $t['priority'],
                'user_id' => $admin->id,
                'department_id' => $deptId,
            ]);
        }
    }
}
