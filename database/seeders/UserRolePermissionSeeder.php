<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat role
        $superAdminRole = Role::create(['name' => 'sso.super-admin']);
        $adminRole = Role::create(['name' => 'sso.admin']);

        // Membuat permission
        $manageUsersPermission = Permission::create(['name' => 'sso.manage-users']);
        $manageRolesPermission = Permission::create(['name' => 'sso.manage-roles']);
        $managePermissionsPermission = Permission::create(['name' => 'sso.manage-permissions']);
        $manageModulePermission = Permission::create(['name' => 'sso.manage-modules']);
        $manageEntityPermission = Permission::create(['name' => 'sso.manage-entity']);
        $manageOauthClientPermission = Permission::create(['name' => 'sso.manage-oauth-client']);
        $sysLogs = Permission::create(['name' => 'sso.system-logs']);

        // Menyematkan permission ke role
        $superAdminRole->givePermissionTo([
            $manageUsersPermission,
            $manageRolesPermission,
            $managePermissionsPermission,
            $manageModulePermission,
            $manageEntityPermission,
            $manageOauthClientPermission,
            $sysLogs
        ]);
        $adminRole->givePermissionTo([$manageUsersPermission, $manageRolesPermission, $managePermissionsPermission]);

        // Membuat user super-admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@srapp.com',
            'password' => Hash::make('123123123'),
        ]);
        $superAdmin->assignRole($superAdminRole);

        // Membuat user admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@srapp.com',
            'password' => Hash::make('123123123'),
        ]);
        $admin->assignRole($adminRole);
    }
}
