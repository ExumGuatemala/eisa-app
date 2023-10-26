<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class WorkOrder extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    
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

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }
}
