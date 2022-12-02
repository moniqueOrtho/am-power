<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Permission;

class PermissionController extends Controller
{
    protected $permissions;

    public function __construct(Permission $permissions)
    {
       $this->permissions = $permissions;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $result = $this->roles->all();

        // return view('superadmin.users', ['data' => $result ]);
    }
}
