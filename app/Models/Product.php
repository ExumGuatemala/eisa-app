<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'existence',
        'order',
        'sale_price',
    ];

    /**
     * The quotes that belong to the Product.
     */
    public function quotes()
    {
        return $this->belongsToMany(Quote::class, 'quotes_products', 'quote_id', 'product_id')->withPivot('quantity');
    }

    /**
     * The quotes that belong to the Product.
     */
    public function pricetypes()
    {
        return $this->belongsToMany(PriceType::class, 'products_pricetypes', 'pricetype_id', 'product_id')->withPivot('price');
    }
}
