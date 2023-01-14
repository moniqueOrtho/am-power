<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ComponentResource;
use App\Repositories\Contracts\IComponent;
use App\Repositories\Eloquent\Criteria\LatestFirst;

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
        $result = $this->components->withCriteria([
            new LatestFirst()
        ])->all();

        $result = $result->map(function($comp) {
            return [
                'theme' => $comp->theme,
                'id' => $comp->id,
                'name' => $comp->name
            ];
        });

        return view('superadmin.components', ['data' => $result ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Automatically add theme to name
        if(! Str::contains($request['name'], $request['theme'])) {
          $request['name'] = $request['theme'] . $request['name'];
        }
        // Validate request
        $validated = $this->validateRequest($request);

        // Create component
        if($validated) {
            $newComp = $this->components->create([
                'name' => $request['name'],
                'theme' => $request['theme']
            ]);
            return new ComponentResource($newComp);
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
        //Automatically add theme to name
        if(! Str::contains($request['name'], $request['theme'])) {
            $request['name'] = $request['theme'] . $request['name'];
        }

        // Validate request
         $validated = $this->validateRequest($request, $id);

         // Create User with relationships
         if($validated) {

            $updated = $this->components->update($id, [
                'name' => $request['name'],
                'theme' => $request['theme'],
            ]);

            return new ComponentResource($updated);

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
    public function destroy($id)
    {
        $deletedId = $this->components->find($id);

        $this->components->delete($id);

        return response()->json(['response' => 1 ], 200);
    }


    protected function validateRequest($request, $id = null)
    {

        $name = $id == null ? 'unique:components,name' : 'unique:components,name,'. $id;

        $request->validate([
            'name' => ['required', 'string', 'max:50', $name],
            'theme' => ['required', 'string', 'max:50'],
        ]);
        return true;
    }
}
