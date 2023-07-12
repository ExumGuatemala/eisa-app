<?php

namespace App\Repositories;

use App\Models\ProductsPriceTypes;

class ProductsPriceTypesRepository
{
    protected $model;

    public function __construct(ProductsPriceTypes $model)
    {
        $this->model = $model;
    }
    
    public function findBy(array $attributes)
    {
        $query = $this->model;

        foreach ($attributes as $key => $value) {
            $query = $query->where($key, $value);
        }

        return $query->first();
    }
    
    public function getProductPrice($pricetypeId, $productId)
    {
        return ProductsPriceTypes::where('product_id', $productId)->where('pricetype_id', $pricetypeId)->get()[0]['price'];
    }

    public function firstOrCreate($attributes)
    {
        return ProductsPriceTypes::firstOrCreate($attributes);
    }
}
