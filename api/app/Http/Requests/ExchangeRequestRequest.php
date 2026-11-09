<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ExchangeRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $partial = $this->isMethod('PATCH');

        return ['user_id' => [$partial ? 'sometimes' : 'required', 'integer', 'exists:users,id'], 'book_id' => [$partial ? 'sometimes' : 'required', 'integer', 'exists:books,id'], 'exchange_type' => [$partial ? 'sometimes' : 'required', Rule::in(['permanent', 'temporary'])], 'start_date' => ['nullable', 'date', 'required_if:exchange_type,temporary'], 'end_date' => ['nullable', 'date', 'required_if:exchange_type,temporary', 'after:start_date'], 'status' => [$partial ? 'sometimes' : 'required', Rule::in(['open', 'closed', 'cancelled'])]];
    }

    public function after(): array
    {
        return [function (Validator $v): void {
            $user = $this->input('user_id', $this->route('exchange_request')?->user_id);
            $book = Book::find($this->input('book_id', $this->route('exchange_request')?->book_id));
            if ($book && $user && (int) $book->user_id !== (int) $user) {
                $v->errors()->add('book_id', 'Le livre doit appartenir au propriétaire de la demande.');
            }
        }];
    }
}
