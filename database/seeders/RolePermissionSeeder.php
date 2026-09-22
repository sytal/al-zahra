<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'articles.create', 'articles.edit', 'articles.publish', 'articles.delete',
            'research.create', 'research.edit', 'research.publish', 'research.delete',
            'resources.create', 'resources.edit', 'resources.publish', 'resources.delete',
            'courses.manage', 'courses.publish',
            'consultations.respond',
            'certificates.manage',
            'settings.manage',
            'users.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $director = Role::firstOrCreate(['name' => 'director']);
        $director->syncPermissions($permissions);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);

        $editor = Role::firstOrCreate(['name' => 'editor']);
        $editor->syncPermissions([
            'articles.create', 'articles.edit', 'articles.publish',
            'research.create', 'research.edit', 'research.publish',
            'resources.create', 'resources.edit', 'resources.publish',
        ]);

        Role::firstOrCreate(['name' => 'student']);
    }
}
