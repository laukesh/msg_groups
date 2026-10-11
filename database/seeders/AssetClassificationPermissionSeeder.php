<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AssetClassificationPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'types',
            'purposes',
            'industry_profiles',
            'operating_models',
            'ownerships',
            'revenue_statuses',
        ];

        $actions = [
            'view',
            'create',
            'edit',
            'delete',
        ];

        $permissions = [];

        foreach ($modules as $module) {
            foreach ($actions as $action) {

                $name = "{$module}.{$action}";

                Permission::firstOrCreate([
                    'name' => $name,
                    'guard_name' => 'web',
                ]);

                $permissions[] = $name;
            }
        }

        $role = Role::where('name', 'Super Admin')
            ->where('guard_name', 'web')
            ->first();

        if ($role) {
            foreach ($permissions as $permission) {
                $role->givePermissionTo($permission);
            }
        }
    }
}