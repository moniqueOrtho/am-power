<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\IComponent;

class ComponentController extends Controller
{
    protected $components;

    public function __construct(IComponent $components)
    {
       $this->components = $components;
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
