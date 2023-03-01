<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /**
     * Get the Clients for any given price type.
     */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    /**
     * The quotes that belong to the Product.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_pricetypes', 'pricetype_id', 'product_id')->withPivot('price');
    }
}
