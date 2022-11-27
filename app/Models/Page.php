<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    use HasFactory;

    protected $fillable=[
        'site_id',
        'name',
        'title',
        'subtitle',
        'description',
        'slug',
        'icon',
        'head_id',
        'sub_order'
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
