<?php

namespace App\Http\Resources;

use App\Http\Resources\ViewSiteResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewPageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'site' => new ViewSiteResource($this->site),
            'sections' => SectionResource::collection($this->sections),
            'subpages' =>  ViewSubpageResource::collection($this->subpages)
        ];
    }
}
