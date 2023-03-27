<?php

namespace App\Repositories;

use App\Models\Quote;
use App\Models\Client;
use App\Models\ProductsPriceTypes;

class QuoteRepository
{
    public function all()
    {
        return Quote::all();
    }

    public function get($id)
    {
        return Quote::where('id', $id)->get();
    }

    public function updateTotal($pricetypeId, $productId)
    {
        return ProductsPriceTypes::where('product_id', $productId)->where('pricetype_id', $pricetypeId)->get()[0]['price'];
    }
}
