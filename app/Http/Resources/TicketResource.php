<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'category' => $this->category,
            'price' => (float) $this->price,
            'stock' => $this->stock,

            // relasi artist (diperkecil biar ringan)
            'artist' => $this->whenLoaded('artist', function () {
                return [
                    'id' => $this->artist->id,
                    'name' => $this->artist->name,
                    'image_url' => $this->artist->image_url ?? null,
                ];
            }),

            // relasi event (diperkecil juga)
            'event' => $this->whenLoaded('event', function () {
                if (!$this->event) {
                    return null;
                }

                return [
                    'id' => $this->event->id,
                    'title' => $this->event->title,
                    'location' => $this->event->location,
                    'date' => $this->event->date,
                    'image_url' => $this->event->image_url ?? null,
                ];
            }),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
