<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\ProductsPriceTypes;

class ProductsPriceTypesRepository
{
    public function getProductPrice($clientId, $productId)
    {
        $pricetypeId = Client::find($clientId)->pricetype_id;
        return ProductsPriceTypes::where('product_id', $productId)->where('pricetype_id', $pricetypeId)->get()[0]['price'];
    }
}
