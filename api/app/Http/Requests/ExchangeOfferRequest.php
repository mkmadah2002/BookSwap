<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ExchangeOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $partial = $this->isMethod('PATCH');

        return ['user_id' => [$partial ? 'sometimes' : 'required', 'integer', 'exists:users,id'], 'offered_book_id' => [$partial ? 'sometimes' : 'required', 'integer', 'exists:books,id'], 'exchange_type' => [$partial ? 'sometimes' : 'required', Rule::in(['permanent', 'temporary'])], 'start_date' => ['nullable', 'date', 'required_if:exchange_type,temporary'], 'end_date' => ['nullable', 'date', 'required_if:exchange_type,temporary', 'after:start_date'], 'message' => ['nullable', 'string', 'max:1000'], 'status' => [$partial ? 'sometimes' : 'required', Rule::in(['pending', 'accepted', 'rejected'])]];
    }

    public function after(): array
    {
        return [function (Validator $v): void {
            $offer = $this->route('exchange_offer');
            $request = $this->route('exchange_request') ?? $offer?->exchangeRequest;
            $user = $this->input('user_id', $offer?->user_id);
            $book = Book::find($this->input('offered_book_id', $offer?->offered_book_id));
            if ($request && $request->status !== 'open' && ! $offer) {
                $v->errors()->add('exchange_request_id', 'Une offre ne peut être ajoutée que sur une demande ouverte.');
            }if ($book && $user && (int) $book->user_id !== (int) $user) {
                $v->errors()->add('offered_book_id', 'Le livre proposé doit appartenir à l’auteur de l’offre.');
            }if ($request && $user && (int) $request->user_id === (int) $user) {
                $v->errors()->add('user_id', 'Vous ne pouvez pas proposer un échange contre votre propre demande.');
            }if ($request && $book && (int) $request->book_id === (int) $book->id) {
                $v->errors()->add('offered_book_id', 'Le livre proposé doit être différent du livre demandé.');
            }
        }];
    }
}
