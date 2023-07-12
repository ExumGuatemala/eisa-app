<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Models\Product;

class ProductService
{
    protected $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository(new Product);
    }

    public function getProductExistence($productId)
    {
        return $this->productRepository->find($productId)->existence;
    }

}
