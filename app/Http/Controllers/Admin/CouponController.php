<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PriceHelper;
use App\Models\Category;
use App\Models\Childcategory;
use App\Models\Coupon;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends AdminBaseController
{

    public function datatables()
    {
        $datas = Coupon::latest('id')->get();
        return DataTables::of($datas)
            ->editColumn('type', function (Coupon $data) {
                return $data->type == 0 ? "Discount By Percentage" : "Discount By Amount";
            })
            ->editColumn('price', function (Coupon $data) {
                return $data->type == 0 ?
                    $data->price . '%' :
                    PriceHelper::showAdminCurrencyPrice($data->price * $this->curr->value);
            })
            ->addColumn('status', function (Coupon $data) {
                $selected_class=$data->status == 1 ? 'fa-toggle-on' : 'fa-toggle-off';
                $url=route('admin-coupon-status', [$data->id]);
                return '<label data-confirm="1" data-url="'.$url.'" class="toggle-checkbox form-check-label" for="toggleSwitch">
                    <i id="toggleIcon" class="fas '.$selected_class.'"></i>
                </label>';
            })
            ->addColumn('action', function (Coupon $data) {
                return '<div class="action-list">
                    <a href="' . route('admin-coupon-edit', $data->id) . '">
                        <i class="fas fa-edit"></i></a>
                    <a
                        href="javascript:;"
                        data-href="' . route('admin-coupon-delete', $data->id) . '"
                        data-toggle="modal"
                        data-target="#confirm-delete"
                        class="delete"
                    ><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }

    public function index()
    {
        return view('admin.coupon.index');
    }


    public function create()
    {
        $categories = Category::where('status', 1)
            ->get();
        return view('admin.coupon.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'code'      => 'required|unique:coupons,code',
            'category'  => 'required',
            // 'product'   =>'required',
            'type'      => 'required',
            'description'      => 'required',
            'value'     => 'required|numeric',
            'times'     => 'required|numeric',
            'start_date' => 'required',
            'end_date'  => 'required'
        ];

        $customs = [
            'code.unique' => __('This code has already been taken.'),
            'value.required' => __('This price field is required.'),
        ];

        $data = $request->all();
        if ($request->type == "0" && $request->value > 100) {
            $data['value'] = '';
            $customs['value.required'] = __('Value must be smaller than 100.');
        }

        $validator = Validator::make($data, $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }

        $data = new Coupon();
        $input = $request->all();

        $input['start_date'] = Carbon::parse($input['start_date'])->format('Y-m-d');
        $input['end_date'] = Carbon::parse($input['end_date'])->format('Y-m-d');
        $input['price']     = $input['value'];
        // if($request->input('product') == null){
        //     $input['product'] = 0 ;
        // }
        $data->fill($input)->save();

        $msg = __('New Data Added Successfully.') .
            '<a href="' . route("admin-coupon-index") . '">' . __("View Coupon Lists") . '</a>';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $categories = Category::where('status', 1)->get();
        $sub_categories = Subcategory::where('status', 1)->get();
        $child_categories = Childcategory::where('status', 1)->get();
        $data = Coupon::findOrFail($id);
        return view('admin.coupon.edit', compact('data', 'categories', 'sub_categories', 'child_categories'));
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'code'          => 'required|unique:coupons,code,' . $id,
            'category'      => 'required',
            // 'product'   =>'required',
            'type'          => 'required',
            'description'   => 'required',
            'value'         => 'required|numeric',
            'times'         => 'required|numeric',
            'start_date'    => 'required',
            'end_date'      => 'required'
        ];

        $customs = [
            'code.unique' => __('This code has already been taken.'),
            'value.required' => __('This price field is required.'),
        ];

        $data = $request->all();
        if ($request->type == "0" && $request->value > 100) {
            $data['value'] = '';
            $customs['value.required'] = __('Value must be smaller than 100.');
        }

        $validator = Validator::make($data, $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $data = Coupon::findOrFail($id);
        $input = $request->all();

        $input['start_date'] = Carbon::parse($input['start_date'])->format('Y-m-d');
        $input['end_date'] = Carbon::parse($input['end_date'])->format('Y-m-d');
        $input['price'] = $input['value'];

        $data = $data->update($input);

        $msg = __('Data Updated Successfully.') .
            '<a href="' . route("admin-coupon-index") . '">' . __("View Coupon Lists") . '</a>';

        return response()->json($msg);
    }

    public function status($id1, $id2=null)
    {
        $data = Coupon::findOrFail($id1);
        $data->status = !$data->status;
        $data->update();

        $msg = __('Status Updated Successfully.');
        return response()->json($msg);
    }


    public function destroy($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->delete();
    
            if (Session::has('APPLIEDCOUPON')) {
                Session::forget('APPLIEDCOUPON');
            }
    
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json(['error' => __('Failed to delete data.')], 500);
        }
    }
}
