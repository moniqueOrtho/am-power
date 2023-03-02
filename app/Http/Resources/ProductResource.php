<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
                'description' => $this->description,
                'price' => $this->price,
                'type' => $this->type,
                'stock' => $this->stock,
                'VAT' => $this->VAT,
                'expand' => '<p class="font-weight-bold py-2 mb-0 primary--text">' .$this->title . ':</p><p><span class="text-decoration-underline">'. trans_choice('site.descriptions', 1) . ':</span><span> ' . $this->description . '</span></p>'
        ];
    }
}
