<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'pre_delivery',
        'delivery',
    ];

    /**
     * The products that belong to the Quote.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'quotes_orders_products', 'order_id', 'product_id')->withPivot('quantity');
    }
}
