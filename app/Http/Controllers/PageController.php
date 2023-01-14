<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\PageResource;
use App\Repositories\Contracts\IPage;
use App\Repositories\Contracts\ISite;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\LatestFirst;

class PageController extends Controller
{

    protected $pages;
    protected $sites;

    public function __construct(IPage $pages, ISite $sites)
    {

       $this->pages = $pages;
       $this->sites = $sites;
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
                'url' => '/' . strtolower(trans_choice('site.pages', 1)) . '/' . $page->id,
                'name' => $page->name,
                'title' => $page->title,
                'subtitle' => $page->subtitle,
                'description' => $page->description,
                'slug' => $page->slug,
                'icon' => $page->icon,
                'expand' => '<p class="font-weight-bold py-2 mb-0 primary--text">' .$page->title . ':</p><p><span class="text-decoration-underline">'. trans_choice('site.subtitles', 1) . ':</span><span> ' . $page->subtitle . '</span></p><p><span class="text-decoration-underline">'. trans_choice('site.descriptions', 1) . ':</span><span> ' . $page->description . '</span></p>',
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = $this->pages->find($id);

        return view('admin.page', ['page' => $page]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Authorize request
        if(isset($request['site_id'])) {
            $site = $this->sites->find($request['site_id']);
        } else {
            $site = auth()->user()->ownedSites->first();
        }

        $this->authorize('create', [Page::class, $site]);

        // Validate request
        if(isset($request['slug'])) {
            $request['slug'] = ! Str::contains($request['slug'], $site->slug) ? $site->slug . '/' . Str::slug($request['slug'], '-'): $request['slug'];
        } else {
            $request['slug'] = $site->slug . '/' . Str::slug($request['name'], '-');
        }
        $validated = $this->validateRequest($request);

        if($request['icon']) {
            $icon = Str::contains($request['icon'], 'mdi') ? $request['icon'] : 'mdi-' . $request['icon'];
        } else {
            $icon = null;
        }

        // Create User with relationships
        if($validated) {
            $newPage = $this->pages->create([
                'site_id' => $site->id,
                'name' => $request['name'],
                'title' => $request['title'] ?? $request['name'],
                'subtitle' => $request['subtitle'] ?? null,
                'description' => $request['description'] ?? null,
                'slug' => $request['slug'],
                'icon' => $icon,
                'sequence' => $request['sequence'] ?? 0,
                'required' => false,
            ]);

            return new PageResource($newPage);
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
        // Authorize request
        if(isset($request['site_id'])) {
            $site = $this->sites->find($request['site_id']);
        } else {
            $site = auth()->user()->ownedSites->first();
        }
        $this->authorize('update', [Page::class, $site]);

        // Validate request
        if(isset($request['slug']) && ! Str::contains($request['slug'], $site->slug)) {
            $request['slug'] = $site->slug . '/' .  Str::slug($request['slug'], '-');
        }
         $validated = $this->validateRequest($request, $id);

         // Create User with relationships
         if($validated) {

            if($request['icon']) {
                $icon = Str::contains($request['icon'], 'mdi') ? $request['icon'] : 'mdi-' . $request['icon'];
            } else {
                $icon = null;
            }

            $updated = $this->pages->update($id, [
                'site_id' => $site->id,
                'name' => $request['name'],
                'title' => $request['title'],
                'subtitle' => $request['subtitle'],
                'description' => $request['description'],
                'slug' => $request['slug'],
                'icon' => $icon,
                'sequence' => $request['sequence'] ?? 0,
            ]);

            return new PageResource($updated);

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
    public function destroy($id)    {

        // Authorize request
        $page = $this->pages->find($id);
        $site = $this->sites->find($page->site_id);
        $this->authorize('delete', [Page::class, $site]);

        if($page->required) {
            return response()->json(['message' =>  __('site.cannot_delete_item', ['item' => $page->title]) ], 403);
        }

        $this->pages->delete($id);

        return response()->json(['response' => 1 ], 200);
    }


    protected function validateRequest($request, $id = null)
    {

        $url = $id == null ? 'unique:pages,slug' : 'unique:pages,slug,'. $id;

        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'title' => ['nullable', 'string', 'max:250'],
            'subtitle' => ['nullable', 'string', 'max:250'],
            'description' => ['nullable', 'string'],
            'slug' => ['required', 'string', 'not_regex:/^https?:\\/\\/(?:www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b(?:[-a-zA-Z0-9()@:%_\\+.~#?&\\/=]*)$/', $url],
            'icon' => ['nullable', 'string', 'max:50'],
            'sequence' => ['nullable', 'numeric', 'min:0', 'max: 100'],
        ]);
        return true;
    }



}
