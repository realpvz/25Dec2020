<?php

namespace Database\Seeders;

use App\Models\Thread;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Database\Seeders\RoleAndPermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(RoleAndPermissionSeeder::class);


        $roleInDatabase = Role::where('name', config('permission.default_roles')[0]);
        if ($roleInDatabase->count() < 1){
            foreach (config('permission.default_roles') as $role) {
                Role::create([
                    'name' => $role,
                ]);
            }
        }

        $permissionInDatabase = Permission::where('name', config('permission.default_permissions')[0]);
        if ($permissionInDatabase->count() < 1){
            foreach(config('permission.default_permissions') as $permission) {
                Permission::create([
                    'name' => $permission,
                ]);
            }
        }


        Thread::factory()->count(100)->create();
    }
}
