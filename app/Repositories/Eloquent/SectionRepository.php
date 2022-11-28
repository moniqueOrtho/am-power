<?php

namespace App\Repositories\Eloquent;
use App\Models\Section;
use App\Repositories\Contracts\ISection;
use App\Repositories\Eloquent\BaseRepository;

class SectionRepository extends BaseRepository implements ISection
{
    public function model()
    {
        return Section::class;
    }
}
