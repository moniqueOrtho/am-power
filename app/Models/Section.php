<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'title',
        'subtitle',
        'sequence',
        'body',
        'text',
        'icon',
        'sectionable_id',
        'sectionable_type'
    ];

     /**
     * Get all the images for the section
     *
     */
    public function images()
    {
        return $this->belongsToMany(Image::class);
    }

    public function sectionable()
    {
        return $this->morphTo();
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}
