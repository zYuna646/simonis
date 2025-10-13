<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'user_management_access',
            'user_management_create',
            'user_management_edit',
            'user_management_view',
            'user_management_delete',
            'role_create',
            'role_edit',
            'role_view',
            'role_delete',
            'permission_create',
            'permission_edit',
            'permission_view',
            'permission_delete'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $role1 = Role::create(['name' => 'admin']);
        $role1->givePermissionTo(Permission::all());

        // $role2 = Role::create(['name' => 'user']);
        // $role2->givePermissionTo([
        //     'user_management_view'
        // ]);
        
        // Tambahkan role guru
        $role3 = Role::create(['name' => 'guru']);
        $role3->givePermissionTo([
            'user_management_view'
        ]);
        
        // Tambahkan role siswa
        $role4 = Role::create(['name' => 'siswa']);
        $role4->givePermissionTo([
            'user_management_view'
        ]);
        
        // Tambahkan role orang tua
        $role5 = Role::create(['name' => 'orang_tua']);
        $role5->givePermissionTo([
            'user_management_view'
        ]);
    }
}
