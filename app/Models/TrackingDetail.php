<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingDetail extends Model
{
    use HasFactory;
    protected $table = "trackingDetails";
    protected $fillable = ['courier','location','checkpointTime','country','order_id'];

    public function order(){
        return $this->hasOne(Order::class,'courierId','order_id');
    }
}
