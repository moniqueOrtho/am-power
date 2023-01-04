<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\IRole;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\LatestFirst;

class RoleController extends Controller
{
    protected $roles;

    public function __construct(IRole $roles)
    {
       $this->roles = $roles;
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
                'role' => $result->role
            ];
        });

        return view('superadmin.roles', ['data' => $results]);
    }
}
