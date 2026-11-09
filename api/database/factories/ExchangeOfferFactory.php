<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\ExchangeOffer;
use App\Models\ExchangeRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExchangeOfferFactory extends Factory
{
    protected $model = ExchangeOffer::class;

    public function definition(): array
    {
        $book = Book::factory()->create();

        return ['exchange_request_id' => ExchangeRequest::factory(), 'user_id' => $book->user_id, 'offered_book_id' => $book->id, 'exchange_type' => 'permanent', 'status' => 'pending'];
    }
}
