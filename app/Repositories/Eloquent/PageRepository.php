<?php

namespace App\Repositories\Eloquent;
use App\Models\Page;
use App\Repositories\Contracts\IPage;
use App\Repositories\Eloquent\BaseRepository;

class PageRepository extends BaseRepository implements IPage
{
    public function model()
    {
        return Page::class;
    }
}
