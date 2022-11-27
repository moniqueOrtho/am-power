<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'title',
        'subtitle',
        'description',
        'slug',
        'lang',
        'theme',
        'colors'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
