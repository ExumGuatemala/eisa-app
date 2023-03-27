<?php

namespace App\Services;

use App\Repositories\ProductsPriceTypesRepository;
use App\Repositories\QuoteRepository;
use App\Repositories\QuotesProductsRepository;

class QuoteService
{
    protected $productsPriceTypesRepository;
    protected $quoteRepository;
    protected $quotesProductsRepository;

    public function __construct()
    {
        $this->productsPriceTypesRepository = new ProductsPriceTypesRepository;
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
            $quote->total += $product->price * $product->quantity;
        }
        $quote->save();
        return strval($quote->total);
    }

    public function getProductPriceTypePrice($clientId, $productId)
    {
        return $this->productsPriceTypesRepository->getProductPrice($clientId, $productId);
    }
}
