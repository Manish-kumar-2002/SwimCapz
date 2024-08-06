<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use App\Traits\ShippingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CurrencyController extends AdminBaseController
{
    use ShippingTrait ;
    public function datatables()
    {
        $datas = Currency::latest('id')->get();
        return DataTables::of($datas)
            ->editColumn('icon', function($row) {
                if(!$row->icon) {
                    return "";
                }
                return "<img src='".asset('assets/images/currencies/' . $row->icon)."'>";
            })
            ->addColumn('action', function (Currency $data) {
                $delete = $data->id == 1 ?
                '' : '<a
                            href="javascript:;"
                            data-href="' . route('admin-currency-delete', $data->id) . '"
                            data-toggle="modal"
                            data-target="#confirm-delete"
                            class="delete"
                        ><i class="fas fa-trash-alt"></i></a>';
                $default = $data->is_default == 1 ?
                    '<a><i class="fa fa-check"  title="default"></i> </a>' :
                    '<a
                        class="status"
                        data-href="' . route('admin-currency-status', [
                            'id1' => $data->id, 'id2' => 1
                        ]) . '"><i class="fa fa-retweet" title="set default">  </i></a>';

                return '<div class="action-list">
                    <a
                        data-href="' . route('admin-currency-edit', $data->id) . '"
                        class="edit"
                        data-toggle="modal"
                        data-target="#modal1"
                    > <i class="fas fa-edit"></i></a>' . $delete . $default . '</div>';
            })
            ->rawColumns(['action', 'icon'])
            ->toJson();
    }

    public function index()
    {
        return view('admin.currency.index');
    }

    public function create()
    {
        return view('admin.currency.create');
    }
    
    public function store(Request $request)
    {
        $rules = [
            'name' => 'unique:currencies',
            'sign' => 'unique:currencies'
        ];
        $customs = [
            'name.unique' => __('This name has already been taken.'),
            'sign.unique' => __('This sign has already been taken.')
        ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        
        $data = new Currency();
        $input = $request->all();

        if($request->file('icon')) {
            $imageName = time().'.'.$request->icon->extension();
            $request->icon->move(public_path('assets/images/currencies'), $imageName);

            $input['icon']=$imageName;
        }
        $input['value'] = 0;
        $data->fill($input)->save();
        $this->setCurrencyExchangeRate();
        
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data = Currency::findOrFail($id);
        return view('admin.currency.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'unique:currencies,name,' . $id,
            'sign' => 'unique:currencies,sign,' . $id
        ];
        $customs = [
            'name.unique' => __('This name has already been taken.'),
            'sign.unique' => __('This sign has already been taken.')
        ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        
        $data = Currency::findOrFail($id);
        $input = $request->all();

        if($request->file('icon')) {
            $imageName = time().'.'.$request->icon->extension();
            $request->icon->move(public_path('assets/images/currencies'), $imageName);

            $input['icon']=$imageName;
        }

        $data->update($input);
        $this->setCurrencyExchangeRate();
        
        cache()->forget('default_currency');

        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
    }

    public function status($id1, $id2)
    {
        $data = Currency::findOrFail($id1);
        $data->is_default = $id2;
        $data->update();
        Currency::where('id', '!=', $id1)
                    ->update(['is_default' => 0]);
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
    }

    public function destroy($id)
    {
        if ($id == 1) {
            return __("You cant't remove the main currency.");
        }
        $data = Currency::findOrFail($id);
        if ($data->is_default == 1) {
            Currency::where('id', '=', 1)
                ->update(['is_default' => 1]);
        }
        $data->delete();
    
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
    }
}