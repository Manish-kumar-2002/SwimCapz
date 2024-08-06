<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveDesign extends Model
{
    use HasFactory;

    protected $appends=['edit_link'];
    protected $fillable=['name', 'design', 'session_id', 'user_id', 'raw_design'];
    public function getDesignAttribute($value)
    {
        return $this->parseJson($value);
    }

    public function getRawDesignAttribute($val)
    {
        return $this->parseJson($val);
    }

    public function parseJson($value)
    {
        return json_decode($value, true);
    }

    public function getEditLinkAttribute()
    {
        $varient=0;
        $rawDesign=$this->raw_design;
       
        if (@$rawDesign['variant_id']) {
            $aVarient=$rawDesign['variant_id'];
            $varient=$aVarient[0];
        } else {
            $products=Product::with('variant')->first();
            $varient=@$products->variant[0]->id;
        }
       
        return url("frontend/tool?product-id=$varient&isEdit=true&design=".$this->id);
    }

}
