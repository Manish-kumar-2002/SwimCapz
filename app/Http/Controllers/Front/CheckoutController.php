<?php

namespace App\Http\Controllers\Front;

use App\{
    Models\Cart,
    Models\PaymentGateway
};
use App\Helpers\Helper;
use App\Helpers\PriceHelper;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends FrontBaseController
{
    public function loadpayment($slug1, $slug2)
    {
        $curr = $this->curr;
        $payment = $slug1;
        $pay_id = $slug2;
        $gateway = '';
        if ($pay_id != 0) {
            $gateway = PaymentGateway::findOrFail($pay_id);
        }
        return view('load.payment', compact('payment', 'pay_id', 'gateway', 'curr'));
    }

    public function checkout()
    {

        if (!Helper::cartCheck()) {
            return redirect()->route('front.cart')
                ->with('error', __("You don't have any product to checkout."));
        }

        if(!Auth::check()) {
            Helper::setBackHistoryUrl(route('front.checkout'));
            return redirect(route('user.login'))
                        ->with('error', __('Please login, before checkout.'));
        }
        
        Helper::rushShippingClear();

        $gateways =  PaymentGateway::scopeHasGateway($this->curr->id);
        $pickups =  DB::table('pickups')->get();
        $paystack = PaymentGateway::whereKeyword('paystack')->first();
        $paystackData = $paystack->convertAutoData();

        $shipping_data  = DB::table('shippings')->whereUserId(0)->get();
        $package_data  = DB::table('packages')->whereUserId(0)->get();
        return view('frontend.checkout', [
            'products' => [] ,
            'totalPrice' => 100,
            'pickups' => $pickups,
            'totalQty' => 200,
            'gateways' => $gateways,
            'shipping_cost' => 0,
            'digital' => 1,
            'curr' => [],
            'shipping_data' => $shipping_data,
            'package_data' =>$package_data ,
            'paystack' => $paystackData,
            'checked'=>1
        ]);
    }

    public function loadAdresses(Request $request)
    {
        $content=[];
        $content['selected_id']=$request->selected_id ?? null;
        if ($request->has('shipping')) {
            return view('frontend._assets._shipping_address', $content);
        } else {
            return view('frontend._assets._billing_address', $content);
        }

    }

    public function getState($country_id)
    {

        $states = State::where('country_id', $country_id)->get();

        if (Auth::user()) {
            $user_state = Auth::user()->state;
        } else {
            $user_state = 0;
        }


        $html_states = '<option value="" > Select State </option>';
        foreach ($states as $state) {
            if ($state->id == $user_state) {
                $check = 'selected';
            } else {

                $check = '';
            }
            $html_states .= '<option
                value="' . $state->id . '"
                rel="' . $state->country->id . '" ' . $check . ' >' . $state->state . '</option>';
        }

        return response()->json(["data" => $html_states, "state" => $user_state]);
    }
}
