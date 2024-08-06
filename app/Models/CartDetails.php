<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetails extends Model
{
    use HasFactory;
    protected $fillable=[
        "product_variant_id",
        "total_qty",
        "total_price",
        "remarks",
        "front_design",
        "back_design"
    ];

    protected $appends=[
        'front_design_url',
        'back_design_url',
    ];
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }


    public function productVarients()
    {
        return $this->hasOne(ProductVariant::class, 'id', 'product_variant_id');
    }

    public function deleteCart()
    {
        
        $totalProduct=CartDetails::where('cart_id', $this->cart_id)
                                    ->count();
        if($totalProduct >= 1) {
            Cart::where('id', $this->cart_id)
                    ->delete();
        }

        $this->delete();
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
}
