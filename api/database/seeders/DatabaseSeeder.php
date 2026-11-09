<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\ExchangeOffer;
use App\Models\ExchangeRequest;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Yassine Amrani', 'Salma Bennani', 'Aya Alaoui', 'Omar Tazi', 'Imane Idrissi', 'Hamza El Fassi', 'Sara Chraibi', 'Mehdi Berrada', 'Lina El Mansouri', 'Adam Naciri', 'Nora Kabbaj', 'Rayan Filali'];
        foreach ($names as $i => $name) {
            User::create(['name' => $name, 'email' => 'apprenant'.($i + 1).'@bookswap.local', 'password' => Hash::make('bookswap'), 'avatar' => '/images/avatars/avatar-'.(($i % 6) + 1).'.svg', 'group_name' => $i < 6 ? 'CyphX A' : 'CyphX B']);
        }
        foreach (['Développement', 'JavaScript', 'Architecture', 'Qualité logicielle', 'Bases de données', 'DevOps'] as $name) {
            Category::create(['name' => $name, 'slug' => Str::slug($name)]);
        }
        foreach (['clean-code', 'qualite', 'javascript', 'web', 'architecture', 'design-patterns', 'refactoring', 'agile', 'database', 'sql', 'devops', 'testing', 'api', 'laravel', 'algorithmes'] as $name) {
            Tag::create(['name' => Str::headline($name), 'slug' => $name]);
        }
        $titles = [['Clean Code', 'Robert C. Martin'], ['Clean Architecture', 'Robert C. Martin'], ['Eloquent JavaScript', 'Marijn Haverbeke'], ['JavaScript: The Definitive Guide', 'David Flanagan'], ['Refactoring', 'Martin Fowler'], ['Design Patterns', 'Gamma, Helm, Johnson, Vlissides'], ['The Pragmatic Programmer', 'Andrew Hunt & David Thomas'], ['You Don’t Know JS Yet', 'Kyle Simpson'], ['Domain-Driven Design', 'Eric Evans'], ['Working Effectively with Legacy Code', 'Michael Feathers'], ['Test Driven Development', 'Kent Beck'], ['Patterns of Enterprise Application Architecture', 'Martin Fowler'], ['Head First Design Patterns', 'Eric Freeman'], ['Introduction to Algorithms', 'Cormen et al.'], ['SQL Antipatterns', 'Bill Karwin'], ['Designing Data-Intensive Applications', 'Martin Kleppmann'], ['Release It!', 'Michael Nygard'], ['The Phoenix Project', 'Gene Kim'], ['Continuous Delivery', 'Jez Humble'], ['Accelerate', 'Forsgren, Humble & Kim'], ['Laravel: Up & Running', 'Matt Stauffer'], ['RESTful Web APIs', 'Leonard Richardson'], ['API Design Patterns', 'JJ Geewax'], ['Grokking Algorithms', 'Aditya Bhargava'], ['Code Complete', 'Steve McConnell'], ['The Clean Coder', 'Robert C. Martin'], ['Effective TypeScript', 'Dan Vanderkam'], ['Learning SQL', 'Alan Beaulieu'], ['Building Microservices', 'Sam Newman'], ['Fundamentals of Software Architecture', 'Richards & Ford']];
        foreach ($titles as $i => $item) {
            $book = Book::create(['user_id' => ($i % 12) + 1, 'category_id' => ($i % 6) + 1, 'title' => $item[0], 'author' => $item[1], 'description' => 'Une référence pratique pour progresser en développement logiciel, avec des concepts clairs et des exemples concrets.', 'image_url' => '/images/books/book-'.(($i % 8) + 1).'.svg']);
            $book->tags()->attach([($i % 15) + 1, (($i + 4) % 15) + 1]);
        }
        for ($i = 1; $i <= 20; $i++) {
            $temporary = $i % 2 === 0;
            ExchangeRequest::create(['user_id' => Book::find($i)->user_id, 'book_id' => $i, 'exchange_type' => $temporary ? 'temporary' : 'permanent', 'start_date' => $temporary ? '2026-11-15' : null, 'end_date' => $temporary ? '2026-12-15' : null, 'status' => $i <= 14 ? 'open' : ($i <= 18 ? 'closed' : 'cancelled'), 'created_at' => now()->subDays(21 - $i)]);
        }
        for ($i = 1; $i <= 30; $i++) {
            $request = ExchangeRequest::find((($i - 1) % 14) + 1);
            $candidate = Book::find((($i + 9) % 30) + 1);
            if ($candidate->user_id === $request->user_id) {
                $candidate = Book::find((($candidate->id) % 30) + 1);
            } $temporary = $i % 3 === 0;
            ExchangeOffer::create(['exchange_request_id' => $request->id, 'user_id' => $candidate->user_id, 'offered_book_id' => $candidate->id, 'exchange_type' => $temporary ? 'temporary' : 'permanent', 'start_date' => $temporary ? '2026-11-20' : null, 'end_date' => $temporary ? '2026-12-20' : null, 'message' => 'Bonjour, ce livre pourrait vous intéresser pour cet échange.', 'status' => $i % 7 === 0 ? 'accepted' : ($i % 5 === 0 ? 'rejected' : 'pending')]);
        }
    }
}
