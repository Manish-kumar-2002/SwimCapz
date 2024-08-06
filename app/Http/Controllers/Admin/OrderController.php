<?php

namespace App\Http\Controllers\Admin;

use App\{
    Models\Cart,
    Models\User,
    Models\Order,
    Models\Product,
    Models\OrderTrack,
    Classes\GeniusMailer,
    Models\Generalsetting
};
use App\Classes\SendMail;
use App\Helpers\Helper;
use App\Helpers\PriceHelper;
use App\Models\AffliateBonus;
use App\Models\Courier;
use App\Models\TrackingDetail;
use App\Traits\TrackingTrait;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends AdminBaseController
{
    use TrackingTrait;

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

    public function orders($status = null)
    {
        return view('admin.order.orderSummary', [
            'status' => $status
        ]);
    }

    public function download($status = null)
    {

        $result = Order::query();

        if ($status != null) {
            $result = $result->where('order_status', '=', $status);
        }
        $result = $result->orderBy('created_at', 'desc')
            ->get();

        $array = [];
        foreach ($result as $row) {
            array_push($array, array(
                'email'     => $row->email,
                'order_no'  => $row->order_number,
                'totalQty'  => $row->totalQty,
                'price'     => Helper::convertPrice($row->pay_amount)
            ));
        }

        /* Now generate Excel */
        return $this->generateExcel($array);
    }

    private function generateExcel($array)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Order Details');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        $tr = 1;

        $sheet->setCellValue("A$tr", 'Email');
        $sheet->setCellValue("B$tr", 'Order Number');
        $sheet->setCellValue("C$tr", 'Total Qty');
        $sheet->setCellValue("D$tr", 'Price');
        $tr += 1;

        foreach ($array as $row) {
            $sheet->setCellValue("A$tr", $row['email']);
            $sheet->setCellValue("B$tr", $row['order_no']);
            $sheet->setCellValue("C$tr", $row['totalQty']);
            $sheet->setCellValue("D$tr", $row['price']);

            $tr += 1;
        }

        $sheet = $sheet->getStyle('A1:D' . $tr)->applyFromArray($this->styleArray);

        $writer = new Xlsx($spreadsheet);
        $file = 'Order-' . time() . '.xlsx';
        $writer->save($file);

        return response()
            ->download(public_path($file))
            ->deleteFileAfterSend();
    }

    public function processing()
    {
        return view('admin.order.processing');
    }

    public function completed()
    {
        return view('admin.order.completed');
    }

    public function declined()
    {
        return view('admin.order.declined');
    }

    public function datatables($status = null)
    {
        $datas = Order::query();
        if ($status != null) {
            if ($status == 6) {
                $datas = $datas->where('order_status', '=', $status)->orWhere('order_status', '=', 3);
            } elseif ($status == 1) {
                $datas = $datas->where('order_status', '=', $status)->orWhere('order_status', '=', 2);
            } else {
                $datas = $datas->where('order_status', '=', $status);
            }
        }

        $datas = $datas->removePending()
            ->orderBy('created_at', 'desc')
            ->get();
        return DataTables::of($datas)
            ->editColumn('name', function (Order $data) {
                return $data->name;
            })
            ->editColumn('id', function (Order $data) {
                return '<a href="' . route('admin-order-invoice', $data->id) . '">' .
                    $data->order_number . '</a>';
            })
            ->editColumn('pay_amount', function ($q) {
                return Helper::convertPrice($q->pay_amount, true);
            })
            ->addColumn('action', function ($data) {
                $html = '<div class="godropdown action-list">
                       
                       
                            <a href="' . route('admin-order-show', $data->id) . '" >
                                <i class="fas fa-eye"></i> 
                            </a>';

                if ($data->payment_status == Order::PAYMENT_SUCCESS || $data->method == Order::PAYMENT_METHOD_PO) {
                    $html .= '<a
                            href="javascript:;"
                            class="send"
                            data-email="' . $data->email . '"
                            data-toggle="modal" data-target="#vendorform"
                        ><i class="fas fa-envelope"></i> </a>';

                    // $html .='<a
                    //         href="'.route('track.order', $data->id).'"
                    //         link-url="'.route('track.order', $data->id).'"
                    //         link-title="Add Address"
                    //         class="link-modal"
                    //     ><i class="fas fa-truck"></i> </a>';
                }

                $html .= '<a
                        href="' . route('download.order.file', $data->id) . '"
                    ><i class="fa fa-download"></i> </a>';

                return $html . '</div>';
            })->rawColumns(['id', 'action'])->toJson();
    }

    /**
     * @method used for download zipped order file
     * @param order id
     * @return zipped folder
     */
    
    public function downloadOrderFile($id)
    {
        $order=Order::find($id);
        $order->prepareZipFile();
        return redirect(asset('storage/preparedOrder/zipped/'.$order->zip_file_name));
    }

    /**
     * @method used for showing order track info
     */
    public function trackOrder($order_id)
    {
        $order = Order::find($order_id);
        $courier = Courier::orderBy('courierName', 'asc')->get();
        $checkpoints = $order->trackingDetails()->get();
        if (!empty($order->trackingNo) && $checkpoints->isEmpty()) {
            $get_tracking = $this->getTracking($order->trackingNo, $order->courierSlug);
            // $get_tracking = $this->getTracking('32977126910' ,'bluedart');
            $checkpoint = !empty(json_decode($get_tracking)->data->tracking->checkpoints) ? json_decode($get_tracking)->data->tracking->checkpoints : [];
            if (!empty($checkpoint)) {
                foreach ($checkpoint as  $value) {
                    $trackingDetails                 = new TrackingDetail();
                    $trackingDetails->courier        = $value->slug;
                    $trackingDetails->location       = $value->location;
                    $trackingDetails->checkpointTime = $value->checkpoint_time;
                    $trackingDetails->country        = $value->country_name;
                    $trackingDetails->order_id       = $order->courierId;
                    $trackingDetails->save();
                }
                $checkpoints = $checkpoint;
            }
        }
        return view('admin.order.track', compact('order', 'courier', 'checkpoints'));
    }
    public function show($id)
    {
        $courier = Courier::all();
        $order = Order::findOrFail($id);
        return view('admin.order.details', compact('order', 'courier'));
    }

    public function invoice($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.order.invoice', compact('order'));
    }

    public function emailsub(Request $request)
    {
        $gs = Generalsetting::findOrFail(1);
        if ($gs->is_smtp == 1) {
            $data = [
                'to' => $request->to,
                'subject' => $request->subject,
                'body' => $request->message,
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        } else {
            $data = 0;
            $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            $mail = mail($request->to, $request->subject, $request->message, $headers);
            if ($mail) {
                $data = 1;
            }
        }

        return response()->json($data);
    }

    public function printpage($id)
    {
        $order = Order::findOrFail($id);
        $cart = json_decode($order->cart, true);
        return view('admin.order.print', compact('order', 'cart'));
    }

    public function license(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $cart = json_decode($order->cart, true);
        $cart['items'][$request->license_key]['license'] = $request->license;
        $new_cart = json_encode($cart);
        $order->cart = $new_cart;
        $order->update();
        $msg = __('Successfully changed the license key.');
        return redirect()->back()->with('license', $msg);
    }

    public function edit($id)
    {
        $data = Order::find($id);
        return view('admin.order.delivery', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = Order::findOrFail($id);
        $input = $request->all();

        if ($request->has('status')) {
            if ($data->status == Order::ORDER_COMPLETED) {
                $input['order_status'] = Order::ORDER_COMPLETED;
                $data->update($input);
                $this->sendOrderCompletedMail($order->email, $order->name, $order->order_number);
            } else {

                if ($request->status == Helper::ORDER_CANCELLED || $request->status == Helper::ORDER_REJECTED) {

                    if (!$request->ajax() && $request->track_text == null) {
                        return back()
                            ->with('unsuccess', __('Notes field is required.'));
                    }
                }

                $data->orderStatusChange($request);
            }
        }

        $data->update($input);
        $msg = __('Data updated successfully.');

        if ($request->ajax()) {
            return response()->json($msg);
        }

        return redirect()
            ->back()->with('success', $msg);
    }



    //*** POST Request
    public function _update(Request $request, $id)
    {

        //--- Logic Section
        $data = Order::findOrFail($id);
        $gs = Generalsetting::findOrFail(1);
        $input = $request->all();
        if ($request->has('status')) {
            if ($data->status == "completed") {

                // Then Save Without Changing it.
                $input['status'] = "completed";
                $data->update($input);
                //--- Logic Section Ends

                //--- Redirect Section
                $msg = __('Status updated successfully.');
                return response()->json($msg);
                //--- Redirect Section Ends

            } else {

                if ($input['status'] == "completed") {



                    if (User::where('id', $data->affilate_user)->exists()) {
                        $auser = User::where('id', $data->affilate_user)->first();
                        $auser->affilate_income += $data->affilate_charge;
                        $auser->update();

                        $affiliate_bonus = new AffliateBonus();
                        $affiliate_bonus->refer_id = $auser->id;
                        $affiliate_bonus->bonus =  $data->affilate_charge;
                        $affiliate_bonus->type = 'Order';
                        $affiliate_bonus->user_id = $data->user_id;
                        $affiliate_bonus->save();
                    }

                    if ($data->affilate_users != null) {
                        $ausers = json_decode($data->affilate_users, true);
                        foreach ($ausers as $auser) {
                            $user = User::find($auser['user_id']);
                            if ($user) {
                                $user->affilate_income += $auser['charge'];
                                $user->update();
                            }
                        }
                    }

                    $maildata = [
                        'to' => $data->customer_email,
                        'subject' => 'Your order ' . $data->order_number . ' is Confirmed!',
                        'body' => "Hello " . $data->customer_name . "," . "\n thank you for shopping with us. We are looking forward to your next visit.",
                    ];

                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($maildata);                
                    $mailer->sendCustomMail($maildata);                

                    $mailer->sendCustomMail($maildata);

                }
                if ($input['status'] == "declined") {

                    // Refund User Wallet If Any
                    if ($data->user_id != 0) {
                        if ($data->wallet_price != 0) {
                            $user = User::find($data->user_id);
                            if ($user) {
                                $user->balance = $user->balance + $data->wallet_price;
                                $user->save();
                            }
                        }
                    }

                    $cart = json_decode($data->cart, true);

                    // Restore Product Stock If Any
                    foreach ($cart->items as $prod) {
                        $x = (string)$prod['stock'];
                        if ($x != null) {

                            $product = Product::findOrFail($prod['item']['id']);
                            $product->stock = $product->stock + $prod['qty'];
                            $product->update();
                        }
                    }

                    // Restore Product Size Qty If Any
                    foreach ($cart->items as $prod) {
                        $x = (string)$prod['size_qty'];
                        if (!empty($x)) {
                            $product = Product::findOrFail($prod['item']['id']);
                            $x = (int)$x;
                            $temp = $product->size_qty;
                            $temp[$prod['size_key']] = $x;
                            $temp1 = implode(',', $temp);
                            $product->size_qty =  $temp1;
                            $product->update();
                        }
                    }

                    $maildata = [
                        'to' => $data->customer_email,
                        'subject' => 'Your order ' . $data->order_number . ' is Declined!',
                        'body' => "Hello " . $data->customer_name . "," . "\n We are sorry for the inconvenience caused. We are looking forward to your next visit.",
                    ];
                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($maildata);
                }

                $data->update($input);

                if ($request->track_text) {
                    $title = ucwords($request->status);
                    $ck = OrderTrack::where('order_id', '=', $id)->where('title', '=', $title)->first();
                    if ($ck) {
                        $ck->order_id = $id;
                        $ck->title = $title;
                        $ck->text = $request->track_text;
                        $ck->update();
                    } else {
                        $data = new OrderTrack;
                        $data->order_id = $id;
                        $data->title = $title;
                        $data->text = $request->track_text;
                        $data->save();
                    }
                }

                //--- Redirect Section          
                $msg = __('Status updated successfully.');
                return response()->json($msg);
                //--- Redirect Section Ends    

            }
        }

        $data->update($input);
        //--- Redirect Section          
        $msg = __('Data updated successfully.');
        return redirect()->back()->with('success', $msg);
        //--- Redirect Section Ends  

    }

    public function product_submit(Request $request)
    {

        $order_id = $request->order_id;
        $order = Order::find($order_id);
        $sku = $request->sku;
        $product = Product::whereStatus(1)->where('sku', $sku)->first();
        $data = array();
        if (!$product) {
            $data[0] = false;
            $data[1] = __('No product found');
        } else {
            $data[0] = true;
            $data[1] = $product->id;
        }
        return response()->json($data);
    }

    public function product_show($id)
    {
        $data['productt'] = Product::find($id);
        $data['curr'] = $this->curr;
        return view('admin.order.add-product', $data);
    }

    public function addcart($id)
    {

        $order = Order::find($id);
        $id = $_GET['id'];
        $qty = $_GET['qty'];
        $size = str_replace(' ', '-', $_GET['size']);
        $color = $_GET['color'];
        $size_qty = $_GET['size_qty'];
        $size_price = (float)$_GET['size_price'];
        $size_key = $_GET['size_key'];
        $affilate_user = isset($_GET['affilate_user']) ? $_GET['affilate_user'] : '0';
        $keys =  $_GET['keys'];
        $keys = explode(",", $keys);
        $values = $_GET['values'];
        $values = explode(",", $values);
        $prices = $_GET['prices'];
        $prices = explode(",", $prices);
        $keys = $keys == "" ? '' : implode(',', $keys);
        $values = $values == "" ? '' : implode(',', $values);
        $size_price = ($size_price / $order->currency_value);
        $prod = Product::where('id', '=', $id)->first(['id', 'user_id', 'slug', 'name', 'photo', 'size', 'size_qty', 'size_price', 'color', 'price', 'stock', 'type', 'file', 'link', 'license', 'license_qty', 'measure', 'whole_sell_qty', 'whole_sell_discount', 'attributes', 'minimum_qty']);

        if ($prod->user_id != 0) {
            $prc = $prod->price + $this->gs->fixed_commission + ($prod->price / 100) * $this->gs->percentage_commission;
            $prod->price = round($prc, 2);
        }
        if (!empty($prices)) {
            if (!empty($prices[0])) {
                foreach ($prices as $data) {
                    $prod->price += ($data / $order->currency_value);
                }
            }
        }

        if (!empty($prod->license_qty)) {
            $lcheck = 1;
            foreach ($prod->license_qty as $ttl => $dtl) {
                if ($dtl < 1) {
                    $lcheck = 0;
                } else {
                    $lcheck = 1;
                    break;
                }
            }
            if ($lcheck == 0) {
                return 0;
            }
        }
        if (empty($size)) {
            if (!empty($prod->size)) {
                $size = trim($prod->size[0]);
            }
            $size = str_replace(' ', '-', $size);
        }

        if (empty($color)) {
            if (!empty($prod->color)) {
                $color = $prod->color[0];
            }
        }
        $color = str_replace('#', '', $color);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);

        if (!empty($cart->items)) {
            if (!empty($cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)])) {
                $minimum_qty = (int)$prod->minimum_qty;
                if ($cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'] < $minimum_qty) {
                    return redirect()->back()->with('unsuccess', __('Minimum quantity is:') . ' ' . $prod->minimum_qty);
                }
            } else {
                if ($prod->minimum_qty != null) {
                    $minimum_qty = (int)$prod->minimum_qty;
                    if ($qty < $minimum_qty) {
                        return redirect()->back()->with('unsuccess', __('Minimum quantity is:') . ' ' . $prod->minimum_qty);
                    }
                }
            }
        } else {
            $minimum_qty = (int)$prod->minimum_qty;
            if ($prod->minimum_qty != null) {
                if ($qty < $minimum_qty) {
                    return redirect()->back()->with('unsuccess', __('Minimum quantity is:') . ' ' . $prod->minimum_qty);
                }
            }
        }

        $cart->addnum($prod, $prod->id, $qty, $size, $color, $size_qty, $size_price, $size_key, $keys, $values, $affilate_user);
        if ($cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['dp'] == 1) {
            return redirect()->back()->with('unsuccess', __('This item is already in the cart.'));
        }
        if ($cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['stock'] < 0) {
            return redirect()->back()->with('unsuccess', __('Out of stock.'));
        }
        if ($cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['size_qty']) {
            if ($cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'] > $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['size_qty']) {
                return redirect()->back()->with('unsuccess', __('Out of stock.'));
            }
        }

        $cart->totalPrice = 0;
        foreach ($cart->items as $data)
            $cart->totalPrice += $data['price'];
        $o_cart = json_decode($order->cart, true);

        $order->totalQty = $order->totalQty + $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'];
        $order->pay_amount = $order->pay_amount + $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['price'];

        $prev_qty = 0;
        $prev_price = 0;

        if (!empty($o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)])) {
            $prev_qty = $o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'];
            $prev_price = $o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['price'];
        }

        $prev_qty += $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'];
        $prev_price += $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['price'];

        $o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)] = $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)];
        $o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'] = $prev_qty;
        $o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['price'] = $prev_price;
        $order->cart = json_encode($o_cart);
        $order->update();
        return redirect()->back()->with('success', __('Successfully added to cart.'));
    }


    public function product_edit($id, $itemid, $orderid)
    {

        $product = Product::find($itemid);
        $order = Order::find($orderid);
        $cart = json_decode($order->cart, true);
        $data['productt'] = $product;
        $data['item_id'] = $id;
        $data['prod'] = $id;
        $data['order'] = $order;
        $data['item'] = $cart['items'][$id];
        $data['curr'] = $this->curr;

        return view('admin.order.edit-product', $data);
    }


    public function updatecart($id)
    {
        $order = Order::find($id);
        $id = $_GET['id'];
        $qty = $_GET['qty'];
        $size = str_replace(' ', '-', $_GET['size']);
        $color = $_GET['color'];
        $size_qty = $_GET['size_qty'];
        $size_price = (float)$_GET['size_price'];
        $size_key = $_GET['size_key'];
        $affilate_user = isset($_GET['affilate_user']) ? $_GET['affilate_user'] : '0';
        $keys =  $_GET['keys'];
        $keys = explode(",", $keys);
        $values = $_GET['values'];
        $values = explode(",", $values);
        $prices = $_GET['prices'];
        $prices = explode(",", $prices);
        $keys = $keys == "" ? '' : implode(',', $keys);
        $values = $values == "" ? '' : implode(',', $values);

        $item_id = $_GET['item_id'];


        $size_price = ($size_price / $order->currency_value);
        $prod = Product::where('id', '=', $id)->first(['id', 'user_id', 'slug', 'name', 'photo', 'size', 'size_qty', 'size_price', 'color', 'price', 'stock', 'type', 'file', 'link', 'license', 'license_qty', 'measure', 'whole_sell_qty', 'whole_sell_discount', 'attributes', 'minimum_qty']);

        if ($prod->user_id != 0) {
            $prc = $prod->price + $this->gs->fixed_commission + ($prod->price / 100) * $this->gs->percentage_commission;
            $prod->price = round($prc, 2);
        }
        if (!empty($prices)) {
            if (!empty($prices[0])) {
                foreach ($prices as $data) {
                    $prod->price += ($data / $order->currency_value);
                }
            }
        }

        if (!empty($prod->license_qty)) {
            $lcheck = 1;
            foreach ($prod->license_qty as $ttl => $dtl) {
                if ($dtl < 1) {
                    $lcheck = 0;
                } else {
                    $lcheck = 1;
                    break;
                }
            }
            if ($lcheck == 0) {
                return 0;
            }
        }
        if (empty($size)) {
            if (!empty($prod->size)) {
                $size = trim($prod->size[0]);
            }
            $size = str_replace(' ', '-', $size);
        }

        if (empty($color)) {
            if (!empty($prod->color)) {
                $color = $prod->color[0];
            }
        }
        $color = str_replace('#', '', $color);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);

        if (!empty($cart->items)) {
            if (!empty($cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)])) {
                $minimum_qty = (int)$prod->minimum_qty;
                if ($cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'] < $minimum_qty) {
                    return redirect()->back()->with('unsuccess', __('Minimum quantity is:') . ' ' . $prod->minimum_qty);
                }
            } else {
                if ($prod->minimum_qty != null) {
                    $minimum_qty = (int)$prod->minimum_qty;
                    if ($qty < $minimum_qty) {
                        return redirect()->back()->with('unsuccess', __('Minimum quantity is:') . ' ' . $prod->minimum_qty);
                    }
                }
            }
        } else {
            $minimum_qty = (int)$prod->minimum_qty;
            if ($prod->minimum_qty != null) {
                if ($qty < $minimum_qty) {
                    return redirect()->back()->with('unsuccess', __('Minimum quantity is:') . ' ' . $prod->minimum_qty);
                }
            }
        }

        $cart->addnum($prod, $prod->id, $qty, $size, $color, $size_qty, $size_price, $size_key, $keys, $values, $affilate_user);
        if ($cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['dp'] == 1) {
            return redirect()->back()->with('unsuccess', __('This item is already in the cart.'));
        }
        if ($cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['stock'] < 0) {
            return redirect()->back()->with('unsuccess', __('Out Of Stock.'));
        }
        if ($cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['size_qty']) {
            if ($cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'] > $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['size_qty']) {
                return redirect()->back()->with('unsuccess', __('Out of stock.'));
            }
        }

        $cart->totalPrice = 0;
        foreach ($cart->items as $data)
            $cart->totalPrice += $data['price'];
        $o_cart = json_decode($order->cart, true);

        if (!empty($o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)])) {

            $cart_qty = $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'];
            $cart_price =  $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['price'];

            $prev_qty = $o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'];
            $prev_price = $o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['price'];

            $temp_qty = 0;
            $temp_price = 0;

            if ($o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'] < $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty']) {

                $temp_qty = $cart_qty - $prev_qty;
                $temp_price = $cart_price - $prev_price;

                $order->totalQty += $temp_qty;
                $order->pay_amount += $temp_price;
                $prev_qty += $temp_qty;
                $prev_price += $temp_price;
            } elseif ($o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'] > $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty']) {

                $temp_qty = $prev_qty - $cart_qty;
                $temp_price = $prev_price - $cart_price;

                $order->totalQty -= $temp_qty;
                $order->pay_amount -= $temp_price;
                $prev_qty -= $temp_qty;
                $prev_price -= $temp_price;
            }
        } else {

            $order->totalQty -= $o_cart['items'][$item_id]['qty'];

            $order->pay_amount -= $o_cart['items'][$item_id]['price'];

            unset($o_cart['items'][$item_id]);



            $order->totalQty = $order->totalQty + $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'];
            $order->pay_amount = $order->pay_amount + $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['price'];

            $prev_qty = 0;
            $prev_price = 0;

            if (!empty($o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)])) {
                $prev_qty = $o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'];
                $prev_price = $o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['price'];
            }

            $prev_qty += $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'];
            $prev_price += $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['price'];
        }

        $o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)] = $cart->items[$id . $size . $color . str_replace(str_split(' ,'), '', $values)];
        $o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'] = $prev_qty;
        $o_cart['items'][$id . $size . $color . str_replace(str_split(' ,'), '', $values)]['price'] = $prev_price;

        $order->cart = json_encode($o_cart);

        $order->update();
        return redirect()->back()->with('success', __('Successfully updated the cart.'));
    }


    public function product_delete($id, $orderid)
    {


        $order = Order::find($orderid);
        $cart = json_decode($order->cart, true);

        $order->totalQty = $order->totalQty - $cart['items'][$id]['qty'];
        $order->pay_amount = $order->pay_amount - $cart['items'][$id]['price'];
        unset($cart['items'][$id]);
        $order->cart = json_encode($cart);

        $order->update();


        return redirect()->back()->with('success', __('Successfully deleted from the cart.'));
    }


    // URL of this function is "http://localhost:8000/api/v1/update/trackwebhook" for webhook of update tracking details .
    public function trackwebhook(Request $request)
    {
        $data = $request->url();
        dd($data);
        return $this->webhook($data);
    }

    private function sendOrderCompletedMail($email, $name, $id)
    {
        $to = $email;
        $subject = 'Order Completed with SwimCapz.';
        $msg = " 	 
                Hello $name,</>
                </br>
                We are happy to inform you that your order $id has been delivered.</br>
                </br>
                We hope you enjoy your purchase! If you have any questions or concerns, please feel free to contact our support team.</br>
                </br>
                Thank you for shopping with Swimcapz!</br>
                </br>
                Best regards,</br>
                The Swimcapz Team";
        //Sending Email To Customer
        $template = 'emails.email_verification_link';
        $mailer = new SendMail();
        $mailer->sendCustomMail($template, $msg, $to, $subject);
    }
}
