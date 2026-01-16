<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $configs = config('permission.access_web');

        $allPermissions = $configs['permissions'];

        $onlyPermissions = array_unique(array_merge(...array_values($allPermissions)));

        // create permissions
        foreach ($onlyPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $rolesPermissions = $configs['roles_permissions'];

        // create roles and assign created permissions
        foreach ($rolesPermissions as $role => $permissions) {
            /** @var Role $role */
            $role = Role::create(['name' => $role]);

            // if empty permissions give all permissions
            if (empty($permissions)) {
                $role->givePermissionTo(Permission::all());
                continue;
            }

            // give permissions by types
            if (array_key_exists('types', $permissions)) {
                foreach ($permissions['types'] as $type) {
                    $typePermissions = $allPermissions[$type] ?? null;
                    $typePermissions && $role->givePermissionTo($typePermissions);
                }
                unset($permissions['types']);
            }

            $permissions && $role->givePermissionTo($permissions);
        }
    }
}
