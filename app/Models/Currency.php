<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ShippingTrait;
class Currency extends Model
{
    use ShippingTrait;
    protected $fillable = ['name', 'sign', 'value', 'icon'];
    public $timestamps = false;
}
