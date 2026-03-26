<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Permissions
        $permissions = [
            'manage tickets',
            'manage orders',
            'manage users',
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 2. Create Roles and Assign Permissions
        
        // Super Admin: Gets everything
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin: Can manage tickets and orders
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo(['manage tickets', 'manage orders', 'view dashboard']);

        // User: Basic access
        $userRole = Role::create(['name' => 'User']);
        $userRole->givePermissionTo(['view dashboard']);

        // 3. Create the Actual Users
        
        // Your Super Admin Account
        $superAdmin = User::updateOrCreate(
            ['email' => 'jacques@vibeandburn.com'], // Change to your real email
            [
                'name' => 'Jacques Musengimana',
                'password' => Hash::make('password123'), // Change this immediately!
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        // A Test Admin Account
        $admin = User::updateOrCreate(
            ['email' => 'admin@vibeandburn.com'],
            [
                'name' => 'Support Agent',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->assignRole($adminRole);

        // A Test Regular User
        $user = User::updateOrCreate(
            ['email' => 'client@example.com'],
            [
                'name' => 'Regular Client',
                'password' => Hash::make('password123'),
            ]
        );
        $user->assignRole($userRole);
    }
}