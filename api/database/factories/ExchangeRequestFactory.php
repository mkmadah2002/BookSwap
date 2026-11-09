<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\ExchangeRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExchangeRequestFactory extends Factory
{
    protected $model = ExchangeRequest::class;

    public function definition(): array
    {
        $book = Book::factory()->create();

        return ['user_id' => $book->user_id, 'book_id' => $book->id, 'exchange_type' => 'permanent', 'status' => 'open'];
    }
}
