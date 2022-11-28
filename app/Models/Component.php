<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    protected $fillable=[
        'theme',
        'name',
    ];

    public function section()
    {
        return $this->hasOne(Section::class);
    }
}
