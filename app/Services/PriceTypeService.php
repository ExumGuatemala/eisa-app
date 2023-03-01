<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductPriceTypes;
use App\Models\PriceType;

class PriceTypeService
{
    public static function addProductsToPriceType(PriceType $pricetype)
    {
        $products = Product::all();
        foreach($products as $product){
            $pricetype->products()->attach($product->id, ['price' => 0]);
        }

    }

}
