<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class TagController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return JsonResource::collection(Tag::orderBy('name')->get());
    }
}
