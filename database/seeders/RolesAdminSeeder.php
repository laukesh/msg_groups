<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Get all existing permissions
        $permissions = Permission::pluck('name')->toArray();

        $roles = [
            'Super Admin' => $permissions,

            'Administrator' => [
                'dashboard.view',
                'users.view',
                'users.create',
                'users.edit',
                'users.delete',
                'roles.view',
                'roles.create',
                'roles.edit',
                'roles.delete',
                'permissions.view',
                'permissions.create',
                'permissions.edit',
                'permissions.delete',
                'malls.view',
                'malls.create',
                'malls.edit',
                'malls.delete',
                'audit.view',
            ],

            'Mall Manager' => [
                'dashboard.view',
                'malls.view',
                'malls.create',
                'malls.edit',
                'malls.delete',
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

        foreach ($roles as $roleName => $rolePermissions) {

            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            if ($roleName === 'Super Admin') {
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions($rolePermissions);
            }
        }

        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD', 'ChangeMe123!');

        if ($adminEmail) {

            $user = User::firstOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => 'Super Admin',
                    'password' => Hash::make($adminPassword),
                    'is_active' => true,
                    'is_super_admin' => true,
                    'status' => 'active',
                ]
            );

            $user->assignRole('Super Admin');
        }
    }
}