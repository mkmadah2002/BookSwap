<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExchangeOfferRequest;
use App\Http\Resources\ExchangeOfferResource;
use App\Models\ExchangeOffer;
use App\Models\ExchangeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ExchangeOfferController extends Controller
{
    private const RELATIONS = ['author', 'offeredBook.owner', 'offeredBook.category', 'offeredBook.tags'];

    public function index(ExchangeRequest $exchangeRequest): AnonymousResourceCollection
    {
        return ExchangeOfferResource::collection($exchangeRequest->offers()->with(self::RELATIONS)->latest()->paginate(10));
    }

    public function store(ExchangeOfferRequest $request, ExchangeRequest $exchangeRequest): JsonResponse
    {
        $offer = $exchangeRequest->offers()->create($request->validated());

        return (new ExchangeOfferResource($offer->load(self::RELATIONS)))->response()->setStatusCode(201);
    }

    public function show(ExchangeOffer $exchangeOffer): ExchangeOfferResource
    {
        return new ExchangeOfferResource($exchangeOffer->load(self::RELATIONS));
    }

    public function update(ExchangeOfferRequest $request, ExchangeOffer $exchangeOffer): ExchangeOfferResource
    {
        $exchangeOffer->update($request->validated());

        return new ExchangeOfferResource($exchangeOffer->fresh()->load(self::RELATIONS));
    }

    public function destroy(ExchangeOffer $exchangeOffer): Response
    {
        $exchangeOffer->delete();

        return response()->noContent();
    }
}
