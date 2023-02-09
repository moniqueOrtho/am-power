<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'alt',
        'upload_successful',
        'disk'
    ];

    /**
     * Get the user for a image
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImagesAttribute()
    {
        return [
            'thumbnail' => $this->getImagePath('thumbnail'),
            'original' => $this->getImagePath('original'),
            'large' => $this->getImagePath('large'),
        ];
    }

    protected function getImagePath($size)
    {
        return Storage::disk($this->disk)
                ->url("uploads/images/{$size}/" . $this->name);
    }

}
