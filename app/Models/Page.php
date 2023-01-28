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
        'sequence',
        'required'
    ];

    public function site()
    {
        return $this->BelongsTo(Site::class);
    }

    public function subpages()
    {
        return $this->hasMany(Subpage::class);
    }

    public function sections()
    {
        return $this->morphMany(Section::class, 'sectionable')
            ->orderBy('sequence', 'asc');
    }
}
