<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'src',
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

    /**
     * Get all the sections for the image
     *
     */
    public function sections()
    {
        return $this->belongsToMany(Section::class);
    }
}
