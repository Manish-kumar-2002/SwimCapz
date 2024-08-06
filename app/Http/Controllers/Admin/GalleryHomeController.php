<?php

namespace App\Http\Controllers\Admin;
use App\Models\GalleryHome;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Datatables;
use Validator;
use Str;
use Image;
use File;

class GalleryHomeController extends AdminBaseController
{
    //*** JSON Request
    public function datatables()
    {
        $datas = GalleryHome::latest('id')->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->editColumn('image', function (GalleryHome $data)
        {
            $url = asset("assets/galleryhome/".$data->image);
           return '<img src="'.$url.'">';
        })
        ->editColumn('logo', function (GalleryHome $data)
        {
            $url = asset("assets/galleryhome/".$data->logo);
           return '<img src="'.$url.'">';
        })
         ->addColumn('status', function (GalleryHome $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks ' . $class . '"><option data-val="1" value="' . route('admin-gallery-status', ['id1' => $data->id, 'id2' => 1]) . '" ' . $s . '>' . __("Active") . '</option><<option data-val="0" value="' . route('admin-gallery-status', ['id1' => $data->id, 'id2' => 0]) . '" ' . $ns . '>' . __("Inactive") . '</option>/select></div>';
            })
            ->addColumn('action', function (GalleryHome $data) {
                return '<div class="action-list"><a href="' . route('admin-gallery-edit', $data->id) . '"> <i class="fas fa-edit"></i></a><a href="javascript:;" data-href="' . route('admin-gallery-home-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->rawColumns(['logo','status','image','action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index()
    {
        return view('admin.galleryhome.index');
    }


    //*** GET Request
    public function create()
    {
        return view('admin.galleryhome.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Adjust the allowed image types and size limit as needed
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $image = '';
        $logo = '';
        if ($request->hasFile('image')) {
            $timestamp = Carbon::now()->timestamp; // Get the current timestamp
            $randomImageName = Str::random(20); // You can adjust the length as needed
    
            // Get the file extension from the uploaded image
            $imageExtension = $request->file('image')->getClientOriginalExtension();
    
            // Combine the random name, timestamp, and the original extension to create a new file name
            $newImageName = $timestamp . '_' . $randomImageName . '.' . $imageExtension;
            $request->file('image')->move(public_path().'/assets/galleryhome',$newImageName);
            $image = $newImageName;

        }
        
        //logo
        if ($request->hasFile('logo')) {
            $timestamplogo = Carbon::now()->timestamp; // Get the current timestamp
            $randomImageNamelogo = Str::random(20); // You can adjust the length as needed
    
            // Get the file extension from the uploaded image
            $imageExtensionlogo = $request->file('logo')->getClientOriginalExtension();
    
            // Combine the random name, timestamp, and the original extension to create a new file name
            $newImageNamelogo = $timestamplogo . '_' . $randomImageNamelogo . '.' . $imageExtensionlogo;
            $request->file('logo')->move(public_path().'/assets/galleryhome',$newImageNamelogo);
            $logo = $newImageNamelogo;

        }

        // Create a new FeaturedOn model instance
        $galleryhome = new GalleryHome;
        $galleryhome->title = $request->input('title');
        $galleryhome->logo = $logo;
        $galleryhome->status = $request->status; // Store the image path in the database
        $galleryhome->image = $image;
        // Save the model to the database
        $galleryhome->save();
        $msg = __('New Data Added Successfully.') . '<a href="' . route("admin-gallery-index") . '">' . __("View Gallery Lists") . '</a>';
        return response()->json($msg);
    }

    //*** GET Request
    public function edit($id)
    {
        $data = GalleryHome::findOrFail($id);
        return view('admin.galleryhome.edit', compact('data'));
    }


    //*** POST Request
    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048', // Adjust the allowed image types and size limit as needed
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $galleryhome = GalleryHome::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($galleryhome->image) {
                $oldImagePath = public_path().'/assets/galleryhome/'.$galleryhome->image;
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
            $request->file('image')->move(public_path().'/assets/galleryhome',$newImageName);
            $galleryhome->image = $newImageName;

        }
        
        //logo
        if ($request->hasFile('logo')) {
            if ($galleryhome->logo) {
                $oldLogoPath = public_path().'/assets/galleryhome/'.$galleryhome->logo;
                if (File::exists($oldLogoPath)) {
                    File::delete($oldLogoPath);
                }
            }
            $timestamplogo = Carbon::now()->timestamp; // Get the current timestamp
            $randomImageNamelogo = Str::random(20); // You can adjust the length as needed
    
            // Get the file extension from the uploaded image
            $imageExtensionlogo = $request->file('logo')->getClientOriginalExtension();
    
            // Combine the random name, timestamp, and the original extension to create a new file name
            $newImageNamelogo = $timestamplogo . '_' . $randomImageNamelogo . '.' . $imageExtensionlogo;
            $request->file('logo')->move(public_path().'/assets/galleryhome',$newImageNamelogo);
            $galleryhome->logo = $newImageNamelogo;

        }
        // Create a new FeaturedOn model instance
        $galleryhome->title = $request->input('title');
        // Save the model to the database
        $galleryhome->save();
        $msg = __('Data Updated Successfully.') . '<a href="' . route("admin-gallery-index") . '">' . __("View Gallery lists") . '</a>';
        return response()->json($msg);
    }

    //*** GET Request Status
    public function status($id1, $id2)
    {
        $data = GalleryHome::findOrFail($id1);
        $data->status = $id2;
        $data->update();
        $msg = __('Status Updated Successfully.');
        return response()->json($msg);
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = GalleryHome::findOrFail($id);
        if ($data->image) {
            $oldImagePath = public_path().'/assets/galleryhome/'.$data->image;
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
        }
        if ($data->logo) {
            $oldLogoPath = public_path().'/assets/galleryhome/'.$data->logo;
            if (File::exists($oldLogoPath)) {
                File::delete($oldLogoPath);
            }
        }
        $data->delete();
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
    }
}
