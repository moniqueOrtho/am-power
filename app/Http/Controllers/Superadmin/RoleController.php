<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Repositories\Contracts\IRole;
use App\Repositories\Contracts\IPermission;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\LatestFirst;

class RoleController extends Controller
{
    protected $roles;
    protected $permissions;

    public function __construct(IRole $roles, IPermission $permissions)
    {
       $this->roles = $roles;
       $this->permissions = $permissions;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = $this->roles->withCriteria([
            new EagerLoad(['permissions']),
            new LatestFirst()
        ])->all();

        $results = $results->map(function($result) {
            return [
                'id' => $result->id,
                'role' => $result->role,
                'description' => $result->description,
                'expand' => '<p class="font-weight-bold py-2 mb-0 primary--text">' . __( 'site.permissions_of_role', ['role' => $result->role] ) . '</p><p class="pb-2">'. $result->getPermissions()->implode(', ') . '</p>',
                'permissions' => $result->permissionIds
            ];
        });

        $perms = $this->permissions->all();

        $fields = collect([
            [ 'name' => 'role', 'label' => trans_choice('site.roles', 1), 'counter' => 20, 'rules' =>     ['required', 'max-counter'], 'required' => true, 'sm' => 4, 'md'=> 3 ],
            [ 'name' => 'description', 'label' => trans_choice('site.descriptions', 1), 'sm' => 8, 'md'=> 9 ],
            [ 'name' => 'permissions', 'label' =>__('site.all') . ' ' . trans_choice('site.permissions', 2) , 'input' => 'checkbox', 'type' => 'selectAll', 'sm' => 12, 'md'=> 12]
        ]);

        $perms = $perms->map(function($perm) {
            return [
                'name' => 'permissions',
                'label' => $perm->permission,
                'input' => 'checkbox',
                'value' => $perm->id
            ];
        });

        $newFields = $fields->concat($perms);

        return view('superadmin.roles', ['data' => $results, 'perms' => $newFields]);
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
            $newRole = $this->roles->create([
                'role' => $request['role'],
                'description' => $request['description'] ?? null,
            ]);

            if(isset($request['permissions'])) {
                $newRole->permissions()->attach($request['permissions']);
            }

            return new RoleResource($newRole);
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

            $updated = $this->roles->update($id, [
                'role' => $request['role'],
                'description' => $request['description'] ?? null,
            ]);
            if(isset($request['permissions'])) {
                $updated->permissions()->sync($request['permissions']);
            }

            return new RoleResource($updated);

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
        $deletedId = $this->roles->find($id);
        $deletedId->permissions()->detach();

        $this->roles->delete($id);

        return response()->json(['response' => 1 ], 200);
    }



    protected function validateRequest($request, $id = null)
    {
        $role = $id == null ? 'unique:roles,role' : 'unique:roles,role,'. $id;

        $request->validate([
            'role' => ['required', 'string', 'max:20', $role],
            'description' => ['nullable', 'string', 'max:250'],
        ]);
        return true;
    }
}
