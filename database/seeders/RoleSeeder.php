<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Creates the admin/employee roles and backfills every user's role
     * from the is_admin flag. Must run AFTER all user-creating seeders;
     * safe to re-run standalone on an existing database.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('employee', 'web');

        User::query()->each(function (User $user) {
            $user->syncRoles($user->is_admin ? 'admin' : 'employee');
        });
    }
}
