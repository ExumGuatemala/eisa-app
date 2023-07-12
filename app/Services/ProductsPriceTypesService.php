<?php

namespace App\Services;

use App\Repositories\ProductsPriceTypesRepository;
use App\Repositories\ProductRepository;
use App\Repositories\PriceTypeRepository;
use App\Models\ProductsPriceTypes;
use App\Models\Product;

class ProductsPriceTypesService
{
    protected $productsPriceTypeRepository;
    protected $productRepository;
    protected $priceTypeRepository;

    public function __construct()
    {
        $this->productsPriceTypeRepository = new ProductsPriceTypesRepository(new ProductsPriceTypes);
        $this->productRepository = new ProductRepository(new Product);
        $this->priceTypeRepository = new PriceTypeRepository;
    }

    public function addProductToPriceTypes($productId, $price)
    {
        $priceTypes = $this->priceTypeRepository->all();
        foreach($priceTypes as $pt)
        {
            $this->productsPriceTypeRepository->firstOrCreate(['product_id' => $productId, 'pricetype_id' => $pt->id, 'price' => $price]);
        }
    }

    public function getProductPrice($productId, $pricetypeId)
    {
        return $this->productsPriceTypeRepository->findBy(['product_id' => $productId, 'pricetype_id' => $pricetypeId])->price;
    }

    public function getProductExistence($productId)
    {
        return $this->productRepository->find($productId)->existence;
    }

}
