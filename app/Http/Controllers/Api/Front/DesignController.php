<?php

namespace App\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Models\SaveDesign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DesignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rule=[];
        $message=[];
        if (@$request->user_id == null && $request->session_id == null) {
            $rule['session_id']='required';
            $message['session_id.required'] = __('Session id required.');
        }

        $validator = Validator::make($request->all(), $rule, $message);
        if ($validator->fails()) {
            return response()->json([
                'status' => false, 'data' => [], 'error' => $validator->errors()], 400);
        }
        $result=SaveDesign::query();
        if (@$request->user_id) {
            $result=$result->where('user_id', $request->user_id);
        }

        if (@$request->session_id) {
            $result=$result->where('session_id', $request->session_id);
        }

        $result=$result->orderBy('created_at', 'desc')
                        ->get();

        return response()->json([
            'status' => true,
            'data' => $result,
            'message'       =>__('Design fetched successfully.')
        ], 200);
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
        $rule['name']        ='required';
        $rule['design']      ='required';
        $rule['isOverwrite'] ='required';

        $message=[];
        if (@$request->user_id == null && $request->session_id == null) {
            $rule['session_id']='required';
            $message['session_id.required'] = __('Session id required.');
        }

        $validator = Validator::make($request->all(), $rule, $message);
        if ($validator->fails()) {
            return response()->json([
                'status' => false, 'data' => [], 'error' => $validator->errors()], 400);
        }

        $isNew=1;
        if ($request->isOverwrite == 0) {
            
            $isExist=SaveDesign::where('name', $request->name)
                            ->where('user_id', $request->user_id)
                            ->exists();
            if($isExist) {
                return response()->json([
                    'status' => 201,
                    'message' => __("This design name already exists. Do you want to overwrite it?")
                ], 201);
            }

        } elseif ($request->isOverwrite == 1) {
            $saveDesign=SaveDesign::where('name', $request->name)
                                    ->where('user_id', $request->user_id)
                                    ->first();
            if ($saveDesign) {
                $isNew=0;
            }
        }

        if ($isNew) {
            $saveDesign=new SaveDesign();
        }
        
        $saveDesign->name=$request->name;
        $saveDesign->design=json_encode($request->design);
        $saveDesign->session_id=$request->session_id;
        $saveDesign->user_id=$request->user_id;
        $saveDesign->raw_design=json_encode(array_merge($request->finalDesignedData , ['svg_data' => $request->get('svg-data')]));
        $saveDesign->save();
        $this->convertBase64ToSVG($request->get('svg-data'),$request->name.".svg");

        return response()->json([
            'status'        => true,
            'data'          => $saveDesign,
            'message'       =>__('Design created successfully.')
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result=SaveDesign::find($id);
        return response()->json([
            'status'        => true,
            'data'          => $result,
            'message'       =>__('Design fetched successfully.')
        ], 200);
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

        $rule['name']   ='required';
        $rule['design'] ='required';

        $message=[];
        if (@$request->user_id == null && $request->session_id == null) {
            $rule['session_id']='required';
            $message['session_id.required'] = __('Session id required.');
        }

        $validator = Validator::make($request->all(), $rule, $message);
        if ($validator->fails()) {
            return response()->json([
                'status' => false, 'data' => [], 'error' => $validator->errors()], 400);
        }

        $saveDesign=SaveDesign::find($id);
        $saveDesign->name=$request->name;
        $saveDesign->design=$request->design;
        $saveDesign->session_id=$request->session_id;
        if($request->user_id) {
            $saveDesign->user_id=$request->user_id;
        }
       
        $saveDesign->save();

        return response()->json([
            'status'        => true,
            'data'          => $saveDesign,
            'message'       =>__('Design updated successfully.')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SaveDesign::find($id)->delete();
        return response()->json([
            'status'        => true,
            'data'          => [],
            'message'       =>__('Design deleted successfully.')
        ], 200);
    }

    public function convertBase64ToSVG($base64Data,$fileName)
    {
        $base64Data = preg_replace('/^data:image\/svg\+xml;base64,/', '', $base64Data);
        $svgData = base64_decode($base64Data);
        Storage::disk('public')->put($fileName, $svgData);
        return $fileName;
    }
}
