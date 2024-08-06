<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceStructure extends Model
{
    use HasFactory;
    protected $fillable=["noc", "nob","fixScreenCost", "fixCostPerName", "fixFrontBackPerColor"];
    public function breakPoint()
    {
        return $this->hasMany(PriceStructureProductQty::class , 'price_structure_id' , 'id');
    }

    public function setupCharge($color =null, $category_id=null)
    {
        $result =$this->hasMany(PriceStructureSetupCharge::class , 'price_structure_id' , 'id');
        if($color) {
            $result =$result ->where('color_no' ,$color);
        }

        if($category_id) {
            $result =$result ->where('category_id' ,$category_id);
        }

        return $result;
    }

    public function printCharge($color =null, $category_id=null)
    {
        $result =$this->hasMany(PriceStructurePrintCharge::class , 'price_structure_id' , 'id');
        if($color) {
            $result =$result ->where('color_no' ,$color);
        }

        if($category_id) {
            $result =$result ->where('category_id' ,$category_id);
        }

        return $result;
    }


    public function store($request) {
        if($request->has('break_count')) {
            $arr=[];
            foreach($request->break_count as $key => $break) {
                $result=$this->breakPoint()
                    ->updateOrCreate([
                        'break_no' => $key,
                    ],[
                        'break_qty' => $break
                    ]);

                array_push($arr, $result->id);
            }

            $this->breakPoint()
                ->whereNotIn('id', $arr)
                ->delete();
        }

        if ($request->has('fixScreenCost')) {
            $this->update([
                'fixScreenCost'         =>$request->fixScreenCost,
                'fixCostPerName'        =>$request->fixCostPerName,
                'fixFrontBackPerColor'  =>$request->fixFrontBackPerColor
            ]);
        }
        
    }

    public function storeSetupCharge($request) {
        
        if($request->has('setupCharge')) {
            $arr=[];
            foreach($request->setupCharge as $color => $breaks) {
                
                foreach ($breaks as $key => $break) {
                    $result=$this->setupCharge()
                        ->updateOrCreate([
                            'break_no' => $key,
                            'color_no' => $color,
                            'category_id'=>$request->category_id
                        ],[
                            'break_price' => $break
                        ]);

                    array_push($arr, $result->id);
                }

                $this->setupCharge()
                        ->whereNotIn('id', $arr)
                        ->where('color_no', $color)
                        ->where('category_id', $request->category_id)
                        ->delete();
            }
        }
    }

    public function storePrintCharge($request) {
        if($request->has('printCharge')) {
            $arr=[];
            foreach($request->printCharge as $color => $breaks) {
                foreach ($breaks as $key => $break) {
                    $result=$this->printCharge()
                        ->updateOrCreate([
                            'break_no' => $key,
                            'color_no' => $color,
                            'category_id'=>$request->category_id
                        ],[
                            'break_price' => $break
                        ]);

                    array_push($arr, $result->id);
                }

                $this->printCharge()
                        ->whereNotIn('id', $arr)
                        ->where('color_no', $color)
                        ->where('category_id', $request->category_id)
                        ->delete();
            }
        }
    }

    public function removeRelatedData()
    {
        /* break point delete */
        $this->breakPoint()
                        ->where('break_no' , '>', $this->nob)
                        ->delete();

        /* setup charge delete */
        $this->setupCharge()
                        ->where('break_no' , '>', $this->nob)
                        ->delete();

        /* setup charge delete */
        $this->printCharge()
                        ->where('break_no' , '>', $this->nob)
                        ->delete();
    }
}
