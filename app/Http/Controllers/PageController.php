<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\IPage;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\LatestFirst;

class PageController extends Controller
{

    protected $pages;

    public function __construct(IPage $pages)
    {

       $this->pages = $pages;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($siteId)
    {
        $result = $this->pages->withCriteria([
            new LatestFirst()
        ])->findWhere('site_id', $siteId);

        $result = $result->map(function($page) {
            $data = [
                'id' => $page->id,
                'name' => $page->name,
                'title' => $page->title,
                'subtitle' => $page->subtitle,
                'description' => $page->description,
                'slug' => $page->slug,
                'icon' => $page->icon,
            ];

            return $data;
        });

        return view('admin.pages', ['data' => $result, 'siteId' => $siteId ]);
    }
}
