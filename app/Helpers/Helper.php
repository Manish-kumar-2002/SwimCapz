<?php

namespace App\Helpers;

use AddressInfo;
use App\Models\Addresses;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Order;
use App\Models\PriceStructure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Traits\ShippingTrait;


class Helper
{
    use ShippingTrait;
    const PRIVACY = 'privacy-policy';
    const REFUND = 'refund-policy';
    const DELIVERY = 'delivery-policy';
    const DISCLAIMER = 'color-disclaimer';

    const ORDER_PENDING = Order::ORDER_PENDING;
    const ORDER_SUCCESS = Order::ORDER_SUCCESS;
    const ORDER_ACCEPTED = Order::ORDER_ACCEPTED;
    const ORDER_REJECTED = Order::ORDER_REJECTED;
    const ORDER_SHIPPED = Order::ORDER_SHIPPED;
    const ORDER_COMPLETED = Order::ORDER_COMPLETED;
    const ORDER_CANCELLED = Order::ORDER_CANCELLED;

    const ORDER_PENDING_MSG = Order::ORDER_PENDING_MSG;
    const ORDER_SUCCESS_MSG = Order::ORDER_SUCCESS_MSG;
    const ORDER_ACCEPTED_MSG = Order::ORDER_ACCEPTED_MSG;
    const ORDER_REJECTED_MSG = Order::ORDER_REJECTED_MSG;
    const ORDER_SHIPPED_MSG = Order::ORDER_SHIPPED_MSG;
    const ORDER_COMPLETED_MSG = Order::ORDER_COMPLETED_MSG;
    const ORDER_CANCELLED_MSG = Order::ORDER_CANCELLED_MSG;

    const PAYMENT_PENDING = Order::PAYMENT_PENDING;
    const PAYMENT_SUCCESS = Order::PAYMENT_SUCCESS;
    const PAYMENT_REFUND = Order::PAYMENT_REFUND;

    const PAYMENT_PENDING_MSG = Order::PAYMENT_PENDING_MSG;
    const PAYMENT_SUCCESS_MSG = Order::PAYMENT_SUCCESS_MSG;
    const PAYMENT_REFUND_MSG = Order::PAYMENT_REFUND_MSG;

    const PAYMENT_METHOD_PO = Order::PAYMENT_METHOD_PO;
    const PAYMENT_METHOD_STRIPE = Order::PAYMENT_METHOD_STRIPE;

    /* currentcies list */
    public static function getCurrencies()
    {
        return Cache::remember('currencies', 10, function () {
            return Currency::all();
        });
    }

    /* default curreny */
    public static function getDefaultCurrency($selected_currency = null)
    {
        if ($selected_currency == null) {
            return Cache::remember('default-currencies', 10, function () {
                $result = Currency::where('is_default', '=', 1)->first();
                return $result ?? null;
            });
        }

        $result = Currency::where('id', $selected_currency)->first();
        return $result ?? null;
    }

    public static function generateUniqueID()
    {
        return time() . bin2hex(random_bytes(5));
    }

    //generate session
    public static function getUserSession()
    {
        $sessionId = Session::get('SESSIONID');

        if ($sessionId) {
            return $sessionId;
        }

        // This reduces the chances of collisions
        $uniqueID = self::generateUniqueID();
        while (Cart::where('session_id', $uniqueID)->exists()) {
            $uniqueID = self::generateUniqueID();
        }

        Session::put('SESSIONID', $uniqueID, 2800);

        return $uniqueID;
    }

    //generate Unique OrderNumber
    public static function getUniqueOrderNumber()
    {
        $uniqueID = self::generateUniqueID();
        while (Order::where('order_number', $uniqueID)->exists()) {
            $uniqueID = self::generateUniqueID();
        }
        return $uniqueID;
    }

    public static function cartList()
    {
        return Cart::where(function ($query) {
            if (Auth::check()) {
                $query->where('user_id', Auth::user()->id);
            }
                $query->orWhere('session_id', self::getUserSession());
        })->orderBy('created_at', 'desc')
            ->get();
            
    }

