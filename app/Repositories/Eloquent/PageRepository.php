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

    public function addSection($pageId, array $data)
    {
        // Get the page for which we want to create a section
        $page = $this->find($pageId);

        // Create the section for the page
        $section = $page->sections()->create($data);

        return $section;
    }
}
