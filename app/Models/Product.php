<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable=[
        'site_id',
        'name',
        'title',
        'description',
        'price',
        'VAT'
    ];

    public function site()
    {
        return $this->BelongsTo(Site::class);
    }

    public function sections()
    {
        return $this->morphMany(Section::class, 'sectionable')
            ->orderBy('sequence', 'asc');
    }
}
