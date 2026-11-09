<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['exchange_request_id', 'user_id', 'offered_book_id', 'exchange_type', 'start_date', 'end_date', 'message', 'status'])] class ExchangeOffer extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date'];
    }

    public function exchangeRequest()
    {
        return $this->belongsTo(ExchangeRequest::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function offeredBook()
    {
        return $this->belongsTo(Book::class, 'offered_book_id');
    }
}
