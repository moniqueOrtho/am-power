<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\IRole;

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
        // $result = $this->roles->all();

        // return view('superadmin.users', ['data' => $result ]);
    }
}
