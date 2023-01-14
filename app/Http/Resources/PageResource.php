<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'url' => '/' . strtolower(trans_choice('site.pages', 1)) . '/' . $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'expand' => '<p class="font-weight-bold py-2 mb-0 primary--text">' .$this->title . ':</p><p><span class="text-decoration-underline">'. trans_choice('site.subtitles', 1) . ':</span><span> ' . $this->subtitle . '</span></p><p><span class="text-decoration-underline">'. trans_choice('site.descriptions', 1) . ':</span><span> ' . $this->description . '</span></p>'
        ];
    }
}
