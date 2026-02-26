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

        // Create Admin
        $admin = \App\Models\Admin::firstOrCreate(
            ['email' => 'admin@worksphere.com'],
            [
                'name' => 'Admin User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $admin->assignRole('super_admin');

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
            ]
        );

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
            // Map designations to reasonable departments for better seeding
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
    }
}
