<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantImage extends Model
{
    protected $table = 'product_variant_images';
    protected $fillable = ['product_id','variant_id','images'];
}
