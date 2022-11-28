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
        'body'
    ];

    public function sectionable()
    {
        return $this->morphTo();
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}
