<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\Product;

class QuoteService
{
    public static function addToTotal(Quote $quote, $productId, $qty)
    {
        $product = Product::find($productId);
        $total = $quote->total;
        $total = $total + (float)($product->sale_price * $qty);
        $quote->total = $total;
        $quote->save();
    }

    public static function substractFromTotal(Quote $quote, $productId, $qty)
    {
        $product = Product::find($productId);
        $total = $quote->total;
        $total = $total - (float)($product->sale_price * $qty);
        $quote->total = $total;
        $quote->save();
    }
}
