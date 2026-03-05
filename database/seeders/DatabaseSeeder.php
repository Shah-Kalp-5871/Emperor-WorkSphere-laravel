<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Creates one super admin and one employee with all required data.
     *
     * NOTE: Do NOT use Hash::make() here. The Admin and User models both have
     * 'password' => 'hashed' in their casts, which auto-hashes the password
     * on save. Using Hash::make() would cause double-hashing and login failures.
     */
    public function run(): void
    {
        // 1. Seed Roles & Permissions first (required by assignRole)
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        // 2. Create Admin (stored in the `admins` table)
        //    Plain text password → model's 'hashed' cast handles bcrypt automatically
        $admin = \App\Models\Admin::firstOrCreate(
            ['email' => 'admin@worksphere.com'],
            [
                'name'     => 'Admin User',
                'password' => 'Admin@1234',   // plain text — model casts to bcrypt
            ]
        );
        $admin->syncRoles(['super_admin']);

        // 3. Create a Department (required by Employee)
        $department = \App\Models\Department::firstOrCreate(
            ['name' => 'Engineering'],
            ['description' => 'Software Engineering Department']
        );

        // 4. Create a Designation inside that Department
        $designation = \App\Models\Designation::firstOrCreate(
            ['name' => 'Software Developer', 'department_id' => $department->id],
        );

        // 5. Create Employee User (stored in the `users` table)
        //    Same as above — plain text password, model auto-hashes
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'employee@worksphere.com'],
            [
                'name'     => 'Employee User',
                'password' => 'Employee@1234',  // plain text — model casts to bcrypt
            ]
        );
        $user->syncRoles(['employee']);

        // 6. Create the Employee record (stored in the `employees` table, linked to the user)
        \App\Models\Employee::firstOrCreate(
            ['user_id' => $user->id],
            [
                'department_id'   => $department->id,
                'designation_id'  => $designation->id,
                'employee_code'   => 'EMP001',
                'phone'           => '9000000001',
                'is_active'       => true,
                'created_by'      => $admin->id,
                'date_of_joining' => now()->toDateString(),
            ]
        );

        $this->command->info('✅ Seeded: admin@worksphere.com (password: Admin@1234)');
        $this->command->info('✅ Seeded: employee@worksphere.com (password: Employee@1234)');
    }
}
