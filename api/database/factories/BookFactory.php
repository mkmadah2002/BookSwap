<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return ['user_id' => User::factory(), 'category_id' => Category::factory(), 'title' => fake()->sentence(3), 'author' => fake()->name(), 'description' => fake()->paragraph(), 'image_url' => '/images/books/book-1.svg'];
    }
}
