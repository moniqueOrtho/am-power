<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SiteResource extends JsonResource
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
            'lang' => $this->lang,
            'theme' => $this->theme,
            'colors' => $this->colors,
            'owner_name' => $this->owner->name,
            'owner_id' => $this->owner->id
        ];
    }
}