    public static function cartCheck()
    {
        return count(self::cartList()) > 0 ? 1 : 0;
    }

    public static function getSubString($string, $length = 45)
    {
        return mb_strlen($string, 'UTF-8') > $length ? mb_substr($string, 0, $length, 'UTF-8') . '...' : $string;
    }

    public static function manageCart()
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::user()->id)
                ->update([
                    'session_id' => self::getUserSession()
                ]);
        }
    }

    public static function getColorbyStatus($status) {
        switch ($status) {
            case Order::ORDER_PENDING_MSG:
                return "#FF5733"; // Red
            case Order::ORDER_SUCCESS_MSG:
                return "#28A745"; // Green
            case Order::ORDER_ACCEPTED_MSG:
                return "#007BFF"; // Blue
            case Order::ORDER_REJECTED_MSG:
                return "#FFC107"; // Yellow
            case Order::ORDER_SHIPPED_MSG:
                return "#FF8C00"; // Orange
            case Order::ORDER_COMPLETED_MSG:
                return "#6F42C1"; // Purple
            case Order::ORDER_CANCELLED_MSG:
                return "#6C757D"; // Gray
            default:
                return "#000000"; // Black or another default color code
        }
    }
    


    public static function convertIntoCurrency($amount)
    {
        if (Session::has('currency')) {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return Currency::find(Session::get('currency'));
            });
        } else {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return Currency::where('is_default', '=', 1)->first();
            });
        }
        return $amount * $curr->value;
    }
    public static function convertPrice($price, $appendSign = true)
    {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        if (Session::has('currency')) {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return Currency::find(Session::get('currency'));
            });
        } else {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return Currency::where('is_default', '=', 1)->first();
            });
        }
        $price = $price * $curr->value;
        $price = PriceHelper::showPrice($price);

        if (!$appendSign) {
            return $price;
        }

        if ($gs->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public static function cartTotal($isConvert = true, $appendSign = true)
    {
        $amount = Cart::join('cart_details', 'cart_details.cart_id', '=', 'carts.id')
            ->join('product_variants as pv', 'pv.id', '=', 'cart_details.product_variant_id')
            ->where(function ($query) {
                if (Auth::check()) {
                    $query->orWhere('carts.user_id', Auth::user()->id);
                }
                $query->orWhere('session_id', self::getUserSession());
            })->get()->sum(function ($query) {
                return ($query->total_qty * $query->total_price) + $query->name_cost;
            });
        return $isConvert ? self::convertPrice($amount, $appendSign) : $amount;
    }

    public static function cartTotalQuantity()
    {
        return Cart::join('cart_details', 'cart_details.cart_id', '=', 'carts.id')
            ->join('product_variants as pv', 'pv.id', '=', 'cart_details.product_variant_id')
            ->where(function ($query) {
                if (Auth::check()) {
                    $query->orWhere('carts.user_id', Auth::user()->id);
                }
                $query->orWhere('session_id', self::getUserSession());
            })->count('cart_details.id');
    }

    public static function deliveryCharge($isConvert = true)
    {
        $amount = 0;
        return $isConvert ? self::convertPrice($amount) : $amount;
    }

    public static function rushCharge($default = false)
    {
        $amount = 0;
        if (self::isActiveRushShipping()) {
            $amount = (self::cartTotal(false) * 10) / 100;
        }
        if ($default) {
            return self::convertPrice($amount);
        }

        return $amount;
    }

    public static function taxCharge($isConvert = true)
    {
        $amount = 0;
        return $isConvert ? self::convertPrice($amount) : $amount;
    }


    public static function totalCharge($isConvert = true)
    {
        $amount = SELF::cartTotal(false, false) +
            SELF::deliveryCharge(false) +
            SELF::rushCharge(false) +
            SELF::taxCharge(false) -
            SELF::appliedCouponDiscount();
        return $isConvert ? SELF::convertPrice($amount) : $amount;
    }

    public static function appliedCoupon()
    {
        return Session::has('APPLIEDCOUPON') ? Session::get('APPLIEDCOUPON') : null;
    }

    public static function appliedCouponClear()
    {
        if (Session::has('APPLIEDCOUPON')) {
            Session::forget('APPLIEDCOUPON');
        }
    }
    public static function appliedCouponDiscount($default = false)
    {
        $coupon = self::appliedCoupon();
        if (!$coupon) {
            return 0;
        }

        $amount = 0;
        if ($coupon->type == 0) {
            $amount = (self::cartTotal(false) * $coupon->price) / 100;
        } else {
            $amount = $coupon->price;
        }
        if ($default) {
            return SELF::convertPrice((float)$amount);
        }
        return (float)$amount;
    }

    public static function rushShippingClear()
    {
        if (Session::has('RUSHSHIPPING')) {
            Session::forget('RUSHSHIPPING');
        }
    }

    public static function activateRushShipping()
    {
        Session::put('RUSHSHIPPING', true);
    }
    public static function isActiveRushShipping()
    {
        return Session::has('RUSHSHIPPING') ? 1 : 0;
    }

    public static function getCountries()
    {
        return Cache::rememberForever('countries', function () {
            return DB::table('countries')->get();
        });
    }

    public static function getStates($countryId)
    {
        return Cache::rememberForever('states', function () use ($countryId) {
            return DB::table('states')
                ->where('country_id', $countryId)
                ->get();
        });
    }

    public static function categories()
    {

        return Cache::rememberForever('categories', function () {
            return Category::with('subs')
                ->withCount('subs')
                ->where('status', 1)->get();
        });
    }

    public static function manageFolder($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    public static function priceStructure()
    {
        return Cache::rememberForever('price_structure', function () {
            return PriceStructure::first();
        });
    }

    public static function getPriceStructure($break_no)
    {
        return Self::priceStructure()
            ->breakPoint()
            ->where('break_no', $break_no)
            ->first();
    }

    public static function totalBreak()
    {
        $result = SELF::priceStructure();
        return $result ? $result->nob : 0;
    }

    public static function totalColor()
    {
        $result = SELF::priceStructure();
        return $result ? $result->noc : 0;
    }

    public static function normalDeliveryEstimation()
    {
        return date('l, M d,Y', strtotime('+ 10 days'));
    }

    public static function cartClear()
    {
        SELF::appliedCouponClear();
        $result = self::cartList();
        foreach ($result as $row) {
            $row->details()->delete();
            $row->delete();
        }
        return true;
    }

    public static function getOrderStatus()
    {
        return Cache::rememberForever('order_status_list', function () {
            return (new Order())->getDefinedOrderStatusList();
        });
    }

    public static function getTotalSalesAllTime()
    {
        return Order::where('order_status', '=', SELF::ORDER_COMPLETED)
            ->get()->count();
    }

    public static function getTotalSalesInLastThirtyDays()
    {
        return Order::where('order_status', '=', SELF::ORDER_COMPLETED)
            ->where('created_at', '>', Carbon::now()
                ->subDays(30))
            ->get()->count();
    }

    public static function getStatusLabelByStatus($status)
    {
        if ($status == null) {
            return __('All Orders');
        }

        return Cache::rememberForever('states_label_' . $status, function () use ($status) {
            return (new Order())
                ->getStatusLabelByStatus($status);
        });
    }

    public static function getPaymentStatusLabelByStatus($status)
    {
        return Cache::rememberForever('payment_states_label_' . $status, function () use ($status) {
            return (new Order())
                ->getPaymentStatusLabelByStatus($status);
        });
    }

    public static function setBackHistoryUrl($url)
    {
        Session::put('BACK_URL', $url);
    }

    public static function getBackHistoryUrl()
    {
        return Session::get('BACK_URL') ? Session::get('BACK_URL') : null;
    }

    public static function clearBackHistoryUrl()
    {
        return Session::pull('BACK_URL');
    }

    public static function getAddresses()
    {
        return Addresses::where('user_id', Auth::user()->id)
            ->get();
    }

    public static function getFixCost()
    {
        $result = self::priceStructure();
        return [$result->fixScreenCost, $result->fixCostPerName, $result->fixFrontBackPerColor];
    }

    public static function getNameFixCost($default = false)
    {
        $result = self::priceStructure();
        $price = $result->fixCostPerName ?? 0;
        if ($default) {
            return SELF::convertPrice($price);
        }

        return $price;
    }
    /**
     * @this method is used for calculating product price
     * @params category_id, qty, front no of color,  back no of color and names
     * @return calculated price
     */
    public static function calculateProductPrice($category_id, $cap_quantity, $front_noc, $back_noc = 0, $namesQty = 0)
    {
        list($fixScreenCost, $fixCostPerName, $fixFrontBackPerColor)
            = self::getFixCost();


        /* Cap Cost based on quantity and ink colours */
        $capCost = self::getCapCostBasedOnInkAndNoc($category_id, $cap_quantity, $front_noc);

        /* Different Side 2 */
        $screenCost = 0;
        if ($back_noc > 0) {
            $screenCost = ($fixScreenCost * $back_noc) / $cap_quantity;
        }

        /* front and back printing */
        $frontBackPrinting = self::getFrontAndBackPrintingPrice($front_noc, $back_noc, $fixFrontBackPerColor);

        /* names */
        $totalNameCost = self::calculateTotalNameCost($namesQty, $fixCostPerName);

        /* Cost Per cap */
        $totalCostPerCap = $capCost + $screenCost + $frontBackPrinting;

        $totalCapCost = $totalCostPerCap * $cap_quantity;
        return $totalCapCost +  $totalNameCost;
    }

    public static function calculateTotalNameCost($namesQty, $price = 3)
    {
        return $namesQty * $price;
    }

    private static function getFrontAndBackPrintingPrice($frontNoc, $backNoc, $price = "0.75")
    {
        return ($frontNoc * $price) + ($backNoc * $price);
    }

    private static function getCapCostBasedOnInkAndNoc($category_id, $cap_quantity, $front_noc)
    {
        $amount = 0;
        $break = self::getMatchedBreak($cap_quantity);
        if (!$break) {
            return $amount;
        }

        $priceStruct = self::priceStructure();
        $result = $priceStruct->printCharge()
            ->where('category_id', $category_id)
            ->where('color_no', $front_noc)
            ->where('break_no', $break->break_no)
            ->first();
        return $result ? $result->break_price : $amount;
    }

    private static function getMatchedBreak($cap_quantity)
    {
        $priceStruct = self::priceStructure();

        return $priceStruct->breakPoint()
            ->where('break_qty', '<=', $cap_quantity)
            ->orderBy('break_qty', 'desc')
            ->first();
    }

    /**
     * This method is used for recalculate cart value
     */
    public static function reCalculateCart()
    {
        $fixCostPerName = self::getNameFixCost();

        foreach (self::cartList() as  $carts) {
            $totalNames = 0;
            foreach ($carts->details as $row) {

                $totalNames += $carts->names ? array_sum(array_column($carts->names, 'quantity')) : 0;
                if ($carts->product) {
                    $price = self::calculateProductPrice(
                        $carts->product->category_id,
                        $row->total_qty,
                        $carts->noc,
                        $carts->noc_back,
                        0
                    );

                    $row->update(['total_price' => (float)$price / $row->total_qty]);
                }
            }

            $carts->update(['name_cost' => self::calculateTotalNameCost($totalNames, $fixCostPerName)]);
        }
    }

    public static function pageStatus($slug)
    {
        $page = DB::table('pages')->where('slug', $slug)->first();
        return $page ;
    }
}
