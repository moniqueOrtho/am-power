<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subpage extends Model
{
    use HasFactory;

    protected $fillable=[
        'page_id',
        'name',
        'title',
        'subtitle',
        'description',
        'slug',
        'icon',
        'sequence'
    ];

    public function page()
    {
        return BelongsTo(Page::class);
    }

    public function sections()
    {
        return $this->morphMany(Section::class, 'sectionable');
    }
}
