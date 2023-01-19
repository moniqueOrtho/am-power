<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
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
            'sequence' => $this->sequence,
            'component' => $this->component,
            'name' => $this->name,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'body' => $this->body,
            'text' => $this->text,
            'icon' => $this->icon,
        ];
    }
}
