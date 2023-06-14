<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'status',
        'client_id',
        'pricetype_id'
    ];

    /**
     * Get the client of the Quote
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the price type of reference for the Quote
     */
    public function priceType()
    {
        return $this->belongsTo(PriceType::class, 'pricetype_id');
    }

    /**
     * The products that belong to the Quote.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'quotes_products', 'quote_id', 'product_id')->withPivot('quantity', 'description', 'height', 'width');
    }
}
