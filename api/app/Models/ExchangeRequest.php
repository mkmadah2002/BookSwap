<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'book_id', 'exchange_type', 'start_date', 'end_date', 'status'])] class ExchangeRequest extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date'];
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function offers()
    {
        return $this->hasMany(ExchangeOffer::class);
    }
}
