<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'role' => $this->role,
            'description' => $this->description,
            'expand' => '<p class="font-weight-bold py-2 mb-0 primary--text">' . __( 'site.permissions_of_role', ['role' => $this->role] ) . '</p><p class="pb-2">'. $this->getPermissions()->implode(', ') . '</p>',
            'permissions' => $this->permissionIds
        ];
    }
}
