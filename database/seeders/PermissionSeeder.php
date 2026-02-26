<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view_dashboard',
            'manage_employees',
            'manage_projects',
            'manage_tasks',
            'manage_tickets',
            'view_audit_logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
        }

        // Assign to web super_admin
        $superAdminWeb = Role::where(['name' => 'super_admin', 'guard_name' => 'web'])->first();
        if ($superAdminWeb) {
            $superAdminWeb->syncPermissions(Permission::where('guard_name', 'web')->get());
        }

        // Assign to admin super_admin
        $superAdminAdmin = Role::where(['name' => 'super_admin', 'guard_name' => 'admin'])->first();
        if ($superAdminAdmin) {
            $superAdminAdmin->syncPermissions(Permission::where('guard_name', 'admin')->get());
        }
    }
}
