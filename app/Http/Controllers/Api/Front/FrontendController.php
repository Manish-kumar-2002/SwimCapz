<?php

namespace App\Http\Controllers\Api\Front;

use App\Classes\GeniusMailer;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BlogResource;
use App\Http\Resources\FaqResource;
use App\Http\Resources\FeaturedBannerResource;
use App\Http\Resources\FeaturedLinkResource;
use App\Http\Resources\OrderTrackResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\ProductlistResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\SliderResource;
use App\Models\Admin\Ttf;
use App\Models\ArrivalSection;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Cart;
use App\Models\Currency;
use App\Models\Faq;
use App\Models\FeaturedBanner;
use App\Models\FeaturedLink;
use App\Models\Font;
use App\Models\Generalsetting;
use App\Models\Language;
use App\Models\Order;
use App\Models\Page;
use App\Models\Pagesetting;
use App\Models\Partner;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SaveDesign;
use App\Models\Service;
use App\Models\Slider;
use App\Models\TempPageDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Stripe\Event;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\StripeClient;
use UnexpectedValueException;

class FrontendController extends Controller
{
    
    public function section_customization()
    {
        try {
            $data = Pagesetting::find(1)->toArray();
            return response()->json(['status' => true, 'data' => $data, 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function defaultLanguage()
    {
        try {
            $language = Language::where('is_default', '=', 1)->first();
            if (!$language) {
                return response()->json([
                    'status' => true, 'data' => [], 
                    'error' => ['message' => 'No Language Found']]);
            }
            $data_results = file_get_contents(public_path() . '/assets/languages/' . $language->file);
            $lang = json_decode($data_results);
            return response()->json([
                'status' => true, 'data' => ['basic' => $language, 'languages' => $lang], 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function language($id)
    {
        try {
            $language = Language::find($id);
            if (!$language) {
                return response()->json([
                    'status' => true, 'data' => [], 'error' => ['message' => 'No Language Found']]);
            }
            $data_results = file_get_contents(public_path() . '/assets/languages/' . $language->file);
            $lang = json_decode($data_results);
            return response()->json([
                'status' => true, 'data' => ['basic' => $language, 'languages' => $lang], 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function languages()
    {
        try {
            $languages = Language::all();
            return response()->json(['status' => true, 'data' => $languages, 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function defaultCurrency()
    {
        try {
            $currency = Currency::where('is_default', '=', 1)->first();
            if (!$currency) {
                return response()->json([
                    'status' => true, 'data' => [], 'error' => ['message' => 'No Currency Found']]);
            }
            return response()->json(['status' => true, 'data' => $currency, 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function currency($id)
    {
        try {
            $currency = Currency::find($id);
            if (!$currency) {
                return response()->json([
                    'status' => true, 'data' => [], 'error' => ['message' => 'No Currency Found']]);
            }
            return response()->json(['status' => true, 'data' => $currency, 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function currencies()
    {
        try {
            $currencies = Currency::all();
            return response()->json(['status' => true, 'data' => $currencies, 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function sliders()
    {
        try {
            $sliders = Slider::all();
            return response()->json(['status' => true, 'data' => SliderResource::collection($sliders), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function featuredLinks()
    {
        try {
            $featuredLinks = FeaturedLink::all();
            return response()->json([
                'status' => true, 'data' => FeaturedLinkResource::collection($featuredLinks), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function featuredBanners()
    {
        try {
            $featuredBanners = FeaturedBanner::all();
            return response()->json([
                'status' => true, 'data' => FeaturedBannerResource::collection($featuredBanners), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function services()
    {
        try {
            $services = Service::where('user_id', '=', 0)->get();
            return response()->json([
                'status' => true, 'data' => ServiceResource::collection($services), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function banners(Request $request)
    {

        try {

            $rules = [
                'type' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'data' => [], 'error' => $validator->errors()]);
            }

            $type = $request->type;
            $checkType = in_array($type, ['TopSmall', 'BottomSmall', 'Large']);

            if (!$checkType) {
                return response()->json([
                    'status' => false, 'data' => [],
                    'error' => ["message" => "This type doesn't exists."
                ]]);
            }

            if ($request->type == 'TopSmall') {
                $banners = Banner::where('type', '=', 'TopSmall')->get();
            } elseif ($request->type == 'BottomSmall') {
                $banners = Banner::where('type', '=', 'BottomSmall')->get();
            } elseif ($request->type == 'Large') {
                $ps = Pagesetting::first();
                $large_banner['image'] = asset('assets/images/' . $ps->big_save_banner1);
                $large_banner['title'] = $ps->big_save_banner_text;
                $large_banner['text'] = $ps->big_save_banner_subtitle;
                $large_banner['link'] = $ps->big_save_banner_link1;

                return response()->json(['status' => true, 'data' => $large_banner, 'error' => []]);
            }
            return response()->json(['status' => true, 'data' => BannerResource::collection($banners), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function partners()
    {
        try {
            $partners = Partner::all();
            return response()->json([
                'status' => true, 'data' => PartnerResource::collection($partners), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function products(Request $request)
    {

        try {
            $input = $request->all();

            if (!empty($input)) {

                $type = isset($input['type']) ? $input['type'] : '';
                $typeCheck = !empty($type) && in_array($type, ['Physical', 'Digital', 'License', 'Listing']);
                $highlight = isset($input['highlight']) ? $input['highlight'] : '';
                $highlightCheck = !empty($highlight) && in_array($highlight, [
                    'featured', 'best', 'top', 'big', 'is_discount', 'hot', 'latest', 'trending', 'sale'
                ]);
                $productType = isset($input['product_type']) ? $input['product_type'] : '';
                $productTypeCheck = !empty($productType) && in_array($productType, ['normal', 'affiliate']);
                $limit = isset($input['limit']) ? (int) $input['limit'] : 0;
                $paginate = isset($input['paginate']) ? (int) $input['paginate'] : 0;

                $prods = Product::whereStatus(1);

                if ($typeCheck) {
                    $prods = $prods->whereType($type);
                }

                if ($productTypeCheck) {
                    $prods = $prods->whereProductType($productType);
                }

                if ($highlightCheck) {
                    if ($highlight == 'featured') {
                        $prods = $prods->whereFeatured(1);
                    } elseif ($highlight == 'best') {
                        $prods = $prods->whereBest(1);
                    } elseif ($highlight == 'top') {
                        $prods = $prods->whereTop(1);
                    } elseif ($highlight == 'big') {
                        $prods = $prods->whereBig(1);
                    } elseif ($highlight == 'is_discount') {
                        $prods = $prods->whereIsDiscount(1);
                    } elseif ($highlight == 'hot') {
                        $prods = $prods->whereHot(1);
                    } elseif ($highlight == 'latest') {
                        $prods = $prods->whereLatest(1);
                    } elseif ($highlight == 'trending') {
                        $prods = $prods->whereTrending(1);
                    } else {
                        $prods = $prods->whereSale(1);
                    }
                }

                if ($limit != 0) {
                    $prods = $prods->where('status', 1)->take($limit);
                }

                if ($paginate == 0) {
                    $prods = $prods->where('status', 1)->get();
                } else {
                    $prods = $prods->where('status', 1)->paginate($paginate);
                }

                return response()->json([
                    'status' => true,
                    'data' => ProductlistResource::collection($prods)->response()->getData(true), 'error' => []]);
            } else {

                $prods = Product::where('status', 1)->get();
                return response()->json([
                    'status' => true,
                    'data' => ProductlistResource::collection($prods), 'error' => []]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function faqs()
    {
        try {
            $faqs = Faq::all();
            return response()->json(['status' => true, 'data' => FaqResource::collection($faqs), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function blogs(Request $request)
    {
        try {
            if($request->type == 'latest'){
                $blogs = Blog::orderby('id','desc')->take(6)->get();
            }else{
                $blogs = Blog::all();
            }
            
            return response()->json(['status' => true, 'data' => BlogResource::collection($blogs), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function pages()
    {
        try {
            $pages = Page::all();
            return response()->json(['status' => true, 'data' => PageResource::collection($pages), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function settings(Request $request)
    {
    
        try {

            $rules = [
                'name' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'data' => [], 'error' => $validator->errors()]);
            }

            $name = $request->name;
            $checkSettings = in_array($name, ['generalsettings', 'pagesettings', 'socialsettings']);
            if (!$checkSettings) {
                return response()->json([
                    'status' => false,
                    'data' => [],
                    'error' => ["message" => "This setting doesn't exists."]
                ]);
            }

            $setting = DB::table($name)->first();
            return response()->json(['status' => true, 'data' => $setting, 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function ordertrack(Request $request)
    {
        try {
            $rules = [
                'order_number' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'data' => [], 'error' => $validator->errors()]);
            }

            $order_number = $request->order_number;

            $order = Order::where('order_number', $order_number)->first();
            if (!$order) {
                return response()->json([
                    'status' => false,
                    'data' => [],
                    'error' => ["message" => "Order not found."]
                ]);
            }

            return response()->json([
                'status' => true,
                'data' => OrderTrackResource::collection($order->tracks), 'error' => []
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function contactmail(Request $request)
    {
        try {
           
            $rules =
                [
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'message' => 'required',

            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 'data' => [], 'error' => $validator->errors()]);
            }

            $gs = Generalsetting::find(1);

            // Login Section
            $ps = DB::table('pagesettings')->where('id', '=', 1)->first();
            $subject = "Email From Of " . $request->name;
            $to = $ps->contact_email;
            $name = $request->name;
            $from = $request->email;
            $msg = "Name: " . $name . "\nEmail: " . $from . "\nPhone:
            " . $request->phone . "\nMessage: " . $request->message;

            if ($gs->is_smtp) {
                $data = [
                    'to' => $to,
                    'subject' => $subject,
                    'body' => $msg,
                ];

                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($data);
            } else {
                $headers = "From: " . $name . "<" . $from . ">";
                mail($to, $subject, $msg, $headers);
            }
           
            return response()->json([
                'status' => true, 'data' => ['message' => 'Email Sent Successfully!'], 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function deal()
    {
        $gs = Generalsetting::findOrFail(1);
        $data['title'] = $gs->deal_title;
        $data['deal_details'] = $gs->deal_details;
        $data['time'] = $gs->deal_time;
        $data['image'] = url('/') . '/assets/images/' . $gs->deal_background;
        $data['link'] = $gs->link;
        return response()->json(['status' => true, 'data' => $data, 'error' => []]);
    }

    public function arrival()
    {
        $arrivalSection = ArrivalSection::get()->toArray();
        foreach ($arrivalSection as $key => $value) {
            $arrivalSection[$key]['photo'] = url('/') . '/assets/images/banners/' . $value['photo'];
        }

        return response()->json(['status' => true, 'data' => $arrivalSection, 'error' => []]);
    }

    public function getFontFamilies()
    {
        return response()->json([
            'status' => true,
            'data' => Font::all()
        ]);
    }

    public function getTtfFonts()
    {
        return response()->json([
            'status' => true,
            'data' => Ttf::all()
        ]);
    }

    public function activeWebhookEvent()
    {
        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $stripe->webhookEndpoints->create([
            'enabled_events' => [
                'charge.succeeded',
                'charge.failed',
                'charge.captured',
                'charge.expired',
                'charge.pending',
                'charge.refunded',
                'charge.succeeded',
                'payment_intent.canceled'
            ],
            'url' => 'https://example.com/my/webhook/endpoint',
        ]);

        
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $event = null;

        try {
            $event = Event::constructFrom(json_decode($payload, true));
        } catch (UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object;
                $this->handlePaymentMethodAttached($paymentMethod);
                break;
            case 'charge.captured' :
                    
                    break;
            case 'charge.expired':
                
                break;
            case 'charge.failed':
               
                break;
            case 'charge.pending':
                
                break;
            case 'charge.refunded':
                
                break;
            case 'charge.succeeded':
                
                break;
            case 'payment_intent.canceled':
               
                break;
            default:
                return response()->json(['error' => 'Received unknown event type ' . $event->type], 400);
        }

        return response()->json(['success' => true]);
    }

    private function handlePaymentIntentSucceeded(PaymentIntent $paymentIntent)
    {
        DB::table('testing')
                ->insert([
                    'response' => json_encode($paymentIntent)
                ]);
    }

    private function handlePaymentMethodAttached(PaymentMethod $paymentMethod)
    {
        // Your logic for handling payment method attached event
    }

    public function getVariantDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'variant_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false, 'data' => [], 'error' => $validator->errors()]);
        }

        $result=ProductVariant::with('products')
                            ->whereIn('id', explode(',', $request->variant_id))
                            ->get();
        return response()->json([
            'status' => true,
            'data' => $result,
            'message' => __('list fetched successfully.')
        ]);
        
    }

    public function getTempPageDetails($id, Request $request)
    {
        $result=null;
        if ($request->has('cart_id') && $request->cart_id) {
            $record=Cart::find($request->cart_id);
            if ($record) {
                $result=$record->raw_design;
            }
        } elseif($request->has('design_id') && $request->design_id) {
            $record=SaveDesign::find($request->design_id);
            if ($record) {
                $result=$record->raw_design;
            }
        }else {
            $record=TempPageDetail::where('user_id', $id)
                                ->first();
            if ($record) {
                $result=$record->result;
            }
        }

        return response()->json([
            'status' => true,
            'data' => $result,
            'message' => __('list fetched successfully.')
        ]);
    }

    public function storeTempPageDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'result'  =>'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false, 'data' => [], 'error' => $validator->errors()]);
        }

        TempPageDetail::updateOrCreate(['user_id' => $request->user_id],[
            'result' => json_encode($request->result)
        ]);

        return response()->json([
            'status' => true,
            'message' => __('list uploaded successfully.')
        ]);
    }

    public function priceCalculate(Request $request)
    {
        $result=[];
        $totalCapQty=0;
        foreach($request->payloads as $row) {
            $varient=ProductVariant::where('id', $row['varient_id'])
                                    ->first();

            if (!$varient || !$row['cap_quantity'] || !$row['front_noc']) {
               continue;
            }
    
            $price=Helper::calculateProductPrice(
                $varient->products->category_id,
                $row['cap_quantity'],
                $row['front_noc'],
                $row['back_noc'],
                $this->prepareNamesQty($row['names'])
            );
            $row['calculated_price']     = $price;
            $row['price_with_symbol']    = Helper::convertPrice($price);
            
            array_push($result, $row);
            $totalCapQty +=$row['cap_quantity'];
        }

        return response()
        ->json(array(
            'status'            => true,
            'result'            =>$result,
            'total_cap_qty'     =>$totalCapQty,
            'total_amount'      => array_sum(array_column($result, 'calculated_price')),
            'with_symbol'       => Helper::convertPrice(array_sum(array_column($result, 'calculated_price')))
        ), 200);
    }

    private function prepareNamesQty($names = [])
    {
        return array_sum(array_column($names, 'quantity'));
    }

    /* cart manage */
    public function addToCart(Request $request)
    {

        $validator=$this->cartValidate($request);
        if ($validator->fails()) {
            return response()->json([
                'status' => false, 'data' => [], 'error' => $validator->errors()]);
        }

        DB::beginTransaction();
        try{
            if ($request->has('cart_id')) {
                $cart=Cart::find($request->cart_id);
            } else {
                $cart=new Cart();
            }
            
            $cart->user_id      =$request->user_id;
            $cart->product_id   =$request->product_id;
            $cart->design_name  =$request->design_name;
            $cart->noc          =$request->noc_front;
            $cart->front_design =$this->getConvertedImages($request->front_design);
            $cart->noc_back     =$request->noc_back;
            if ($request->noc_back > 0) {
                $cart->back_design =$this->getConvertedImages($request->back_design, 'back');
            }

            if ($request->has('names') && $request->names) {
                $cart->names=json_encode($request->names);
            }

            $cart->save();
            $cart->saveRawDesign();

            foreach($request->variant_details as $item) {
                
                if ($request->has('cart_id')) {
                    $cart->details()->delete(); /* only one item in carts always */
                }

                $cart->details()
                            ->create([
                                'product_variant_id' => $item['variant_id'],
                                'total_qty'          => $item['total_qty'],
                                'total_price'        => $item['total_price'],
                                'remarks'            => $item['remarks'],
                                'front_design'       =>$this->getConvertedImages($item['front_design']),
                                'back_design'        =>$this->getConvertedImages($item['back_design'], 'back')
                            ]);
            }
            
            DB::commit();
            return response()
                        ->json(array(
                            'status' => 200,
                            'message' => __('Product added to cart successfully.')
                        ), 200);

        }catch(Exception $e){
            DB::rollBack();
            return response()
                        ->json(array(
                            'status' => 400,
                            'message' => 'failed :'.$e->getMessage()
                        ), 400);
        }
        
    }

    private function cartValidate($request)
    {
        $validator = $this->defaultValidation($request);

        $isOk=1;
        if ($validator->fails()) {
            $isOk=0;
        }

        if ($isOk) {
            $validator=$this->varientValidate($request);
            if ($validator->fails()) {
                $isOk=0;
            }
        }

        if ($isOk && $request->has('names') && $request->names && count($request->names) > 0) {
            $validator=$this->nameValidate($request);
        }

        return $validator;

    }

    private function nameValidate($request)
    {
        $totalUsedNameQty=array_sum(array_column($request->names, 'quantity'));
        $totalCapQty=array_sum(array_column($request->variant_details, 'total_qty'));


        $rules = $message=[];
        if ($totalUsedNameQty > $totalCapQty) {
            $rules['names']='required|lt:'.$totalCapQty;
            $message['names.lt'] = 'The total names must be less than :value.';
        }

        return Validator::make($request->all(), $rules, $message);
    }

    private function defaultValidation($request)
    {
        $rules=[
            'user_id'           => 'required',
            "product_id"        => 'required',
            "design_name"       => 'required',
            "front_design"      => 'required',
            "noc_front"         =>  'required|gte:1|lte:4',
            "noc_back"          =>  'required|lte:4',
            'variant_details'   =>  'required',
            "names"             =>  'nullable'
        ];

        if ($request->noc_back > 0) {
            $rules['back_design']='required';
        }

        return Validator::make($request->all(), $rules, [
            'noc_front.gte' => 'Front no of color must be greater than or equal to :value',
            'noc_front.lte' => 'Front no of color must be less than or equal to :value',
            'noc_back.lte' =>  'Back no of color must be less than or equal to :value',
        ]);
    }

    private function varientValidate($request)
    {
        $rules = $message =[];
        foreach($request->variant_details as $item) {
            $variant=null;

            if(@$item['variant_id']) {
                $variant=$this->getVariant($item['variant_id']);
            }
            
            if (!$variant) {

                $rules['product_'.$item['variant_id']]='required';
                $message['product_'.$item['variant_id'].'.required']=
                __("Product not found.");

            } else {

                if ($item['total_qty'] < $variant->minimum_order) {
                    $rules['product_'.$item['variant_id']]='required';
                    $message['product_'.$item['variant_id'].'.required']=
                        'Product quantity must be greater than '.$variant->minimum_order;
                }
            }
        }

        return Validator::make($request->all(), $rules, $message);

    }

    private function getVariant($variant_id)
    {
        return ProductVariant::where('id', $variant_id)
                                ->first();
    }
    
    private function getBase64Extension($base64)
    {
        $extention=null;
        preg_match('/^data:image\/(\w+);base64,/', $base64, $matches);
        if (count($matches) > 1) {
            switch ($matches[1]) {
                case 'jpeg':
                    $extention='jpeg';
                    break;
                case 'jpg':
                    $extention='jpg';
                    break;
                case 'png':
                    $extention='png';
                    break;
                case 'gif':
                    $extention='gif';
                    break;
                default:
                    break;
            }
        }

        return $extention;
    }
    private function getConvertedImages($base64, $path='front')
    {
        if ($base64 == "") {
            return null;
        }
        
        $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));

        $extension = $this->getBase64Extension($base64);
        $filename = $path.'-'.uniqid() . '.'.$extension;
        $path='ModifiedProduct/'.$path;
        
        Helper::manageFolder(public_path('storage/'.$path));
        Storage::disk('public')
                    ->put($path.'/' . $filename, $decodedImage);

        return $filename;

    }

    public function parseExcel(Request $request)
    {
        $spreadsheet = IOFactory::load($request->file('excelFile'));
        $sheet = $spreadsheet->getActiveSheet();
        $result=$sheet->toArray();

        $array=[];
        foreach($result as $key => $result) {
            if ($key == 0 || (@$result[0]== null && $result[1] == null)) {
                continue;
            }

            array_push($array,['name' => @$result[0], 'quantity' => @$result[1]]);
        }
        return response()
                        ->json(array('result' => $array));

    }

    public function getCart($id)
    {
        $result=Cart::with('details')->find($id);
        return response()
                        ->json(array('result' => $result));
    }
}