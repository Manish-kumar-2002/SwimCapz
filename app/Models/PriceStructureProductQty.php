<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceStructureProductQty extends Model
{
    use HasFactory;
    protected $fillable=[
        'price_structure_id','break_no','break_qty'
    ];
}
