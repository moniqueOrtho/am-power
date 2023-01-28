<?php

namespace App\Repositories\Contracts;

interface IPage
{
    public function addSection($pageId, array $data);
}
