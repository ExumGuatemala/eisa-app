<?php

namespace App\Services;

use App\Repositories\ProductsPriceTypesRepository;
use App\Repositories\QuoteRepository;
use App\Repositories\QuotesProductsRepository;
use App\Enums\QuoteTypeEnum;

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

    public function getQuoteStatus($quoteId)
    {
        return $this->quoteRepository->find($quoteId)->status;
    }

    public function changeStateToCreated($quoteId)
    {
        $this->quoteRepository->updateById($quoteId,['status' => QuoteTypeEnum::CREATED]);
    }

    public function updateProductQuotePrices($priceTypeId,$productId, $newPrice){
        $quotes = $this->quoteRepository->updateProductPrices($priceTypeId,$productId, $newPrice);
        $updatedTotal = 0;
        foreach($quotes as $quote) {
            $updatedTotal = self::updateTotal($quote['id']);
        }
    }
    public function setAKey($orderKey){
        $result = $orderKey;
        $is_new = false;
        while (!$is_new){
            if ($this->quoteRepository->countByKey($result) == 0)
            {
                $is_new = true;
            } else {
                $result = strtoupper(substr(bin2hex(random_bytes(ceil(8 / 2))), 0, 8));
            }
        }
        return $result;
    }
}
