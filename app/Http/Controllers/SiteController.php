<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SiteResource;
use App\Repositories\Contracts\IRole;
use App\Repositories\Contracts\ISite;
use App\Repositories\Contracts\IUser;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\LatestFirst;

class SiteController extends Controller
{
    protected $sites;
    protected $users;
    protected $roles;

    public function __construct(ISite $sites, IUser $users, IRole $roles)
    {
       $this->sites = $sites;
       $this->users = $users;
       $this->roles = $roles;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->sites->withCriteria([
            new EagerLoad(['owner']),
            new LatestFirst()
        ])->all();

        $result = $result->map(function($site) {
            $data = [
                'theme' => $site->theme,
                'id' => $site->id,
                'name' => $site->name,
                'slug' => $site->slug,
                'lang' => $site->lang,
            ];
            if(auth()->user()->hasRole('superadmin')) {
                $data['owner_name'] = $site->owner->name;
                $data['owner_id'] = $site->owner->id;

            }

            return $data;
        });

        // Find the role id, only admin can become an administrator of a site
        $allAdmins = [];
        if(auth()->user()->hasRole('superadmin')) {
            $admin = $this->roles->findWhereFirst('role', 'admin');

            $allAdmins = $this->users->findWhere('role_id', $admin->id);

            $allAdmins = $allAdmins->map(function($user) {
                return [
                    'text' => $user->name,
                    'value' => $user->id
                ];
            });
        }

        return view('admin.sites', ['data' => $result, 'admins' => $allAdmins ]);
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
        $this->authorize('create', Site::class);

        // Validate request
        $validated = $this->validateRequest($request);

        // Create User with relationships
        if($validated) {
            $newSite = $this->sites->create([
                'name' => $request['name'],
                'title' => $request['title'] ?? $request['name'],
                'subtitle' => $request['subtitle'] ?? null,
                'description' => $request['description'] ?? null,
                'slug' => $request['slug'],
                'lang' => $request['lang'],
                'theme' => $request['theme'] ?? null,
                'colors' => $request['colors'] ?? null,
                'owner_id'=> $request['owner_id']
            ]);
            return new SiteResource($newSite);
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
        $this->authorize('update', Site::class);

        // Validate request
         $validated = $this->validateRequest($request, $id);

         // Create User with relationships
         if($validated) {

            $updated = $this->sites->update($id, [
                'name' => $request['name'],
                'title' => $request['title'] ?? null,
                'subtitle' => $request['subtitle'] ?? null,
                'description' => $request['description'] ?? null,
                'slug' => $request['slug'],
                'lang' => $request['lang'],
                'theme' => $request['theme'],
                'colors' => $request['colors'] ?? null
            ]);
            if(isset($request['owner_id'])) {
                $updated->users()->sync($request['owner_id']);
            }

            return new SiteResource($updated);

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
        $this->authorize('delete', Site::class);

        $this->sites->delete($id);

        return response()->json(['response' => 1 ], 200);
    }


    protected function validateRequest($request, $id = null)
    {

        $url = $id == null ? 'unique:sites,slug' : 'unique:sites,slug,'. $id;

        $request->validate([
            'name' => ['required', 'string', 'max:20'],
            'title' => ['nullable', 'string', 'max:250'],
            'subtitle' => ['nullable', 'string', 'max:250'],
            'description' => ['nullable', 'string', 'min:4', 'max:250'],
            'slug' => ['required', 'string', 'not_regex:/^https?:\\/\\/(?:www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b(?:[-a-zA-Z0-9()@:%_\\+.~#?&\\/=]*)$/', $url],
            'lang' => ['nullable', 'string', 'max:4'],
            'theme' => ['required', 'string', 'max:50'],
            'colors' => ['json', 'nullable']
        ]);
        return true;
    }
}
