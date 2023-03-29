<?php

namespace App\Services;

use App\Repositories\ProductsPriceTypesRepository;
use App\Repositories\PriceTypeRepository;

class ProductsPriceTypesService
{
    protected $productsPriceTypesRepository;
    protected $priceTypeRepository;

    public function __construct()
    {
        $this->productsPriceTypesRepository = new ProductsPriceTypesRepository;
        $this->priceTypeRepository = new PriceTypeRepository;
    }

    public function addProductToPriceTypes($productId, $price)
    {
        $priceTypes = $this->priceTypeRepository->all();
        foreach($priceTypes as $pt)
        {
            $this->productsPriceTypesRepository->firstOrCreate(['product_id' => $productId, 'pricetype_id' => $pt->id, 'price' => $price]);
        }
    }

}
