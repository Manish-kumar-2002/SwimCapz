<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetailsDesc extends Model
{
    use HasFactory;

    protected $fillable=[
        'desc_id',
        'product_variant_id',
        'front_image',
        'front_image_overlay',
        'back_image',
        'back_image_overlay',
        'front_design',
        'back_design',
        'total_qty',
        'total_price',
        'remarks',
        "raw_design"
    ];

    protected $appends=[
        'front_image_url',
        'back_image_url',
        'front_image_overlay_url',
        'back_image_overlay_url',
        'front_design_url',
        'back_design_url'
    ];
    public function varient()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    public function getFrontImageUrlAttribute()
    {
        if ($this->front_image) {
            return asset('assets/product/front/' .$this->front_image);
        }else {
            return null;
        }
    }

    public function getBackImageUrlAttribute()
    {
        if ($this->back_image) {
            return asset('assets/product/back/' .$this->back_image);
        }else {
            return null;
        }
    }

    public function getFrontImageOverlayUrlAttribute()
    {
        if ($this->front_image_overlay) {
            return asset('assets/product/front/' .$this->front_image_overlay);
        }else {
            return null;
        }
    }
   
    public function getBackImageOverlayUrlAttribute()
    {
        if ($this->back_image_overlay) {
            return asset('assets/product/back/' .$this->back_image_overlay);
        }else {
            return null;
        }
    }

    public function getFrontDesignUrlAttribute() {

        if ($this->front_design) {
            return asset('storage/ModifiedProduct/front/' .$this->front_design);
        }else {
            return null;
        }
            
    }

    public function getBackDesignUrlAttribute() {

        if ($this->back_image) {
            return asset('assets/ModifiedProduct/back/' .$this->back_design);
        }else {
            return null;
        }  
    }

    public function getRawDesignAttribute($value)
    {
        return json_decode($value, true);
    }
}
