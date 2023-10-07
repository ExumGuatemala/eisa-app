<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'start_date',
        'end_date',
        'description',
        'quote_id',
    ];

    /**
     * Get the quote of the WorkOrder
     */
    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }
}
