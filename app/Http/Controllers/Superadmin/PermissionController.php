<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\IRole;
use App\Http\Resources\PermissionResource;
use App\Repositories\Contracts\IPermission;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\LatestFirst;

class PermissionController extends Controller
{
    protected $permissions;
    protected $roles;

    public function __construct(IPermission $permissions, IRole $roles)
    {
       $this->permissions = $permissions;
       $this->roles = $roles;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = $this->permissions->withCriteria([
            new EagerLoad(['roles']),
            new LatestFirst()
        ])->all();

        $results = $results->map(function($result) {
            return [
                'id' => $result->id,
                'permission' => $result->permission,
                'description' => $result->description,
                'expand' => '<p class="font-weight-bold py-2 mb-0 primary--text">' . __( 'site.roles_of_the_permission', ['permission' => $result->permission] ) . '</p><p class="pb-2">'. $result->rolesName->implode(', ') . '</p>',
                'roles' => $result->roleIds
            ];
        });

        $AllRoles = $this->roles->all();

        $fields = collect([
            [ 'name' => 'permission', 'label' => trans_choice('site.permissions', 1), 'counter' => 50, 'rules' => ['required', 'max-counter'], 'required' => true, 'sm' => 4, 'md'=> 3 ],
            [ 'name' => 'description', 'label' => trans_choice('site.descriptions', 1), 'sm' => 8, 'md'=> 9 ],
            [ 'name' => 'roles', 'label' =>__('site.all') . ' ' . trans_choice('site.roles', 2) , 'input' => 'checkbox', 'type' => 'selectAll', 'sm' => 12, 'md'=> 12]
        ]);

        $AllRoles = $AllRoles->map(function($role) {
            return [
                'name' => 'roles',
                'label' => $role->role,
                'input' => 'checkbox',
                'value' => $role->id
            ];
        });

        $newFields = $fields->concat($AllRoles);

        return view('superadmin.permissions', ['data' => $results, 'fields' => $newFields]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $this->validateRequest($request);

        // Create Role with relationships
        if($validated) {
            $newPermission = $this->permissions->create([
                'permission' => $request['permission'],
                'description' => $request['description'] ?? null,
            ]);

            if(isset($request['roles'])) {
                $newPermission->roles()->attach($request['roles']);
            }

            return new PermissionResource($newPermission);
        } else {
            return response()->json(['message', __('site.input_not_valid')], 400);
         }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // Validate request
         $validated = $this->validateRequest($request, $id);

         // Create User with relationships
         if($validated) {

            $updated = $this->permissions->update($id, [
                'permission' => $request['permission'],
                'description' => $request['description'] ?? null,
            ]);
            if(isset($request['roles'])) {
                $updated->roles()->sync($request['roles']);
            }

            return new PermissionResource($updated);

         } else {
            return response()->json(['message', __('site.input_not_valid')], 400);
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletedId = $this->permissions->find($id);
        $deletedId->roles()->detach();

        $this->permissions->delete($id);

        return response()->json(['response' => 1 ], 200);
    }



    protected function validateRequest($request, $id = null)
    {
        $permission = $id == null ? 'unique:permissions,permission' : 'unique:permissions,permission,'. $id;

        $request->validate([
            'permission' => ['required', 'string', 'max:50', $permission],
            'description' => ['nullable', 'string', 'max:250'],
        ]);
        return true;
    }
}
