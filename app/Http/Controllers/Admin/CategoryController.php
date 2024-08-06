<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PriceHelper;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CategoryController extends AdminBaseController
{
    protected $styleArray;
    public function __construct()
    {
        $this->styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'black'),
                ),
            ),
        );
    }


    public function datatables()
    {
        $datas = Category::latest('id')->get();
        return DataTables::of($datas)
            ->addColumn('status', function (Category $data) {
                $selected_class=$data->status == 1 ? 'fa-toggle-on' : 'fa-toggle-off';
                $url=route('admin-cat-status', ['id1' => $data->id]);
                return '<label data-confirm="1" data-url="'.$url.'" class="toggle-checkbox form-check-label" for="toggleSwitch">
                    <i id="toggleIcon" class="fas '.$selected_class.'"></i>
                </label>';
            })
            ->editColumn('is_featured', function (Category $data) {
                $selected_class=$data->is_featured == 1 ? 'fa-toggle-on' : 'fa-toggle-off';
                $url=route('admin-cat-featured', ['id1' => $data->id]);
                return '<label data-url="'.$url.'" class="toggle-checkbox form-check-label" for="toggleSwitch">
                    <i id="toggleIcon" class="fas '.$selected_class.'"></i>
                </label>';
            })
            ->addColumn('action', function (Category $data) {
                return '<div
                            class="action-list"
                        ><a
                            data-href="' . route('admin-cat-edit', $data->id) . '"
                            class="edit"
                            data-toggle="modal"
                            data-target="#modal1"
                        > <i class="fas fa-edit"></i></a>
                        <a
                            href="javascript:;"
                            data-href="' . route('admin-cat-delete', $data->id) . '"
                            data-toggle="modal"
                            data-target="#confirm-delete"
                            class="delete"
                        ><i class="fas fa-trash-alt"></i></a>
                    </div>';
            })
            ->rawColumns(['status', 'attributes', 'action','is_featured'])
            ->toJson();
    }

    public function index()
    {
        return view('admin.category.index');
    }


    public function download()
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Categories');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);
        
        $tr=1;

        $sheet->setCellValue("A$tr", 'Category');
        $sheet->setCellValue("B$tr", 'Slug');
        $tr +=1;

        $datas = Category::latest('id')->get();
        foreach($datas as $row) {
            $sheet->setCellValue("A$tr", $row->name);
            $sheet->setCellValue("B$tr", $row->slug);

            $tr +=1;
        }

        $sheet = $sheet ->getStyle('A1:B'.$tr)->applyFromArray($this->styleArray);

        $writer = new Xlsx($spreadsheet);
        $file='category-'.time().'.xlsx';
        $writer->save($file);

        return response()
                        ->download(public_path($file))
                        ->deleteFileAfterSend();
    }

    public function create()
    {
        return view('admin.category.create');
    }
    
    public function store(Request $request)
    {
        $rules = [
            
            'slug' => 'unique:categories|regex:/^[a-zA-Z0-9\s-]+$/',
            'image' => 'mimes:jpeg,jpg,png,svg'
        ];
        $customs = [
            
            'slug.unique' => __('This slug has already been taken.'),
            'slug.regex' => __('Slug Must Not Have Any Special Characters.'),
            'image.mimes' => __('Banner Image Type is Invalid.')
        ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Category();
        $input = $request->all();
        if ($file = $request->file('photo')) {
            $name = \PriceHelper::ImageCreateName($file);
            $file->move('assets/images/categories', $name);
            $input['photo'] = $name;
        }
        if ($file = $request->file('image')) {
            $name = \PriceHelper::ImageCreateName($file);
            $file->move('assets/images/categories', $name);
            $input['image'] = $name;
        }

        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = __('New Category Added Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function edit($id)
    {
        $data = Category::findOrFail($id);
        return view('admin.category.edit', compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        $rules = [
            'name' =>'required',
            'slug' => 'unique:categories,slug,' . $id . '|regex:/^[a-zA-Z0-9\s-]+$/',
            'image' => 'mimes:jpeg,jpg,png,svg'
        ];

        $customs = [
            
            'slug.unique' => __('This slug has already been taken.'),
            'slug.regex' => __('Slug Must Not Have Any Special Characters.'),
            'image.mimes' => __('Banner Image Type is Invalid.')
        ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        
        $data = Category::findOrFail($id);
        $input = $request->all();
        if ($file = $request->file('photo')) {
            $name = PriceHelper::ImageCreateName($file);
            $file->move('assets/images/categories', $name);
            if ($data->photo != null) {
                if (file_exists(public_path() . '/assets/images/categories/' . $data->photo)) {
                    unlink(public_path() . '/assets/images/categories/' . $data->photo);
                }
            }
            $input['photo'] = $name;
        }
        if ($file = $request->file('image')) {
            $name = PriceHelper::ImageCreateName($file);
            $file->move('assets/images/categories', $name);
            $input['image'] = $name;
        }


        $data->update($input);
        
        $msg = __('Category Updated Successfully.');
        return response()->json($msg);
    }

    //*** GET Request Status
    public function status($id1, $id2=null)
    {
        $data = Category::findOrFail($id1);
        $data->status = !$data->status;
        $data->update();
        //--- Redirect Section
        $msg = __('Category status Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request Status
    public function featured($id1, $id2=null)
    {
        $data = Category::findOrFail($id1);
        $data->is_featured  = !$data->is_featured;
        $data->update();
        //--- Redirect Section
        $msg = __('Category featured status Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }


    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Category::findOrFail($id);

        if ($data->products->count() > 0) {
            $msg = __('Remove the products first !');
            return response()->json(array('status' => 401, 'message' => $msg), 400);
        }

        if ($data->image && file_exists(public_path() . '/assets/images/categories/' . $data->image)) {
            unlink(public_path() . '/assets/images/categories/' . $data->image);
        }

        $data->delete();
    
        $msg = __('Category Deleted Successfully.');
        return response()->json($msg);
    }
}