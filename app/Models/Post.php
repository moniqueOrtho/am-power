<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable=[
        'site_id',
        'title',
        'subtitle',
        'summary',
        'slug',
    ];

    public function site()
    {
        return BelongsTo(Site::class);
    }


    public function sections()
    {
        return $this->morphMany(Section::class, 'sectionable');
    }
}
