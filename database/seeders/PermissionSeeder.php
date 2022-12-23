<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $permission_role = collect([
            [
                'role' => 'superadmin',
                'permissions' => ['create_component', 'update_component', 'delete_component', 'create_permission', 'update_permission', 'delete_permission', 'create_role', 'update_role', 'delete_role', 'create_site', 'update_site', 'delete_site', 'create_admin', 'update_admin', 'delete_admin']],
            [
                'role' => 'admin',
                'permissions' => [
                    'update_site', 'update_user', 'create_page', 'update_page', 'delete_page','create_post', 'update_post', 'delete_post', 'create_subscriber', 'update_subscriber', 'delete_subscriber'
                ]
            ],
            [
                'role' => 'subscriber',
                'permissions' => [
                    'update_user'
                ]
            ],
        ]);

        $permission_role->each( function ($data) {
            $date = Carbon::now()->format('Y-m-d H:i:s');
            $role = Role::where('role', $data['role'])->firstOrFail();
            foreach($data['permissions'] as $perm) {
                $result = Permission::firstOrCreate(['permission'=> $perm, 'created_at' => $date, 'updated_at' => $date]);
                $role->permissions()->attach($result->id);
            }
        });
    }
}
