<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'start_date',
        'deadline',
        'description',
        'quote_id',
    ];
}
