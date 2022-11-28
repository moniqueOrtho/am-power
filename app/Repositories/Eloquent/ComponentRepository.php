<?php

namespace App\Repositories\Eloquent;
use App\Models\Component;
use App\Repositories\Contracts\IComponent;
use App\Repositories\Eloquent\BaseRepository;

class ComponentRepository extends BaseRepository implements IComponent
{
    public function model()
    {
        return Component::class;
    }
}
