<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        Permission::create(['name' => 'editar campaña']);
        Permission::create(['name' => 'eliminar campaña']);
        Permission::create(['name' => 'publicar campaña']);
        Permission::create(['name' => 'ver estadísticas']);
        Permission::create(['name' => 'gestionar usuarios']);

        // Crear roles y asignar permisos
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['editar campaña', 'eliminar campaña', 'publicar campaña', 'ver estadísticas']);

        $adminTotaltextoRole = Role::create(['name' => 'admintt']);
        $adminTotaltextoRole->givePermissionTo(['editar campaña', 'publicar campaña']);

        $superAdminRole = Role::create(['name' => 'superadmin']);
        $superAdminRole->givePermissionTo(Permission::all()); // Asignar todos los permisos
    }
}