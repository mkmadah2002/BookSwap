<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return ['id' => $this->id, 'exchange_type' => $this->exchange_type, 'status' => $this->status, 'start_date' => $this->start_date?->toDateString(), 'end_date' => $this->end_date?->toDateString(), 'created_at' => $this->created_at?->toISOString(), 'updated_at' => $this->updated_at?->toISOString(), 'owner' => new UserResource($this->whenLoaded('owner')), 'book' => new BookResource($this->whenLoaded('book')), 'offers_count' => $this->whenCounted('offers'), 'offers' => ExchangeOfferResource::collection($this->whenLoaded('offers'))];
    }
}
