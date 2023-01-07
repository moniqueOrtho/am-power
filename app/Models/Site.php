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
        'colors',
        'owner_id'
    ];

    protected static function boot()
    {
        parent::boot();

        // When site is created, add the owner as site member
        static::created(function($site) {
            $site->members()->attach($site->owner_id);
        });

        static::deleting(function($site) {
            $site->members()->sync([]);
        });
    }

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class);
    }

    public function hasUser(User $user)
    {
        return $this->members()
                ->where('user_id', $user->id)
                ->first() ? true : false;
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function getMemberIdsAttribute()
    {
        return $this->members()->pluck('id');
    }
}
