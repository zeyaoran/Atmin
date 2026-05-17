<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtistResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,

            'image_url' => $this->image_url,

            'events_count' => $this->whenCounted('events'),

            'events' => $this->whenLoaded('events', function () {
                return $this->events->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'title' => $event->title,
                        'location' => $event->location,
                        'date' => $event->date,
                        'price' => $event->price,
                        'image_url' => $event->image_url,
                        'tickets' => $event->relationLoaded('tickets')
                            ? $event->tickets->map(fn ($ticket) => [
                                'id' => $ticket->id,
                                'category' => $ticket->category,
                                'price' => (float) $ticket->price,
                                'stock' => $ticket->stock,
                            ])->values()
                            : [],
                    ];
                });
            }),
        ];
    }
}
