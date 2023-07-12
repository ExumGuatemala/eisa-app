<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }
    
    public function find(int $id)
    {
        return $this->model->find($id);
    }
}
