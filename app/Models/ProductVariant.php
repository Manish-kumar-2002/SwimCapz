<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    const MINIMUM_STOCK_ALERT=5;
    protected $appends=[
        'front_image_url',
        'back_image_url',
        'front_image_overlay_url',
        'back_image_overlay_url',
        'percentage_discount',
        'product_name',
        'name_charge_each'
    ];
    protected $table = 'product_variants';
    protected $fillable = [
        'product_id',
        'sku',
        'color',
        'color_code',
        'size',
        'price',
        'discount_price',
        'quantity',
        'feature_images',
        'default',
        'minimum_order',
        'front_image',
        'back_image',
        'front_image_overlay',
        'back_image_overlay'
    ];
    
    public function variantImages()
    {
        return $this->hasMany('App\Models\ProductVariantImage', 'variant_id', 'id');
    }

    public function emptyStock()
    {
        $stck = $this->quantity;
        if ($stck == "0") {
            return true;
        }
        return false;
    }

    public function getFrontImageUrlAttribute() {

        if ($this->front_image) {
            return asset('assets/product/front/' .$this->front_image);
        }else {
            return null;
        }
            
    }

    public function getFrontImageOverlayUrlAttribute() {

        if ($this->front_image_overlay) {
            return asset('assets/product/front/' .$this->front_image_overlay);
        }else {
            return null;
        }
            
    }

    public function getBackImageOverlayUrlAttribute() {

        if ($this->back_image_overlay) {
            return asset('assets/product/back/' .$this->back_image_overlay);
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

    public function getPercentageDiscountAttribute()
    {
        if (!$this->discount_price) {
            return 0;
        }
           
        return round(($this->discount_price * 100 ) / $this->price, 2);
        
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function getProductNameAttribute()
    {
        return @$this->products->name ?? null;
    }

    public function getNameChargeEachAttribute()
    {
        return Helper::getNameFixCost(true);
    }
}
