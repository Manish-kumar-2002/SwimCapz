<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PriceStructure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PriceStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.priceStructure.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        /* Update or create break no */
        if($request->has('updateBreakNo')) {
            return $this->updateBreakNo($request);
        }

        /* store product Qty */
        $this->storeProductQty($request);
        
        /* store print charge */
        $this->storePrintCharge($request);
        
        return response()
                        ->json(array('message' => __('Save successfully.')), 200);
    }


    private function updateBreakNo($request)
    {
        $request->validate([
            'max_break' => 'required'
        ]);
        $priceStruct=PriceStructure::first();
        $priceStruct->nob=$request->max_break;
        $priceStruct->save();
        $priceStruct->removeRelatedData();
        
        Artisan::call('cache:clear');
        return response()
                        ->json(array('message' => __('Save successfully.')), 200);
    }
    /**
     * This function is used to store break qty and validation
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    private function storeProductQty($request)
    {
        $isOk=1;
        if($request->has('break_count')) {
            foreach($request->break_count as $break) {
                if(!$break) {
                    $isOk=0;
                    break;
                }
            }
        }
       
        if(!$isOk) {
            $request->validate([
                'break_fields' => 'required'
            ],[
                'break_fields.required' => 'All break fields are required.'
            ]);
        }
       
        $priceStruct=Helper::priceStructure();
        $priceStruct->store($request);

        return true;
    }

    /**
     * This function is used to store break qty and validation
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    private function storeSetupCharge($request)
    {

        $priceStruct=Helper::priceStructure();
        $priceStruct->storeSetupCharge($request);

    }

    /**
     * This function is used to store break qty and validation
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    private function storePrintCharge($request)
    {
       
        $priceStruct=Helper::priceStructure();
        $priceStruct->storePrintCharge($request);

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category=Category::find($id);
        return view('admin.priceStructure.show', [
            'category'              => $category,
            'storePriceStructure'   => $this->getStorePriceStructure(),
            'setupCharge'           => $this->getSetupCharge($category->id),
            'printCharge'           => $this->getPrintCharge($category->id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function getStorePriceStructure()
    {
        $priceStruct=Helper::priceStructure();
        return $priceStruct
                    ->breakPoint()
                    ->pluck('break_qty', 'break_no')
                    ->toArray();
    }

    private function getSetupCharge($category_id)
    {
        $arr=[];
        $priceStruct=Helper::priceStructure();
        for ($color =1 ; $color <= Helper::totalColor() ; $color++) {
            $arr[$color] =$priceStruct
                                    ->setupCharge($color, $category_id)
                                    ->pluck('break_price', 'break_no')
                                    ->toArray();
        }

        return $arr;
    }

    private function getPrintCharge($category_id)
    {
        $arr=[];
        $priceStruct=Helper::priceStructure();
        for ($color =1 ; $color <= Helper::totalColor() ; $color++) {
            $arr[$color] =$priceStruct
                                    ->printCharge($color, $category_id)
                                    ->pluck('break_price', 'break_no')
                                    ->toArray();
        }

        return $arr;
    }
}
