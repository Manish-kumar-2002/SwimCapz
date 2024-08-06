<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceStructurePrintCharge extends Model
{
    use HasFactory;
    protected $fillable=[
        'price_structure_id' , 'color_no' , 'category_id', 'break_no', 'break_price'
    ];
}
