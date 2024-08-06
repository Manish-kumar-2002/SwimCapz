<?php

namespace App\Http\Controllers\Admin;

use App\Models\Testimonial;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Datatables;
use Validator;
use Str;
use Image;
use File;
use DB;

class TestimonialController extends AdminBaseController
{
    //*** JSON Request
    public function datatables()
    {
        $datas = Testimonial::latest('id')->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->editColumn('image', function (Testimonial $data)
        {
            $url = asset("assets/testimonial/".$data->image);
           return '<a href="'.$url.'" target="_blank"><img src="'.$url.'"></a>';
        })
         ->addColumn('status', function (Testimonial $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks ' . $class . '"><option data-val="1" value="' . route('admin-testimonial-status', ['id1' => $data->id, 'id2' => 1]) . '" ' . $s . '>' . __("Active") . '</option><<option data-val="0" value="' . route('admin-testimonial-status', ['id1' => $data->id, 'id2' => 0]) . '" ' . $ns . '>' . __("Inactive") . '</option>/select></div>';
            })
            ->addColumn('action', function (Testimonial $data) {
                return '<div class="action-list"><a href="' . route('admin-testimonial-edit', $data->id) . '"> <i class="fas fa-edit"></i></a><a href="javascript:;" data-href="' . route('admin-testimonial-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->rawColumns(['status','image','action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index()
    {
        return view('admin.testimonial.index');
    }


    //*** GET Request
    public function create()
    {
        return view('admin.testimonial.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
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
            $request->file('image')->move(public_path().'/assets/testimonial',$newImageName);
            $image = $newImageName;

        }
        // Create a new FeaturedOn model instance
        $testimonial = new Testimonial;
        $testimonial->name = $request->input('name');
        $testimonial->description = $request->input('description');
        $testimonial->status = $request->status; // Store the image path in the database
        $testimonial->image = $image;

        // Save the model to the database
        $testimonial->save();
        $msg = __('New Data Added Successfully.') . '<a href="' . route("admin-testimonial-index") . '">' . __("View Testmonials Lists") . '</a>';
        return response()->json($msg);
    }

    //*** GET Request
    public function edit($id)
    {
        $data = Testimonial::findOrFail($id);
        return view('admin.testimonial.edit', compact('data'));
    }


    //*** POST Request
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048', // Adjust the allowed image types and size limit as needed
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $testimonial = Testimonial::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($testimonial->image) {
                // $oldImagePath = public_path().'/assets/testimonial/'.$featuredOn->image;
                $oldImagePath = public_path().'/assets/testimonial/'.$testimonial->image;
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
            $request->file('image')->move(public_path().'/assets/testimonial',$newImageName);
            $testimonial->image =  $newImageName;
            // $featuredOn->image =  $newImageName;

        }
        // Create a new FeaturedOn model instance
        $testimonial->name = $request->input('name');
        $testimonial->description = $request->input('description');
        $testimonial->status = $request->status; // Store the image path in the database
        // Save the model to the database
        $testimonial->save();
        $msg = __('Data Updated Successfully.') . '<a href="' . route("admin-testimonial-index") . '">' . __("View Testimonial lists") . '</a>';
        return response()->json($msg);
    }

    //*** GET Request Status
    public function status($id1, $id2)
    {
        $data = Testimonial::findOrFail($id1);
        $data->status = $id2;
        $data->update();
        $msg = __('Status Updated Successfully.');
        return response()->json($msg);
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Testimonial::findOrFail($id);
        if ($data->image) {
            $oldImagePath = public_path().'/assets/testimonial/'.$data->image;
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
        }
        $data->delete();
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
    }

    //*** GET Request
    public function editYoutube($id)
    {
        $data =DB::table('youtube_links')->where('id',$id)->first();
        return view('admin.youtube.youtube', compact('data'));
    }
       //*** POST Request
       public function youtubeUpdate(Request $request, $id)
       {
           $rules = [
               'url' => 'required|url'
           ];
           $validator = Validator::make($request->all(), $rules);
           if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
           }
           $data = DB::table('youtube_links')->where('id',$id)->first();
           
           $newData = array(
            'url' => $request->url
           );
           DB::table('youtube_links')->where('id',$id)->update($newData);
           $msg = __('Data Updated Successfully.');
           return response()->json($msg);
       }
          //*** GET Request
    public function editAboutUs($id)
    {
        $data = AboutUs::findOrFail($id);
        return view('admin.aboutus.index', compact('data'));
    }
    //*** POST Request
    public function aboutUsUpdate(Request $request, $id)
    {
        $rules = [
            'title' => 'required',
            'description' => 'required',
        ];
        if($request->input('description') == "<br>"){
            return response()->json(array('errors' => ['description'=> 'The description field is required !']));
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
        return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $data = AboutUs::findOrFail($id);
        $data->title = $request->title;
        $data->description = $request->description;
        $data->save();
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
    }
}
