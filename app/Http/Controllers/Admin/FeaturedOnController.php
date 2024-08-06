<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Childcategory;
use App\Models\Coupon;
use App\Models\Subcategory;
use App\Models\ProductType;
use App\Models\FeaturedOn;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Datatables;
use Validator;
use Str;
use Image;
use File;

class FeaturedOnController extends AdminBaseController
{
    //*** JSON Request
    public function datatables()
    {
        $datas = FeaturedOn::latest('id')->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->editColumn('image', function (FeaturedOn $data)
        {
            $url = asset("assets/featuredon/".$data->image);
           return '<a href="'.$data->link.'" target="_blank"><img src="'.$url.'"></a>';
        })
         ->addColumn('status', function (FeaturedOn $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks ' . $class . '"><option data-val="1" value="' . route('admin-featured-status', ['id1' => $data->id, 'id2' => 1]) . '" ' . $s . '>' . __("Active") . '</option><<option data-val="0" value="' . route('admin-featured-status', ['id1' => $data->id, 'id2' => 0]) . '" ' . $ns . '>' . __("inactive") . '</option>/select></div>';
            })
            ->addColumn('action', function (FeaturedOn $data) {
                return '<div class="action-list"><a href="' . route('admin-featured-edit', $data->id) . '"> <i class="fas fa-edit"></i></a><a href="javascript:;" data-href="' . route('admin-featured-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->rawColumns(['status','image','action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index()
    {
        return view('admin.featuredon.index');
    }


    //*** GET Request
    public function create()
    {
        return view('admin.featuredon.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'required|url',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Adjust the allowed image types and size limit as needed
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $image = '';
        if ($request->hasFile('image')) {
            $timestamp = Carbon::now()->timestamp; // Get the current timestamp
            $randomImageName = Str::random(20); // You can adjust the length as needed
    
            // Get the file extension from the uploaded image
            $imageExtension = $request->file('image')->getClientOriginalExtension();
    
            // Combine the random name, timestamp, and the original extension to create a new file name
            $newImageName = $timestamp . '_' . $randomImageName . '.' . $imageExtension;
            $request->file('image')->move(public_path().'/assets/featuredon',$newImageName);
            $image = $newImageName;

        }
        // Create a new FeaturedOn model instance
        $featuredOn = new FeaturedOn;
        $featuredOn->title = $request->input('title');
        $featuredOn->description = $request->input('description');
        $featuredOn->link = $request->input('link');
        $featuredOn->status = $request->status; // Store the image path in the database
        $featuredOn->image = $image;
        $featuredOn->article_date = $request->article_date;

        // Save the model to the database
        $featuredOn->save();
        $msg = __('New Data Added Successfully.') . '<a href="' . route("admin-featured-index") . '">' . __("View Featured Lists") . '</a>';
        return response()->json($msg);
    }

    //*** GET Request
    public function edit($id)
    {
        $data = FeaturedOn::findOrFail($id);
        return view('admin.featuredon.edit', compact('data'));
    }


    //*** POST Request
    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'required|url',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048', // Adjust the allowed image types and size limit as needed
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $featuredOn = FeaturedOn::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($featuredOn->image) {
                $oldImagePath = public_path().'/assets/featuredon/'.$featuredOn->image;
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            $timestamp = Carbon::now()->timestamp; // Get the current timestamp
            $randomImageName = Str::random(20); // You can adjust the length as needed
    
            // Get the file extension from the uploaded image
            $imageExtension = $request->file('image')->getClientOriginalExtension();
    
            // Combine the random name, timestamp, and the original extension to create a new file name
            $newImageName = $timestamp . '_' . $randomImageName . '.' . $imageExtension;
            $request->file('image')->move(public_path().'/assets/featuredon',$newImageName);
            $featuredOn->image =  $newImageName;

        }
        // Create a new FeaturedOn model instance
        $featuredOn->title = $request->input('title');
        $featuredOn->description = $request->input('description');
        $featuredOn->link = $request->input('link');
        $featuredOn->status = $request->status; // Store the image path in the database
        $featuredOn->article_date = $request->article_date;
        // Save the model to the database
        $featuredOn->save();
        $msg = __('Data Updated Successfully.') . '<a href="' . route("admin-featured-index") . '">' . __("View Featured lists") . '</a>';
        return response()->json($msg);
    }

    //*** GET Request Status
    public function status($id1, $id2)
    {
        $data = FeaturedOn::findOrFail($id1);
        $data->status = $id2;
        $data->update();
        $msg = __('Status Updated Successfully.');
        return response()->json($msg);
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = FeaturedOn::findOrFail($id);
        if ($data->image) {
            $oldImagePath = public_path().'/assets/featuredon/'.$data->image;
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
        }
        $data->delete();
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
    }
}
