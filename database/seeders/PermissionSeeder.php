<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'units.view',
            'units.create',
            'units.edit',
            'units.delete',
            'unit_statuses.view',
            'unit_statuses.create',
            'unit_statuses.edit',
            'unit_statuses.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
