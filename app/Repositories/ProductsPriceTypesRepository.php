<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\ProductsPriceTypes;

class ProductsPriceTypesRepository
{
    public function getProductPrice($pricetypeId, $productId)
    {
        return ProductsPriceTypes::where('product_id', $productId)->where('pricetype_id', $pricetypeId)->get()[0]['price'];
    }

    public function firstOrCreate($attributes)
    {
        return ProductsPriceTypes::firstOrCreate($attributes);
    }
}
