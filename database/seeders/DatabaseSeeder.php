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
    }
}
