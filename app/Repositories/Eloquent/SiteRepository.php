<?php

namespace App\Repositories\Eloquent;
use App\Models\Site;
use App\Repositories\Contracts\ISite;
use App\Repositories\Eloquent\BaseRepository;

class SiteRepository extends BaseRepository implements ISite
{
    public function model()
    {
        return Site::class;
    }
}
