<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeOfferResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return ['id' => $this->id, 'exchange_request_id' => $this->exchange_request_id, 'exchange_type' => $this->exchange_type, 'status' => $this->status, 'start_date' => $this->start_date?->toDateString(), 'end_date' => $this->end_date?->toDateString(), 'message' => $this->message, 'author' => new UserResource($this->whenLoaded('author')), 'offered_book' => new BookResource($this->whenLoaded('offeredBook')), 'created_at' => $this->created_at?->toISOString()];
    }
}
