<?php

namespace App\Http\Controllers\Admin;

use App\{
    Models\User,
    Models\Withdraw,
    Models\Transaction,
    Classes\GeniusMailer,
};

use Illuminate\{
    Http\Request,
    Support\Str
};

use Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserController extends AdminBaseController
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
        $datas = User::latest('id')->get();
        return DataTables::of($datas)
            ->editColumn('created_at', function ($row) {
                return $row->created_at ;
            })
            ->addColumn('action', function (User $data) {


                return '<div class="action-list">
                                <a href="' . route('admin-user-show', $data->id) . '" >
                                    <i class="fas fa-eye"></i> 
                                </a>
                                <a
                                    data-href="' . route('admin-user-edit', $data->id) . '"
                                    class="edit"
                                    data-toggle="modal"
                                    data-target="#modal1"
                                >
                                    <i class="fas fa-edit"></i></a>
                    <a
                                    href="javascript:;"
                                    data-href="' . route('admin-user-delete', $data->id) . '"
                                    data-toggle="modal" data-target="#confirm-delete"
                                    class="delete"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>';
            })
            ->addColumn('status', function (User $data) {
                $class = $data->ban == 0 ? 'drop-success' : 'drop-danger';
                $s = $data->ban == 0 ? 'selected' : '';
                $ns = $data->ban == 1 ? 'selected' : '';
                $ban = '<select class="process select droplinks ' . $class . '">' .
                    '<option data-val="0" value="' . route('admin-user-ban', [
                        'id1' => $data->id, 'id2' => 1
                    ]) . '" ' . $s . '>' . __("Active") . '</option>' .
                    '<option data-val="1" value="' . route('admin-user-ban', [
                        'id1' => $data->id, 'id2' => 0
                    ]) . '" ' . $ns . '>' . __("Inactive") . '</option>
                        </select>';
                return '<div class="action-list"> ' . $ban . '</div>';
            })
            ->editColumn('phone', function (User $data) {
                if (isset($data->phone) && !empty($data->phone)) {
                    return $data->phone;
                } else {
                    return '--';
                }
            })
            ->rawColumns(['action', 'status', 'phone'])
            ->toJson();
    }

    public function download()
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Customer');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        $tr = 1;

        $sheet->setCellValue("A$tr", 'Name');
        $sheet->setCellValue("B$tr", 'Email');
        $sheet->setCellValue("C$tr", 'Phone No.');
        $tr += 1;

        $datas = User::latest('id')->get();
        foreach ($datas as $row) {
            $sheet->setCellValue("A$tr", $row->name);
            $sheet->setCellValue("B$tr", $row->email);
            $sheet->setCellValue("C$tr", $row->phone);

            $tr += 1;
        }

        $sheet = $sheet->getStyle('A1:C' . $tr)->applyFromArray($this->styleArray);

        $writer = new Xlsx($spreadsheet);
        $file = 'Customer-' . time() . '.xlsx';
        $writer->save($file);

        return response()
            ->download(public_path($file))
            ->deleteFileAfterSend();
    }
    public function index()
    {
        return view('admin.user.index');
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function withdraws()
    {
        return view('admin.user.withdraws');
    }

    //*** GET Request
    public function show($id)
    {
        $data = User::findOrFail($id);
        return view('admin.user.show', compact('data'));
    }

    //*** GET Request
    public function ban($id1, $id2)
    {
        $user = User::findOrFail($id1);
        $user->ban = $id2;
        $user->update();
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            'email'    => 'required|email|unique:users',
            'photo'    => 'required|mimes:jpeg,jpg,png,svg',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends
        $data = new User();
        $input = $request->all();
        $input['password'] = bcrypt($request['password']);
        if ($file = $request->file('photo')) {
            $name = \PriceHelper::ImageCreateName($file);
            $file->move('assets/images/users', $name);
            $input['photo'] = $name;
        }
        $input['email_verified'] = 'Yes';
        $data->fill($input)->save();

        // Welcome Email For User

        $data = [
            'to' => $data->email,
            'type' => "new_registration",
            'cname' => $data->name,
            'oamount' => "",
            'aname' => "",
            'aemail' => "",
            'onumber' => "",
        ];
        $mailer = new GeniusMailer();
        $mailer->sendAutoMail($data);

        $msg = __('New Customer Added Successfully.');
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data = User::findOrFail($id);
        return view('admin.user.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'email'    => 'required|email|unique:users,email,' . $id,
            'photo'    => 'mimes:jpeg,jpg,png,svg'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $user = User::findOrFail($id);
        $data = $request->all();
        if ($file = $request->file('photo')) {
            $name = \PriceHelper::ImageCreateName($file);
            $file->move('assets/images/users', $name);
            if ($user->photo != null) {
                if (file_exists(public_path() . '/assets/images/users/' . $user->photo)) {
                    unlink(public_path() . '/assets/images/users/' . $user->photo);
                }
            }
            $data['photo'] = $name;
        }

        if (!empty($request->password)) {
            $data['password'] = bcrypt($request['password']);
        } else {
            $data['password'] = $user->password;
        }

        $user->update($data);
        $msg = __('Customer information updated successfully.');
        return response()->json($msg);
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->reports->count() > 0) {
            foreach ($user->reports as $gal) {
                $gal->delete();
            }
        }

        if ($user->ratings->count() > 0) {
            foreach ($user->ratings as $gal) {
                $gal->delete();
            }
        }

        if ($user->notifications->count() > 0) {
            foreach ($user->notifications as $gal) {
                $gal->delete();
            }
        }

        if ($user->wishlists->count() > 0) {
            foreach ($user->wishlists as $gal) {
                $gal->delete();
            }
        }

        if ($user->withdraws->count() > 0) {
            foreach ($user->withdraws as $gal) {
                $gal->delete();
            }
        }

        if ($user->socialProviders->count() > 0) {
            foreach ($user->socialProviders as $gal) {
                $gal->delete();
            }
        }

        if ($user->conversations->count() > 0) {
            foreach ($user->conversations as $gal) {
                if ($gal->messages->count() > 0) {
                    foreach ($gal->messages as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }
        if ($user->comments->count() > 0) {
            foreach ($user->comments as $gal) {
                if ($gal->replies->count() > 0) {
                    foreach ($gal->replies as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }

        if ($user->replies->count() > 0) {
            foreach ($user->replies as $gal) {
                if ($gal->subreplies->count() > 0) {
                    foreach ($gal->subreplies as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }

        if ($user->favorites->count() > 0) {
            foreach ($user->favorites as $gal) {
                $gal->delete();
            }
        }

        if ($user->subscribes->count() > 0) {
            foreach ($user->subscribes as $gal) {
                $gal->delete();
            }
        }

        if ($user->services->count() > 0) {
            foreach ($user->services as $gal) {
                if (file_exists(public_path() . '/assets/images/services/' . $gal->photo)) {
                    unlink(public_path() . '/assets/images/services/' . $gal->photo);
                }
                $gal->delete();
            }
        }

        if ($user->withdraws->count() > 0) {
            foreach ($user->withdraws as $gal) {
                $gal->delete();
            }
        }

        if ($user->products->count() > 0) {

            // PRODUCT
            foreach ($user->products as $prod) {
                if ($prod->galleries->count() > 0) {
                    foreach ($prod->galleries as $gal) {
                        if (file_exists(public_path() . '/assets/images/galleries/' . $gal->photo)) {
                            unlink(public_path() . '/assets/images/galleries/' . $gal->photo);
                        }
                        $gal->delete();
                    }
                }
                if ($prod->ratings->count() > 0) {
                    foreach ($prod->ratings as $gal) {
                        $gal->delete();
                    }
                }
                if ($prod->wishlists->count() > 0) {
                    foreach ($prod->wishlists as $gal) {
                        $gal->delete();
                    }
                }
                if ($prod->clicks->count() > 0) {
                    foreach ($prod->clicks as $gal) {
                        $gal->delete();
                    }
                }
                if ($prod->comments->count() > 0) {
                    foreach ($prod->comments as $gal) {
                        if ($gal->replies->count() > 0) {
                            foreach ($gal->replies as $key) {
                                $key->delete();
                            }
                        }
                        $gal->delete();
                    }
                }
                if (file_exists(public_path() . '/assets/images/products/' . $prod->photo)) {
                    unlink(public_path() . '/assets/images/products/' . $prod->photo);
                }

                $prod->delete();
            }

            // PRODUCT ENDS

        }
        // OTHER SECTION 

        if ($user->senders->count() > 0) {
            foreach ($user->senders as $gal) {
                if ($gal->messages->count() > 0) {
                    foreach ($gal->messages as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }

        if ($user->recievers->count() > 0) {
            foreach ($user->recievers as $gal) {
                if ($gal->messages->count() > 0) {
                    foreach ($gal->messages as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }

        if ($user->conversations->count() > 0) {
            foreach ($user->conversations as $gal) {
                if ($gal->messages->count() > 0) {
                    foreach ($gal->messages as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }


        if ($user->notivications->count() > 0) {
            foreach ($user->notivications as $gal) {
                $gal->delete();
            }
        }

        if ($user->shippings->count() > 0) {
            foreach ($user->shippings as $gal) {
                $gal->delete();
            }
        }

        if ($user->packages->count() > 0) {
            foreach ($user->packages as $gal) {
                $gal->delete();
            }
        }
        if ($user->verifies->count() > 0) {
            foreach ($user->verifies as $gal) {
                $gal->delete();
            }
        }
        if ($user->sociallinks->count() > 0) {
            foreach ($user->sociallinks as $gal) {
                $gal->delete();
            }
        }
        // OTHER SECTION ENDS

        //If Photo Doesn't Exist
        if ($user->photo == null) {
            $user->delete();
            //--- Redirect Section     
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
            //--- Redirect Section Ends 
        }
        //If Photo Exist
        if (file_exists(public_path() . '/assets/images/users/' . $user->photo)) {
            unlink(public_path() . '/assets/images/users/' . $user->photo);
        }
        $user->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends    
    }

    //*** JSON Request
    public function withdrawdatatables()
    {
        $datas = Withdraw::where('type', '=', 'user')->latest('id')->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->addColumn('email', function (Withdraw $data) {
                $email = $data->user->email;
                return $email;
            })
            ->addColumn('phone', function (Withdraw $data) {
                $phone = $data->user->phone;
                return $phone;
            })
            ->editColumn('status', function (Withdraw $data) {
                $status = ucfirst($data->status);
                return $status;
            })
            ->editColumn('amount', function (Withdraw $data) {
                $sign = $this->curr;
                $amount = $data->amount * $sign->value;
                return \PriceHelper::showAdminCurrencyPrice($amount);;
            })
            ->addColumn('action', function (Withdraw $data) {
                $action = '<div class="action-list"><a data-href="' . route('admin-withdraw-show', $data->id) . '" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i> ' . __("Details") . '</a>';
                if ($data->status == "pending") {
                    $action .= '<a data-href="' . route('admin-withdraw-accept', $data->id) . '" data-toggle="modal" data-target="#status-modal1"> <i class="fas fa-check"></i> ' . __("Accept") . '</a><a data-href="' . route('admin-withdraw-reject', $data->id) . '" data-toggle="modal" data-target="#status-modal"> <i class="fas fa-trash-alt"></i> ' . __("Reject") . '</a>';
                }
                $action .= '</div>';
                return $action;
            })
            ->rawColumns(['name', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }


    //*** GET Request       
    public function withdrawdetails($id)
    {
        $sign = $this->curr;
        $withdraw = Withdraw::findOrFail($id);
        return view('admin.user.withdraw-details', compact('withdraw', 'sign'));
    }

    //*** GET Request   
    public function accept($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        $data['status'] = "completed";
        $withdraw->update($data);
        //--- Redirect Section     
        $msg = __('Withdraw Accepted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends   
    }

    //*** GET Request
    public function reject($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        $account = User::findOrFail($withdraw->user->id);
        $account->affilate_income = $account->affilate_income + $withdraw->amount + $withdraw->fee;
        $account->update();
        $data['status'] = "rejected";
        $withdraw->update($data);
        //--- Redirect Section     
        $msg = __('Withdraw Rejected Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends   
    }



    //*** GET Request
    public function deposit($id)
    {
        $sign = $this->curr;
        $data = User::findOrFail($id);
        return view('admin.user.deposit', compact('data', 'sign'));
    }

    public function depositupdate(Request $request, $id)
    {
        $sign = $this->curr;
        $user = User::findOrFail($id);
        if ($request->type == 'plus') {
            $user->balance += (float)$request->amount;
        } else {
            $user->balance -= (float)$request->amount;
        }
        $user->update();
        $transaction = new Transaction;
        $transaction->txn_number = Str::random(3) . substr(time(), 6, 8) . Str::random(3);
        $transaction->amount = $request->amount;
        $transaction->user_id = $id;
        $transaction->currency_sign = $sign->sign;
        $transaction->currency_code = $sign->name;
        $transaction->currency_value = $sign->value;
        $transaction->method = null;
        $transaction->txnid = null;
        $transaction->details = $request->details;
        $transaction->type = $request->type;
        $transaction->save();
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
    }
}
