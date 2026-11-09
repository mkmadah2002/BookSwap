<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return BookResource::collection(Book::with(['owner', 'category', 'tags'])->orderBy('title')->paginate(12));
    }

    public function show(Book $book): BookResource
    {
        return new BookResource($book->load(['owner', 'category', 'tags']));
    }
}
