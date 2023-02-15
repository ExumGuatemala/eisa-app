<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'state_id',
        'client_id',
    ];

    /**
     * Get the state Quote
     */
    public function state()
    {
        return $this->belongsTo(QuoteState::class);
    }

    /**
     * Get the client of the Quote
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * The products that belong to the Quote.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'quotes_products', 'quote_id', 'product_id')->withPivot('quantity');
    }
}
