<?php

namespace App\Repositories;

use App\Models\QuotesProducts;

class QuotesProductsRepository
{
    public function allForQuote($quoteId)
    {
        return QuotesProducts::where('quote_id', $quoteId)->get();
    }

    public function updatesProductsPrices($quoteId, $newPrice, $productId){
        $quotesProducts = QuotesProducts::where('quote_id', $quoteId)
        ->where('product_id', $productId)
        ->get();
        foreach ($quotesProducts as $product) {
            $product->price = $newPrice;
            $product->save();
        }
    }

}
