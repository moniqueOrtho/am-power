<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\IPermission;
use App\Repositories\Contracts\IRole;
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
                'expand' => '<p class="font-weight-bold py-2 mb-0 primary--text">' . __( 'site.permissions_of_role', ['role' => $result->role] ) . '</p><p class="pb-2">'. $result->getPermissions()->implode(', ') . '</p>'
            ];
        });

        $perms = $this->permissions->all();

        $fields = collect([
            [ 'name' => 'role', 'label' => trans_choice('site.roles', 1), 'counter' => 20, 'rules' =>     ['required', 'max-counter'], 'required' => true, 'sm' => 4, 'md'=> 3 ],
            [ 'name' => 'description', 'label' => trans_choice('site.descriptions', 1), 'sm' => 8, 'md'=> 9 ],
            [ 'name' => 'permissions', 'label' => trans_choice('site.permissions', 2) , 'input' => 'checkbox', 'type' => 'selectAll', 'sm' => 12, 'md'=> 12]
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
}
