<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            // Dashboard
            'dashboard.view',

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Roles
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Permissions
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',

            // Malls
            'malls.view',
            'malls.create',
            'malls.edit',
            'malls.delete',

            // Audit
            'audit.view',

            // Profile
            'profile.view',
            'profile.update',
            // floors
            'floors.view',
            'floors.create',
            'floors.edit',
            'floors.delete',
            // buildings
            'buildings.view',
            'buildings.create',
            'buildings.edit',  
            'buildings.delete',  
             // Zone
            'zones.view',
            'zones.create',
            'zones.edit',
            'zones.delete',   
            // Unit Types
            'unit_types.view',
            'unit_types.create',
            'unit_types.edit',
            'unit_types.delete',    
        ];

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}