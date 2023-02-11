<?php

namespace App\Repositories\Eloquent;
use App\Models\Image;
use App\Repositories\Contracts\IImage;
use App\Repositories\Eloquent\BaseRepository;

class ImageRepository extends BaseRepository implements IImage
{
    public function model()
    {
        return Image::class;
    }

    public function getUserImages()
    {
        $images = $this->findWhere('user_id', auth()->user()->id);
        if($images->isNotEmpty()) {
            $images = $images->map(function ($image){
                return [
                    'id' => $image->id,
                    'name' => $image->name,
                    'alt' => $image->alt,
                    'sizes' => $image->images
                ];
            });
        }
        return $images;
    }
}
