<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\ProductsPriceTypes;
use App\Repositories\ProductsPriceTypesRepository;
use App\Repositories\QuoteRepository;
use App\Repositories\QuotesProductsRepository;
use App\Enums\QuoteStateEnum;

class QuoteService
{
    protected $productsPriceTypesRepository;
    protected $quoteRepository;
    protected $quotesProductsRepository;

    public function __construct()
    {
        $this->productsPriceTypesRepository = new ProductsPriceTypesRepository(new ProductsPriceTypes);
        $this->quoteRepository = new QuoteRepository;
        $this->quotesProductsRepository = new QuotesProductsRepository;
    }

    public function updateTotal($quoteId): string
    {
        $quote = $this->quoteRepository->get($quoteId)[0];
        $quote->total = 0;
        $products = $this->quotesProductsRepository->allForQuote($quote->id);
        foreach($products as $product)
        {
            $quote->total += $product->price;
        }
        $quote->save();
        return strval($quote->total);
    }

    public function getProductPriceTypePrice($clientId, $productId)
    {
        return $this->productsPriceTypesRepository->getProductPrice($clientId, $productId);
    }

    public function getQuoteState($quoteId)
    {
        return $this->quoteRepository->find($quoteId)->status;
    }

    public function changeStateTo(Quote $quote, $state)
    {
        $quote->state = $state;
        $quote->save();
    }

    public function updateProductQuotePrices($priceTypeId,$productId, $newPrice){
        $quotes = $this->quoteRepository->updateProductPrices($priceTypeId,$productId, $newPrice);
        $updatedTotal = 0;
        foreach($quotes as $quote) {
            $updatedTotal = self::updateTotal($quote['id']);
        }
    }
}
