<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderState extends Model
{
    use HasFactory;

    protected $table = 'workorder_states';

    protected $fillable = [
        'name',
        'order'
    ];
}
