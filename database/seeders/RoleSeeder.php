<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // get all permissions system-wide
        $allPermissions = Permission::query()->select('id', 'name')->get()->toArray();

        $generalPermissions = [];

        $roles = [
            [
                'name' => 'Administrator',
                'description' => 'System administrator account',
                'permissions' => $allPermissions
            ],
            [
                'name' => 'User',
                'description' => 'General user account',
                'permissions' => $generalPermissions
            ]
        ];

        foreach($roles as $roleData) {

            $role = Role::whereName($roleData['name'])->first();
            if(!$role) {
                $role = new Role();
                $role->name = $roleData['name'];
                $role->description = $roleData['description'];
                $role->save();
            }

            $rolePermissions = [];
            foreach($roleData['permissions'] as $permissionData) {
                $permissionFound = $role->permissions()->where('name', $permissionData['name'])->count();
                if(!$permissionFound) {
                    $permission = Permission::whereName($permissionData['name'])->first();
                    
                    $rolePermissions[$permission->id] = [
                        'permission_id' => $permission->id,
                        'role_id' => $role->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }
            }

            $role->permissions()->attach($rolePermissions);
        }
    }
}