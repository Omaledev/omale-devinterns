<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permissions for school management - create only if they don't exist
        $permissions = [
            'schools.view',
            'schools.create',
            'schools.edit',
            'schools.delete',
            'schools.manage',
            'users.manage',
            'roles.manage'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Creating the roles - create only if they don't exist
        $superAdmin = Role::firstOrCreate(['name' => 'SuperAdmin']);
        $schoolAdmin = Role::firstOrCreate(['name' => 'SchoolAdmin']);
        $bursar = Role::firstOrCreate(['name' => 'Bursar']);
        $teacher = Role::firstOrCreate(['name' => 'Teacher']);
        $student = Role::firstOrCreate(['name' => 'Student']);
        $parent = Role::firstOrCreate(['name' => 'Parent']);

        // Assigning all permissions to SuperAdmin
        $superAdminPermissions = Permission::all();
        foreach ($superAdminPermissions as $permission) {
            $superAdmin->givePermissionTo($permission);
        }

        // Permissions for SchoolAdmin
        $schoolAdmin->syncPermissions([
            'schools.view',
            'schools.create',
            'schools.edit',
            'users.manage'
        ]);

        // Other roles permission
        $viewRoles = [$bursar, $teacher, $student, $parent];
        foreach ($viewRoles as $role) {
            $role->syncPermissions(['schools.view']);
        }

        // Creating the SuperAdmin user only if it doesn't exist
        $user = User::firstOrCreate(
            ['email' => 'superadmin@schoolsystem.com'],
            [
             'name' => 'System Administrator',
             'password' => Hash::make('admin123456'),
            ]
        );

        // Assign SuperAdmin role to the user if not already assigned
        if (!$user->hasRole('SuperAdmin')) {
            $user->assignRole('SuperAdmin');
        }

        $this->command->info('Roles, permissions and SuperAdmin user setup completed!');
        $this->command->info('Email: superadmin@schoolsystem.com');
        $this->command->info('Password: admin123456');
    }
}
