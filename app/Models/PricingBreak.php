<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingBreak extends Model
{
    protected $table = 'pricing_breaks';
    protected $fillable = ['product_id','quantity','color_1','color_2','color_3','color_4'];
}