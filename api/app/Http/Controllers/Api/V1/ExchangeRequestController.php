<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExchangeRequestRequest;
use App\Http\Resources\ExchangeRequestResource;
use App\Models\ExchangeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ExchangeRequestController extends Controller
{
    private const RELATIONS = ['owner', 'book.owner', 'book.category', 'book.tags', 'offers.author', 'offers.offeredBook.category', 'offers.offeredBook.tags'];

    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate(['search' => 'nullable|string|max:100', 'exchange_type' => 'nullable|in:permanent,temporary', 'category_id' => 'nullable|integer|exists:categories,id', 'status' => 'nullable|in:open,closed,cancelled', 'sort' => 'nullable|in:newest,oldest', 'page' => 'nullable|integer|min:1']);
        $q = ExchangeRequest::query()->with(self::RELATIONS)->withCount('offers');
        $q->when($request->search, fn ($q, $v) => $q->whereHas('book', fn ($b) => $b->where('title', 'like', "%{$v}%")))->when($request->exchange_type, fn ($q, $v) => $q->where('exchange_type', $v))->when($request->category_id, fn ($q, $v) => $q->whereHas('book', fn ($b) => $b->where('category_id', $v)))->when($request->status, fn ($q, $v) => $q->where('status', $v))->orderBy('created_at', $request->sort === 'oldest' ? 'asc' : 'desc');

        return ExchangeRequestResource::collection($q->paginate(10)->withQueryString());
    }

    public function store(ExchangeRequestRequest $request): JsonResponse
    {
        $model = ExchangeRequest::create($request->validated());

        return (new ExchangeRequestResource($model->load(self::RELATIONS)->loadCount('offers')))->response()->setStatusCode(201);
    }

    public function show(ExchangeRequest $exchangeRequest): ExchangeRequestResource
    {
        return new ExchangeRequestResource($exchangeRequest->load(self::RELATIONS)->loadCount('offers'));
    }

    public function update(ExchangeRequestRequest $request, ExchangeRequest $exchangeRequest): ExchangeRequestResource
    {
        $exchangeRequest->update($request->validated());

        return new ExchangeRequestResource($exchangeRequest->fresh()->load(self::RELATIONS)->loadCount('offers'));
    }

    public function destroy(ExchangeRequest $exchangeRequest): Response
    {
        $exchangeRequest->delete();

        return response()->noContent();
    }
}
