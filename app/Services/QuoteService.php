<?php

namespace App\Services;

use App\Models\Quote;
use App\Repositories\ProductsPriceTypesRepository;

class QuoteService
{
    protected $productsPriceTypesRepository;

    public function __construct()
    {
        $this->productsPriceTypesRepository = new ProductsPriceTypesRepository;
    }

    public function addToTotal(Quote $quote, $productId, $qty)
    {
        $price = $this->productsPriceTypesRepository->getProductPrice($quote->client_id, $productId);
        $total = $quote->total;
        $total = $total + (float)($price * $qty);
        $quote->total = $total;
        $quote->save();
    }

    public function substractFromTotal(Quote $quote, $productId, $qty)
    {
        $price = $this->productsPriceTypesRepository->getProductPrice($quote->client_id, $productId);
        $total = $quote->total;
        $total = $total - (float)($price * $qty);
        $quote->total = $total;
        $quote->save();
    }

    public function getProductPriceTypePrice($clientId, $productId)
    {
        return $this->productsPriceTypesRepository->getProductPrice($clientId, $productId);
    }
}
