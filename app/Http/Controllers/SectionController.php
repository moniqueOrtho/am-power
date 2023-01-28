<?php

namespace App\Http\Controllers;

use App\Http\Resources\SectionResource;
use Illuminate\Http\Request;
use App\Repositories\Contracts\IPage;
use App\Repositories\Contracts\ISection;

class SectionController extends Controller
{
    protected $pages;
    protected $sections;

    public function __construct(IPage $pages, ISection $sections)
    {

       $this->pages = $pages;
       $this->sections = $sections;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $pageId)
    {
        // Authorize request
        $site = auth()->user()->ownedSites->first();

        $this->authorize('create', [Page::class, $site]);

        $validated = $this->validateRequest($request);

        if($validated) {
            $section = $this->pages->addSection($pageId, [
                'sequence' => $request->sequence,
                'component' => $request->component,
                'title' => $request->title ?? null,
                'subtitle' => $request->subtitle ?? null,
                'body' => $request->body ?? null,
                'text' => $request->text ?? null
            ]);

            return new SectionResource($section);
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
        $site = auth()->user()->ownedSites->first();

        $this->authorize('update', [Page::class, $site]);

        $validated = $this->validateRequest($request);

        if($validated) {
            $section = $this->sections->update($id, [
                'sequence' => $request->sequence,
                'component' => $request->component,
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'body' => $request->body,
                'text' => $request->text
            ]);

            return new SectionResource($section);
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

    }

    protected function validateRequest($request)
    {
        $request->validate([
            'sequence' => ['nullable', 'numeric', 'min:0', 'max: 100'],
            'component' => ['required', 'string'],
            'title' => ['nullable', 'string', 'max:250'],
            'subtitle' => ['nullable', 'string', 'max:250'],
            'body' => ['nullable', 'json'],
            'text' => ['nullable', 'string']
        ]);
        return true;
    }
}
