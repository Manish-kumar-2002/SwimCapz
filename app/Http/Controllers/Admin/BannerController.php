<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PriceHelper;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends AdminBaseController
{

    public function datatables($type)
    {
        $datas = Banner::where('type', '=', $type)->latest('id')->get();
        return DataTables::of($datas)
            ->editColumn('photo', function (Banner $data) {
                $photo = $data->photo ? url('assets/images/banners/' . $data->photo) : url('assets/images/noimage.png');
                return '<img src="' . $photo . '" alt="Image">';
            })
            ->addColumn('action', function (Banner $data) {
                return '<div
                            class="action-list"
                        >
                            <a
                                data-href="' . route('admin-sb-edit', $data->id) . '"
                                class="edit"
                                data-toggle="modal"
                                data-target="#modal1"
                            > <i class="fas fa-edit"></i>' . __('Edit') . '</a>
                            <a
                                href="javascript:;"
                                data-href="' . route('admin-sb-delete', $data->id) . '"
                                data-toggle="modal"
                                data-target="#confirm-delete"
                                class="delete"
                            ><i class="fas fa-trash-alt"></i></a>
                        </div>';
            })
            ->rawColumns(['photo', 'action'])
            ->toJson();
    }

    public function index()
    {
        return view('admin.banner.index');
    }

    public function large()
    {
        return view('admin.banner.large');
    }

    public function bottom()
    {
        return view('admin.banner.bottom');
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function largecreate()
    {
        return view('admin.banner.largecreate');
    }

    public function bottomcreate()
    {
        return view('admin.banner.bottomcreate');
    }

    public function store(Request $request)
    {
        
        $rules = [
            'photo'      => 'required|mimes:jpeg,jpg,png,svg',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        
        $data = new Banner();
        $input = $request->all();
        if ($file = $request->file('photo')) {
            $name = PriceHelper::ImageCreateName($file);
            $file->move('assets/images/banners', $name);
            $input['photo'] = $name;
        }
        $data->fill($input)->save();
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data = Banner::findOrFail($id);
        return view('admin.banner.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        
        $rules = [
            'photo'      => 'mimes:jpeg,jpg,png,svg',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        
        $data = Banner::findOrFail($id);
        $input = $request->all();
        if ($file = $request->file('photo')) {
            $name = PriceHelper::ImageCreateName($file);
            $file->move('assets/images/banners', $name);
            if ($data->photo != null) {
                if (file_exists(public_path() . '/assets/images/banners/' . $data->photo)) {
                    unlink(public_path() . '/assets/images/banners/' . $data->photo);
                }
            }
            $input['photo'] = $name;
        }
        $data->update($input);
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
    }
 
    public function destroy($id)
    {
        $data = Banner::findOrFail($id);
        if ($data->photo == null) {
            $data->delete();
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
        }
       
        if (file_exists(public_path() . '/assets/images/banners/' . $data->photo)) {
            unlink(public_path() . '/assets/images/banners/' . $data->photo);
        }
        $data->delete();
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
    }
}
