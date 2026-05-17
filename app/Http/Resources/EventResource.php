<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'city' => $this->city,
            'country' => $this->country,
            'location' => $this->location,
            'category' => $this->category,
            'status' => $this->status,
            'date' => $this->date,
            'price' => $this->price,
            'image_url' => $this->image_url,

            'artist' => [
                'id' => $this->artist?->id,
                'name' => $this->artist?->name,
                'image_url' => $this->artist?->image_url,
            ],

            'tickets' => $this->whenLoaded('tickets', function () {
                return $this->tickets->map(function ($ticket) {
                    return [
                        'id' => $ticket->id,
                        'category' => $ticket->category,
                        'price' => (float) $ticket->price,
                        'stock' => $ticket->stock,
                    ];
                })->values();
            }),
        ];
    }
}
