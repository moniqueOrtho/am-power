<?php

namespace App\Repositories\Eloquent;
use App\Models\Post;
use App\Repositories\Contracts\IPost;
use App\Repositories\Eloquent\BaseRepository;

class PostRepository extends BaseRepository implements IPost
{
    public function model()
    {
        return Post::class;
    }
}
