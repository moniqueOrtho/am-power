<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PageResource;
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
    public function index(Request $request, $siteId)    {

        $result = $this->pages->withCriteria([
            new LatestFirst()
        ])->findWhere('site_id', $siteId);

        if($request->ajax()) {
            return PageResource::collection($result);
        }

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

        $sites = null;

        if($siteId > 0) {
            $ownSites = auth()->user()->ownedSites;
            $sites = $ownSites->map(function ($site){
                return [
                    'text' => $site->title,
                    'value' => $site->id
                ];
            });
        }

        return view('admin.pages', ['data' => $result, 'siteId' => $siteId, 'sites' => $sites ]);
    }
}
