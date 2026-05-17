<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Artist;
use App\Models\Event;

class Ticket extends Model
{
    protected $fillable = [
    'artist_id',
    'event_id',
    'category',
    'price',
    'stock',
];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}