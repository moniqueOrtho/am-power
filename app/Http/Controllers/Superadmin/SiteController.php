<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ISite;

class SiteController extends Controller
{
    protected $sites;

    public function __construct(ISite $sites)
    {
       $this->sites = $sites;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->sites->all();

        return view('superadmin.sites', ['data' => $result ]);
    }
}
