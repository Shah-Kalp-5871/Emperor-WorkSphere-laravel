<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        /*
        // Seed Departments
        $depts = ['Engineering', 'Marketing', 'Sales', 'Human Resources', 'Support'];
        $deptModels = [];
        foreach ($depts as $dept) {
            $deptModels[$dept] = \App\Models\Department::firstOrCreate(['name' => $dept]);
        }

        // Seed Designations
        $engineeringDeptId = $deptModels['Engineering']->id;
        $desigs = ['Senior Developer', 'Junior Developer', 'Marketing Manager', 'HR Lead', 'Sales Executive', 'Support Agent'];
        
        foreach ($desigs as $desig) {
            $deptId = $engineeringDeptId;
            if (str_contains($desig, 'Marketing')) $deptId = $deptModels['Marketing']->id;
            if (str_contains($desig, 'HR')) $deptId = $deptModels['Human Resources']->id;
            if (str_contains($desig, 'Sales')) $deptId = $deptModels['Sales']->id;
            if (str_contains($desig, 'Support')) $deptId = $deptModels['Support']->id;

            \App\Models\Designation::firstOrCreate(
                ['name' => $desig],
                ['department_id' => $deptId]
            );
        }
        */

        // Create Admin
        $admin = \App\Models\Admin::firstOrCreate(
            ['email' => 'admin@worksphere.com'],
            [
                'name' => 'Admin User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $admin->assignRole('super_admin');

        /*
        // Create Employee User
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'employee@worksphere.com'],
            [
                'name' => 'Employee User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $user->assignRole('employee');

        // Create Employee Record
        \App\Models\Employee::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_code' => 'EMP001',
                'phone' => '1234567890',
                'is_active' => true,
                'created_by' => $admin->id,
                'department_id' => $deptModels['Engineering']->id,
                'designation_id' => \App\Models\Designation::where('name', 'Senior Developer')->first()->id,
            ]
        );

        // Seed More Employees
        $users = [
            ['name' => 'John Doe', 'email' => 'john@worksphere.com'],
            ['name' => 'Jane Smith', 'email' => 'jane@worksphere.com'],
            ['name' => 'Michael Brown', 'email' => 'michael@worksphere.com'],
        ];

        foreach ($users as $index => $u) {
            $createdUser = \App\Models\User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                ]
            );
            $createdUser->assignRole('employee');

            \App\Models\Employee::firstOrCreate(
                ['user_id' => $createdUser->id],
                [
                    'employee_code' => 'EMP00' . ($index + 2),
                    'phone' => '987654321' . $index,
                    'is_active' => true,
                    'department_id' => $deptModels['Engineering']->id,
                    'designation_id' => \App\Models\Designation::where('name', 'Senior Developer')->first()->id,
                    'created_by' => $admin->id,
                ]
            );
        }

        // Seed Projects
        $projects = [
            ['name' => 'Project Emperor', 'status' => 'active', 'priority' => 'high'],
            ['name' => 'SkyNet Migration', 'status' => 'planning', 'priority' => 'critical'],
            ['name' => 'User Feedback Module', 'status' => 'completed', 'priority' => 'medium'],
        ];

        foreach ($projects as $p) {
            \App\Models\Project::firstOrCreate(
                ['name' => $p['name']],
                [
                    'description' => 'Description for ' . $p['name'],
                    'status' => $p['status'],
                    'priority' => $p['priority'],
                    'start_date' => now(),
                    'created_by' => $admin->id,
                ]
            );
        }

        // Add sample logs for seeded employees
        $employees = \App\Models\Employee::all();
        $projectList = \App\Models\Project::all();
        foreach ($employees as $employee) {
            \App\Models\DailyLog::create([
                'employee_id' => $employee->id,
                'log_date' => now()->format('Y-m-d'),
                'morning_summary' => 'Finished UI components for ' . ($projectList->random()->name ?? 'Project'),
                'afternoon_summary' => 'Worked on API integration and bug fixes.',
                'status' => 'submitted',
                'mood' => 'good'
            ]);
        }
        foreach ($employees as $employee) {
            \App\Models\SupportTicket::create([
                'ticket_number' => 'TK-' . rand(1000, 9999),
                'employee_id' => $employee->id,
                'category' => 'technical',
                'subject' => 'Issue with system access',
                'description' => 'I cannot access the project module.',
                'priority' => 'medium',
                'status' => 'open',
            ]);
        }
        */

        $this->command->info('Database seeded successfully.');
    }
}
