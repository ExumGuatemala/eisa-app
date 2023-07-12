<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\PriceType;
use App\Models\ProductsPriceTypes;

class PriceTypeRepository
{
    
    public function all()
    {
        return Pricetype::all();
    }

    
}
