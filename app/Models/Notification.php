<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $fillable = ['order_id','user_id','vendor_id','product_id','conversation_id'];

    public function order()
    {
    	return $this->belongsTo('App\Models\Order')->withDefault();
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User')->withDefault();
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\User','vendor_id')->withDefault();
    }

    public function product()
    {
    	return $this->belongsTo('App\Models\Product')->withDefault();
    }

    public function productVarient()
    {
    	return $this->belongsTo(ProductVariant::class, 'product_varient', 'id');
    }

    public function conversation()
    {
        return $this->belongsTo('App\Models\Conversation')->withDefault();
    }

    public static function countRegistration()
    {
        return Notification::where('user_id','!=',null)->where('is_read','=',0)->latest('id')->get()->count();
    }

    public static function countOrder()
    {
        return Notification::where('order_id','!=',null)->where('is_read','=',0)->latest('id')->get()->count();
    }

    public static function countProduct()
    {
        return Notification::where('product_id','!=',null)->where('is_read','=',0)->latest('id')->get()->count();
    }

    public static function countConversation()
    {
        $product_count = Notification::where('product_id','!=',null)->where('is_read','=',0)->latest('id')->get()->count();
        $order_count = Notification::where('order_id','!=',null)->where('is_read','=',0)->latest('id')->get()->count();
        $user_count = Notification::where('user_id','!=',null)->where('is_read','=',0)->latest('id')->get()->count();
        $concersation_count = Notification::where('conversation_id','!=',null)->where('is_read','=',0)->latest('id')->get()->count();
        return $product_count + $order_count + $user_count + $concersation_count ;
    }

}
