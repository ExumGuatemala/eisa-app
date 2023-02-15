<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'key',
        'address',
        'phone1',
        'phone2',
    ];

    /**
     * Get the municipio for the client.
     */
    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    /**
     * Get the price type for the client.
     */
    public function priceType()
    {
        return $this->belongsTo(PriceType::class, 'pricetype_id');
    }
}
