<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return ['id' => $this->id, 'title' => $this->title, 'author' => $this->author, 'description' => $this->description, 'image_url' => $this->image_url, 'owner' => new UserResource($this->whenLoaded('owner')), 'category' => $this->whenLoaded('category', fn () => ['id' => $this->category->id, 'name' => $this->category->name, 'slug' => $this->category->slug]), 'tags' => $this->whenLoaded('tags', fn () => $this->tags->pluck('slug')->values()), 'created_at' => $this->created_at?->toISOString()];
    }
}
