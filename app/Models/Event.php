<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'artist_id',
        'title',
        'category',
        'status',
        'city',
        'country',
        'date',
        'price',
        'image',
    ];

    protected $appends = [
        'location',
        'image_url',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'event_id');
    }

    public static function syncCheapestTicketPrice(?int $eventId): void
    {
        if (! $eventId) {
            return;
        }

        $price = Ticket::where('event_id', $eventId)->min('price');

        if ($price === null) {
            return;
        }

        static::whereKey($eventId)->update([
            'price' => $price,
        ]);
    }

    public function getLocationAttribute()
    {
        return "{$this->city}, {$this->country}";
    }

    public function getImageUrlAttribute()
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
