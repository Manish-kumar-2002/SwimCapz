<?php

namespace App\Http\Controllers\Front;

use App\{
    Models\Cart,
    Models\Coupon
};
use App\Helpers\Helper;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CouponController extends FrontBaseController
{
    const COUPON_NOT_AVAIL = "Coupon not available.";

    public function coupon()
    {
        $gs = $this->gs;
        $code = $_GET['code'];
        $total = (float)preg_replace('/[^0-9\.]/ui', '', $_GET['total']);;
        $fnd = Coupon::where('code', '=', $code)->get()->count();
        $coupon = Coupon::where('code', '=', $code)->first();

        $cart = Session::get('cart');
        foreach ($cart->items as $item) {
            $product = Product::findOrFail($item['item']['id']);

            if ($coupon->coupon_type == 'category') {

                if ($product->category_id == $coupon->category) {
                    $coupon_check_type[] = 1;
                } else {

                    $coupon_check_type[] = 0;
                }
            } elseif ($coupon->coupon_type == 'sub_category') {
                if ($product->subcategory_id == $coupon->sub_category) {
                    $coupon_check_type[] = 1;
                } else {
                    $coupon_check_type[] = 0;
                }
            } elseif ($coupon->coupon_type == 'child_category') {
                if ($product->childcategory_id == $coupon->child_category) {
                    $coupon_check_type[] = 1;
                } else {
                    $coupon_check_type[] = 0;
                }
            } else {

                $coupon_check_type[] = 0;
            }
        }



        if (in_array(0, $coupon_check_type)) {
            return response()->json(0);
        }




        if ($fnd < 1) {
            return response()->json(0);
        } else {
            $coupon = Coupon::where('code', '=', $code)->first();
            $curr = $this->curr;
            if ($coupon->times != null) {
                if ($coupon->times == "0") {
                    return response()->json(0);
                }
            }
            $today = date('Y-m-d');
            $from = date('Y-m-d', strtotime($coupon->start_date));
            $to = date('Y-m-d', strtotime($coupon->end_date));
            if ($from <= $today && $to >= $today) {
                if ($coupon->status == 1) {
                    $oldCart = Session::has('cart') ? Session::get('cart') : null;
                    $val = Session::has('already') ? Session::get('already') : null;
                    if ($val == $code) {
                        return response()->json(2);
                    }
                    $cart = new Cart($oldCart);
                    if ($coupon->type == 0) {
                        if ($coupon->price >= $total) {
                            return response()->json(3);
                        }
                        Session::put('already', $code);
                        $coupon->price = (int)$coupon->price;
                        $val = $total / 100;
                        $sub = $val * $coupon->price;
                        $total = $total - $sub;
                        $data[0] = \PriceHelper::showCurrencyPrice($total);
                        $data[1] = $code;
                        $data[2] = round($sub, 2);
                        Session::put('coupon', $data[2]);
                        Session::put('coupon_code', $code);
                        Session::put('coupon_id', $coupon->id);
                        Session::put('coupon_total', $data[0]);
                        $data[3] = $coupon->id;
                        $data[4] = $coupon->price . "%";
                        $data[5] = 1;

                        Session::put('coupon_percentage', $data[4]);

                        return response()->json($data);
                    } else {
                        if ($coupon->price >= $total) {
                            return response()->json(3);
                        }
                        Session::put('already', $code);
                        $total = $total - round($coupon->price * $curr->value, 2);
                        $data[0] = $total;
                        $data[1] = $code;
                        $data[2] = $coupon->price * $curr->value;
                        Session::put('coupon', $data[2]);
                        Session::put('coupon_code', $code);
                        Session::put('coupon_id', $coupon->id);
                        Session::put('coupon_total', $data[0]);
                        $data[3] = $coupon->id;
                        $data[4] = \PriceHelper::showCurrencyPrice($data[2]);
                        $data[0] = \PriceHelper::showCurrencyPrice($data[0]);
                        Session::put('coupon_percentage', 0);
                        $data[5] = 1;
                        return response()->json($data);
                    }
                } else {
                    return response()->json(0);
                }
            } else {
                return response()->json(0);
            }
        }
    }

    public function couponcheck()
    {
        $gs = $this->gs;
        $code = $_GET['code'];
        $coupon = Coupon::where('code', '=', $code)->first();

        if (!$coupon) {
            return response()->json(0);
        }

        $cart = Session::get('cart');
        $discount_items = [];
        foreach ($cart->items as $key => $item) {
            $product = Product::findOrFail($item['item']['id']);

            if ($coupon->coupon_type == 'category') {
                if ($product->category_id == $coupon->category) {
                    $discount_items[] = $key;
                }
            } elseif ($coupon->coupon_type == 'sub_category') {
                if ($product->sub_category == $coupon->sub_category) {
                    $discount_items[] = $key;
                }
            } elseif ($coupon->coupon_type == 'child_category') {

                if ($product->child_category == $coupon->child_category) {
                    $discount_items[] = $key;
                }
            }
        }


        if (count($discount_items) == 0) {
            return 0;
        }

        //dd($discount_items);
        $main_discount_price = 0;
        foreach ($cart->items as $ckey => $cproduct) {
            if (in_array($ckey, $discount_items)) {
                $main_discount_price += $cproduct['price'];
            }
        }


        $total = (float)preg_replace('/[^0-9\.]/ui', '', $main_discount_price);

        $fnd = Coupon::where('code', '=', $code)->get()->count();
        if (Session::has('is_tax')) {
            $xtotal = ($total * Session::get('is_tax')) / 100;
            $total = $total + $xtotal;
        }
        if ($fnd < 1) {
            return response()->json(0);
        } else {
            $coupon = Coupon::where('code', '=', $code)->first();
            $curr = $this->curr;
            if ($coupon->times != null) {
                if ($coupon->times == "0") {
                    return response()->json(0);
                }
            }
            $today = date('Y-m-d');
            $from = date('Y-m-d', strtotime($coupon->start_date));
            $to = date('Y-m-d', strtotime($coupon->end_date));
            if ($from <= $today && $to >= $today) {
                if ($coupon->status == 1) {
                    $oldCart = Session::has('cart') ? Session::get('cart') : null;
                    $val = Session::has('already') ? Session::get('already') : null;
                    if ($val == $code) {
                        return response()->json(2);
                    }
                    $cart = new Cart($oldCart);
                    if ($coupon->type == 0) {

                        if ($coupon->price >= $total) {
                            return response()->json(3);
                        }
                        Session::put('already', $code);
                        $coupon->price = (int)$coupon->price;

                        $oldCart = Session::get('cart');
                        $cart = new Cart($oldCart);

                        $total = $total - $_GET['shipping_cost'];

                        $val = $total / 100;
                        $sub = $val * $coupon->price;
                        $total = $total - $sub;
                        $total = $total + $_GET['shipping_cost'];
                        $data[0] = \PriceHelper::showCurrencyPrice($total);
                        $data[1] = $code;
                        $data[2] = round($sub, 2);

                        Session::put('coupon', $data[2]);
                        Session::put('coupon_code', $code);
                        Session::put('coupon_id', $coupon->id);
                        Session::put('coupon_total1', round($total, 2));
                        Session::forget('coupon_total');

                        $data[3] = $coupon->id;
                        $data[4] = $coupon->price . "%";
                        $data[5] = 1;
                        $data[6] = round($total, 2);
                        Session::put('coupon_percentage', $data[4]);

                        return response()->json($data);
                    } else {

                        if ($coupon->price >= $total) {
                            return response()->json(3);
                        }
                        Session::put('already', $code);
                        $total = $total - round($coupon->price * $curr->value, 2);
                        $data[0] = $total;
                        $data[1] = $code;
                        $data[2] = $coupon->price * $curr->value;
                        $data[3] = $coupon->id;
                        $data[4] = \PriceHelper::showCurrencyPrice($data[2]);
                        $data[0] = \PriceHelper::showCurrencyPrice($data[0]);
                        Session::put('coupon', $data[2]);
                        Session::put('coupon_code', $code);
                        Session::put('coupon_id', $coupon->id);
                        Session::put('coupon_total1', round($total, 2));
                        Session::forget('coupon_total');
                        $data[1] = $code;
                        $data[2] = round($coupon->price * $curr->value, 2);
                        $data[3] = $coupon->id;
                        $data[5] = 1;
                        $data[6] = round($total, 2);
                        Session::put('coupon_percentage', $data[4]);

                        return response()->json($data);
                    }
                } else {
                    return response()->json(0);
                }
            } else {
                return response()->json(0);
            }
        }
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required'
        ]);

        if (Session::get('APPLIEDCOUPON')) {

            $request->validate([
                'validate' => 'required'
            ], ['validate.required' => 'Coupon already applied.']);
        }

        $coupon = coupon::where([
            'code'      => $request->coupon_code,
            'status'    => 1
        ])->first();

        if (!$coupon) {

            $request->validate([
                'validate' => 'required'
            ], ['validate.required' => SELF::COUPON_NOT_AVAIL]);
        }

        /* coupon expire */
        if (!(strtotime($coupon->start_date) <= strtotime(date('Y-m-d')) &&
            strtotime($coupon->end_date) >= strtotime(date('Y-m-d')))) {

            $request->validate([
                'validate' => 'required'
            ], ['validate.required' => __('Coupon is expired or invalid.')]);
        }

        /* total applied */
        $appliedCoupons = Order::where('coupon_code', $coupon->code)
            ->where('user_id', Auth::user()->id)
            ->whereIn('order_status', [
                Order::ORDER_SUCCESS,
                Order::ORDER_ACCEPTED,
                Order::ORDER_SHIPPED,
                Order::ORDER_COMPLETED
            ])->count();

        if ((int)$coupon->times && $appliedCoupons >= $coupon->times) {
            $request->validate([
                'validate' => 'required'
            ], ['validate.required' => SELF::COUPON_NOT_AVAIL]);
        }

        /* check user can apply */
        $canUserApply = $this->canUserApplyThisCoupon($coupon);
        if (!$canUserApply) {

            $request->validate([
                'validate' => 'required'
            ], ['validate.required' => __("Invalid coupon.")]);
        }

        Session::put('APPLIEDCOUPON', $coupon, 2800);
        return response()->json(array('message' => 'Coupon applied successfully.'));
    }

    private function canUserApplyThisCoupon($coupon)
    {
        $canUserApply = false;
        $list = Helper::cartList();
        foreach ($list as $cart) {
            if ($coupon->product == 0) {
                $canUserApply = true;
                break;
            }
            if ($cart->product_id == $coupon->product) {
                $canUserApply = true;
                break;
            }
        }

        return $canUserApply;

        // $canUserApply = false;
        // $list = Helper::cartList();
        // $cart_price = Helper::cartTotal();

        // foreach ($list as $cart) {

        //     if ($coupon->product == 0) {
        //         if ($cart_price >= $coupon->price) {
        //             $canUserApply = true;
        //             break;
        //         }
        //     }
        //     else{
        //         if ($cart->product_id == $coupon->product && $cart_price >= $coupon->price) {
        //             $canUserApply = true;
        //             break;
        //         }
        //     }

        // }
        // return $canUserApply;
    }
    public function appliedCouponRemove()
    {
        Session::forget('APPLIEDCOUPON');
        return response()->json(array('message' => 'Coupon removed successfully.'));
    }


    public function availableCoupons()
    {
        $coupons = Coupon::where('status', 1)->get();
        $availableCoupons = [];
        foreach ($coupons as $coupon) {

            $appliedCoupons = Order::where('coupon_code', $coupon->code)
                ->where('user_id', Auth::user()->id)
                ->whereIn('order_status', [
                    Order::ORDER_SUCCESS,
                    Order::ORDER_ACCEPTED,
                    Order::ORDER_SHIPPED,
                    Order::ORDER_COMPLETED
                ])->count();

            $canUserApply = $this->canUserApplyThisCoupon($coupon);

            if (!(strtotime($coupon->start_date) <= strtotime(date('Y-m-d')) &&
                strtotime($coupon->end_date) >= strtotime(date('Y-m-d'))) || 
                (int)$coupon->times && $appliedCoupons >= $coupon->times || 
                !$canUserApply) {
                continue;
            } else {
                $availableCoupons[] = $coupon;
            }
        }
        return $availableCoupons;
    }
}
