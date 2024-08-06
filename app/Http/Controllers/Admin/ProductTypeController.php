<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductTypeController extends AdminBaseController
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
        $datas = ProductType::latest('id')->get();
        return DataTables::of($datas)
            ->addColumn('status', function (ProductType $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';
                return '<div class="action-list">
                    <select class="process select droplinks ' . $class . '">
                        <option data-val="1" value="' . route('admin-product-type-status', [
                    'id1' => $data->id, 'id2' => 1
                ]) . '" ' . $s . '>' . __("Active") . '</option>
                        <<option data-val="0" value="' . route('admin-product-type-status', [
                    'id1' => $data->id, 'id2' => 0
                ]) . '" ' . $ns . '>' . __("Inactive") . '</option>
                    </select>
                </div>';
            })
            ->addColumn('action', function ($data) {
                return '<div class="action-list">
                    <a
                        href="' . route('admin-product-type-edit', $data->id) . '"
                    >
                        <i class="fas fa-edit"></i>' . __('Edit') . '
                    </a>
                    <a
                        href="javascript:;"
                        data-href="' . route('admin-product-type-delete', $data->id) . '"
                        data-toggle="modal"
                        data-target="#confirm-delete"
                        class="delete"
                    ><i class="fas fa-trash-alt"></i>
                    </a>
                </div>';
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }

    public function index()
    {
        return view('admin.producttype.index');
    }

    public function download()
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Categories');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getStyle('A1:A1')->getFont()->setBold(true);

        $tr = 1;

        $sheet->setCellValue("A$tr", 'Name');
        $tr += 1;

        $datas = ProductType::latest('id')->get();
        foreach ($datas as $row) {
            $sheet->setCellValue("A$tr", $row->name);

            $tr += 1;
        }

        $sheet = $sheet->getStyle('A1:A' . $tr)->applyFromArray($this->styleArray);

        $writer = new Xlsx($spreadsheet);
        $file = 'Product type-' . time() . '.xlsx';
        $writer->save($file);

        return response()
            ->download(public_path($file))
            ->deleteFileAfterSend();
    }

    public function create()
    {
        return view('admin.producttype.create');
    }

    public function store(Request $request)
    {
        $rules = ['name' => 'required'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $data = new ProductType();
        $input = $request->all();
        $data->fill($input)->save();
        $msg = __('Product type created successfully') . '<a
        href="' . route("admin-product-type-index") . '">' . __("    View Product Types Lists") . '</a>';
        return response()->json($msg);
    }

    //*** GET Request
    public function edit($id)
    {
        $data = ProductType::findOrFail($id);
        return view('admin.producttype.edit', compact('data'));
    }


    //*** POST Request
    public function update(Request $request, $id)
    {
        $rules = ['name' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $data = ProductType::findOrFail($id);
        $input = $request->all();
        $data->update($input);
        $msg = __('Data Updated Successfully.') . '<a href="' . route("admin-product-type-index") . '">' . __("View Product Types") . '</a>';
        return response()->json($msg);
    }

    //*** GET Request Status
    public function status($id1, $id2)
    {
        $data = ProductType::findOrFail($id1);
        $data->status = $id2;
        $data->update();
        $msg = __('Status Updated Successfully.');
        return response()->json($msg);
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = ProductType::findOrFail($id);
        $data->delete();
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
    }
}
