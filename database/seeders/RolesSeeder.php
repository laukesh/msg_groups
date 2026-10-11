<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Ensure permissions are present
        $permissions = Permission::pluck('name')->toArray();

        // Roles and their permissions
        $roles = [
            'Super Admin' => $permissions,
            'Administrator' => [
                'dashboard.view',
                'users.view','users.create','users.edit','users.delete',
                'roles.view','roles.create','roles.edit','roles.delete',
                'permissions.view','permissions.create','permissions.edit','permissions.delete',
                'malls.view','malls.create','malls.edit','malls.delete',
                'audit.view',
            ],
            'Mall Manager' => [
                'dashboard.view',
                'malls.view','malls.create','malls.edit','malls.delete',
            ],
            'Manager' => [
                'dashboard.view',
            ],
            'Employee' => [
                'dashboard.view',
                'profile.view',
                'profile.update',
            ],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($perms);
        }

        // Ensure admin user exists and assign Super Admin
        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD', 'ChangeMe123!');

        if ($adminEmail) {
            $user = User::firstOrCreate(
                ['email' => $adminEmail],
                ['name' => 'Super Admin', 'password' => Hash::make($adminPassword), 'is_active' => true, 'status' => 'active']
            );

            if (! $user->hasRole('Super Admin')) {
                $user->assignRole('Super Admin');
            }

            if (! isset($user->is_super_admin) || ! $user->is_super_admin) {
                $user->is_super_admin = true;
                $user->save();
            }
        }
    }
}
