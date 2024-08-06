<?php

namespace App\Http\Controllers\Front;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if(!Auth::check()) {
        //     Helper::setBackHistoryUrl(route('tool.details').'?product-id='. $request->input('product-id'));
        //     return redirect()
        //                 ->route('user.login')
        //                     ->with('error', __('Please login to proceed.'));
        // }
        
        if($request->has('details-page')) {
            return $this->showDetailPage($request);
        }

        return view('frontend.tools.index', [
            'product_id' => $request->input('product-id') ?? null,
            'user_id'    =>Auth::user()->id ?? '',
            'color_collections'=>$this->getColors()
        ]);
    }

    public function showDetailPage($request)
    {
        return view('frontend.tools.designDetails');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function getColors()
    {
        return Cache::remember('color_collections', 120, function () {
            $colors=Color::select('code', 'name')->get();
            return $colors->map(function($color) {
                return [$color->code, $color->name];
            })->toArray();
        });
    }
}
