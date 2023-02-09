<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteState extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /**
     * Get the Quotes for any given state.
     */
    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}
