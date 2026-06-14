<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['superadmin', 'admin', 'kasir'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Permissions
        $permissions = [
            'manage businesses',
            'manage users',
            'manage products',
            'manage promos',
            'view reports',
            'process transactions',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        Role::findByName('superadmin')->givePermissionTo(Permission::all());
        Role::findByName('admin')->givePermissionTo([
            'manage products', 'manage promos', 'view reports',
        ]);
        Role::findByName('kasir')->givePermissionTo(['process transactions']);
    }
}