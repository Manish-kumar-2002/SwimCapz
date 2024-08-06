<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends AdminBaseController
{
    
    public function datatables()
    {
        $datas = BlogCategory::latest('id')->get();
        return DataTables::of($datas)
            ->addColumn('action', function (BlogCategory $data) {
                return '<div class="action-list">
                    <a
                        data-href="' . route('admin-cblog-edit', $data->id) . '"
                        class="edit"
                        data-toggle="modal"
                        data-target="#modal1"
                    > <i class="fas fa-edit"></i></a>
                    <a
                        href="javascript:;"
                        data-href="' . route('admin-cblog-delete', $data->id) . '"
                        data-toggle="modal"
                        data-target="#confirm-delete"
                        class="delete"
                    ><i class="fas fa-trash-alt"></i></a>
                </div>';
            })
            ->toJson();
    }

    public function index()
    {
        return view('admin.blog.category.index');
    }

    public function create()
    {
        return view('admin.blog.category.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:blog_categories',
            'slug' => 'required|unique:blog_categories'
        ];
        $customs = [
            'name.unique' => __('This name has already been taken.'),
            'slug.unique' => __('This slug has already been taken.')
        ];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
       
        $data = new BlogCategory;
        $input = $request->all();
        $data->fill($input)->save();
        
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);
        
    }

    public function edit($id)
    {
        $data = BlogCategory::findOrFail($id);
        return view('admin.blog.category.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|unique:blog_categories,name,' . $id,
            'slug' => 'required|unique:blog_categories,slug,' . $id
        ];
        $customs = [
            'name.unique' => __('This name has already been taken.'),
            'slug.unique' => __('This slug has already been taken.')
        ];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
       
        $data = BlogCategory::findOrFail($id);
        $input = $request->all();
        $data->update($input);
       
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
    
    }

    public function destroy($id)
    {
        $data = BlogCategory::findOrFail($id);

        if ($data->blogs->count() > 0) {
            foreach ($data->blogs as $element) {
                $element->delete();
            }
        }
        $data->delete();
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
    }
}
