<?php

namespace App\Repositories\Eloquent;
use App\Models\Subpage;
use App\Repositories\Contracts\ISubpage;
use App\Repositories\Eloquent\BaseRepository;

class SubpageRepository extends BaseRepository implements ISubpage
{
    public function model()
    {
        return Subpage::class;
    }
}
