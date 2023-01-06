<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'permission' => $this->permission,
            'description' => $this->description,
            'expand' => '<p class="font-weight-bold py-2 mb-0 primary--text">' . __( 'site.roles_of_the_permission', ['permission' => $this->permission] ) . '</p><p class="pb-2">'. $this->rolesName->implode(', ') . '</p>',
            'roles' => $this->roleIds
        ];
    }
}
