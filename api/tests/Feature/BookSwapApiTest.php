<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\ExchangeOffer;
use App\Models\ExchangeRequest;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookSwapApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_liste_des_demandes_avec_relations_et_pagination(): void
    {
        $this->getJson('/api/v1/exchange-requests')->assertOk()->assertJsonStructure(['data' => [['id', 'owner', 'book' => ['category', 'tags'], 'offers_count', 'offers']], 'links', 'meta'])->assertJsonCount(10, 'data');
    }

    public function test_recupere_une_demande_par_id(): void
    {
        $this->getJson('/api/v1/exchange-requests/1')->assertOk()->assertJsonPath('data.id', 1)->assertJsonPath('data.book.title', 'Clean Code');
    }

    public function test_recherche_et_filtres_sont_combinables(): void
    {
        $this->getJson('/api/v1/exchange-requests?search=Clean%20Architecture&exchange_type=temporary&status=open&sort=oldest')->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.book.title', 'Clean Architecture');
    }

    public function test_pagination_change_de_page(): void
    {
        $this->getJson('/api/v1/exchange-requests?page=2')->assertOk()->assertJsonPath('meta.current_page', 2);
    }

    public function test_creation_demande_valide(): void
    {
        $book = Book::findOrFail(21);
        $this->postJson('/api/v1/exchange-requests', ['user_id' => $book->user_id, 'book_id' => $book->id, 'exchange_type' => 'temporary', 'start_date' => '2026-12-01', 'end_date' => '2026-12-10', 'status' => 'open'])->assertCreated()->assertJsonPath('data.book.id', $book->id);
    }

    public function test_rejette_demande_temporaire_sans_dates(): void
    {
        $book = Book::findOrFail(21);
        $this->postJson('/api/v1/exchange-requests', ['user_id' => $book->user_id, 'book_id' => $book->id, 'exchange_type' => 'temporary', 'status' => 'open'])->assertUnprocessable()->assertJsonValidationErrors(['start_date', 'end_date']);
    }

    public function test_rejette_livre_qui_n_appartient_pas_au_proprietaire(): void
    {
        $this->postJson('/api/v1/exchange-requests', ['user_id' => 2, 'book_id' => 1, 'exchange_type' => 'permanent', 'status' => 'open'])->assertUnprocessable()->assertJsonValidationErrors('book_id');
    }

    public function test_modifie_une_demande_partiellement(): void
    {
        $this->patchJson('/api/v1/exchange-requests/1', ['status' => 'closed'])->assertOk()->assertJsonPath('data.status', 'closed');
        $this->assertDatabaseHas('exchange_requests', ['id' => 1, 'status' => 'closed']);
    }

    public function test_supprime_une_demande(): void
    {
        $this->deleteJson('/api/v1/exchange-requests/1')->assertNoContent();
        $this->assertDatabaseMissing('exchange_requests', ['id' => 1]);
    }

    public function test_creation_offre_valide(): void
    {
        $request = ExchangeRequest::findOrFail(1);
        $book = Book::where('user_id', '!=', $request->user_id)->where('id', '!=', $request->book_id)->firstOrFail();
        $this->postJson('/api/v1/exchange-requests/1/offers', ['user_id' => $book->user_id, 'offered_book_id' => $book->id, 'exchange_type' => 'permanent', 'status' => 'pending'])->assertCreated()->assertJsonPath('data.offered_book.id', $book->id);
    }

    public function test_rejette_offre_sur_demande_fermee(): void
    {
        $request = ExchangeRequest::findOrFail(15);
        $book = Book::where('user_id', '!=', $request->user_id)->firstOrFail();
        $this->postJson('/api/v1/exchange-requests/15/offers', ['user_id' => $book->user_id, 'offered_book_id' => $book->id, 'exchange_type' => 'permanent', 'status' => 'pending'])->assertUnprocessable()->assertJsonValidationErrors('exchange_request_id');
    }

    public function test_rejette_offre_avec_livre_d_un_autre_utilisateur(): void
    {
        $request = ExchangeRequest::findOrFail(1);
        $book = Book::where('user_id', '!=', $request->user_id)->firstOrFail();
        $wrongUser = Book::where('user_id', '!=', $book->user_id)->where('user_id', '!=', $request->user_id)->value('user_id');
        $this->postJson('/api/v1/exchange-requests/1/offers', ['user_id' => $wrongUser, 'offered_book_id' => $book->id, 'exchange_type' => 'permanent', 'status' => 'pending'])->assertUnprocessable()->assertJsonValidationErrors('offered_book_id');
    }

    public function test_ressources_inconnues_retournent_404(): void
    {
        $this->getJson('/api/v1/exchange-requests/9999')->assertNotFound();
        $this->getJson('/api/v1/exchange-offers/9999')->assertNotFound();
    }

    public function test_relations_eloquent_sont_coherentes(): void
    {
        $offer = ExchangeOffer::with(['exchangeRequest.book', 'author', 'offeredBook'])->firstOrFail();
        $this->assertSame($offer->user_id, $offer->offeredBook->user_id);
        $this->assertNotSame($offer->exchangeRequest->user_id,$offer->user_id);
    }
}
