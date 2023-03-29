<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsPriceTypes extends Model
{
    use HasFactory;

    protected $table = 'products_pricetypes';

    protected $fillable = [
        'product_id',
        'pricetype_id',
        'price'
    ];
}
