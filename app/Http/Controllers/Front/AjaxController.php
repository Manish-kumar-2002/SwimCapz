<?php

namespace App\Http\Controllers\Front;


use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\Category;
use App\Models\FeaturedOn;
use App\Models\Product;

class AjaxController extends Controller
{
    public function getCountries(Request $request) {
        return Country::select('id', 'country_name as text')
        ->where(function ($query) use ($request) {
            $query->where('country_name', 'like', "$request->search%");
        })->where('status', 1)
        ->get();

    }

    public function getProducts(Request $request)
    {
        return Product::select('id', 'name as text')
        ->where(function ($query) use ($request) {
            $query->where('name', 'like', "$request->search%");
        })->where('status', 1)
        ->get();
    }

    public function getFeaturedOn(Request $request)
    {
        return FeaturedOn::select('id', 'title as text')
        ->where(function ($query) use ($request) {
            $query->where('title', 'like', "$request->search%");
        })->where('status', 1)
        ->get();
    }


    public function getStates(Request $request)
    {

        return State::select('id', 'state as text')
        ->where(function ($query) use ($request) {
            $query->where('state', 'like', "$request->search%");
        })->where(['status'=> 1, 'country_id'=>$request->country])
        ->get();
        
    }

    public function getStatesByCountryId($id, Request $request)
    {
        return view('admin.ajax.states', [
            'states'    =>Helper::getStates($id),
            'selected'  =>$request->selected ?? null
        ]);
    }

    public function getProductByCategoryId($id, Request $request)
    {
        $category=Category::find($id);
        return view('admin.ajax.product', [
            'products'    =>$category->products,
            'selected'    =>$request->selected ?? null
        ]);
    }
}
