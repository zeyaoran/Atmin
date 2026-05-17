<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Artist extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    /*
    |----------------------------------
    | RELATIONSHIP
    |----------------------------------
    */

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /*
    |----------------------------------
    | APPENDED ATTRIBUTES (API)
    |----------------------------------
    */

    protected $appends = [
        'image_url',
    ];

    /*
    |----------------------------------
    | HIDDEN FIELDS (biar API clean)
    |----------------------------------
    */

    protected $hidden = [
        'image',
        'updated_at',
        'created_at',
    ];

    /*
    |----------------------------------
    | ACCESSOR IMAGE URL
    |----------------------------------
    */

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }
}