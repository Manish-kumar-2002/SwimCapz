<?php
namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id' ,
        'session_id',
        'product_id',
        'design_name',
        'noc',
        'front_design',
        'noc_back',
        'back_design',
        'names',
        'name_cost',
        'raw_design'
    ];

    protected $appends=[
        'front_design_url',
        'back_design_url',
    ];
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
    
    public function details()
    {
        return $this->hasMany(CartDetails::class, 'cart_id', 'id');
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

    public function getNamesAttribute($value) {

        if ($value) {
            return json_decode($value, true);
        }

        return $value;
            
    }

    public function getTotalNames()
    {
        if ($this->names == null) {
            return 0;
        }
        return array_sum(array_column($this->names, 'quantity'));
    }

    public function totalNameCharge($isCurrency = true)
    {
        $fixCostPerName= Helper::getNameFixCost();

        $totalCharge=0;
        if ($this->names && count($this->names) > 0) {
            $totalName=array_sum(array_column($this->names, 'quantity'));
            $totalCharge=Helper::calculateTotalNameCost($totalName, $fixCostPerName);
        }
        
        if($isCurrency) {
            return Helper::convertPrice($totalCharge);
        }

        return $totalCharge;
    }

    public function nameChargeEach($isCurrency = true)
    {
        $fixCostPerName= Helper::getNameFixCost();


        if($isCurrency) {
            return Helper::convertPrice($fixCostPerName);
        }

        return $fixCostPerName;
    }

    public function saveRawDesign()
    {
        $result=DB::table('temp_page_details')
                        ->where('user_id', $this->user_id)
                        ->first();

        $this->update([
            'raw_design' => $result->result
        ]);
    }

    public function getRawDesignAttribute($value)
    {
        return json_decode($value, true);
    }

    public function deleteCart()
    {
        
        $totalProduct=CartDetails::where('cart_id', $this->id)
                                    ->count();
        if($totalProduct >= 1) {
            CartDetails::where('cart_id', $this->id)
                    ->delete();
        }

        $this->delete();
    }
}
