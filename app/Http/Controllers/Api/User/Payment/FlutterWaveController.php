<?php

namespace App\Http\Controllers\Api\User\Payment;


use App\Models\Deposit;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\Transaction;
use Illuminate\Support\Str;



class FlutterWaveController extends Controller
{
  public $public_key;
  private $secret_key;

  public function __construct()
    {
        
        $data = PaymentGateway::whereKeyword('flutterwave')->first();
        $paydata = $data->convertAutoData();
        $this->public_key = $paydata['public_key'];
        $this->secret_key = $paydata['secret_key'];
    }

    public function store(Request $request){

         if(!$request->has('deposit_number')){
             return response()->json(['status' => false, 'data' => [], 'error' => 'Invalid Request']);
        }

        $deposit_number = $request->deposit_number;
        $order = Deposit::where('deposit_number',$deposit_number)->first();
        $curr = Currency::where('name','=',$order->currency_code)->first();
        $settings = Generalsetting::findOrFail(1);
        $item_amount = $order->amount * $order->currency_value;

   
                $available_currency = array(
                    'CAD',
                    'EUR',
                    'GBP',
                    'USD',
                    'ZWD',
                    'NGN',
                    );
                    if(!in_array($curr->name,$available_currency))
                    {
                    return redirect()->back()->with('unsuccess','Invalid Currency For Flutter Wave.');
                    }

            $order['method'] = $request->method;
            $order->update();
                   

        // SET CURL

        $curl = curl_init();

        $currency = $curr->name;
        $txref = $order->deposit_number; // ensure you generate unique references per transaction.
        $PBFPubKey = $this->public_key; // get your public key from the dashboard.
        $redirect_url = action('Api\User\Payment\FlutterWaveController@notify');
        $payment_plan = ""; // this is only required for recurring payments.
        
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode([
            'amount' => $item_amount,
            'customer_email' => User::findOrFail($order->user_id)->email,
            'currency' => $currency,
            'txref' => $txref,
            'PBFPubKey' => $PBFPubKey,
            'redirect_url' => $redirect_url,
            'payment_plan' => $payment_plan
          ]),
          CURLOPT_HTTPHEADER => [
            "content-type: application/json",
            "cache-control: no-cache"
          ],
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        if($err){
          // there was an error contacting the rave API
          die('Curl returned error: ' . $err);
        }
        
        $transaction = json_decode($response);
        
        if(!$transaction->data && !$transaction->data->link){
          // there was an error from the API
          print_r('API returned error: ' . $transaction->message);
        }
        
        return redirect($transaction->data->link);

   
    }
   
   
   public function notify(Request $request){
   
    $input = $request->all();
    

    if (isset($input['txref'])) {
        $ref = $input['txref'];

       
        $query = array(
            "SECKEY" => $this->secret_key,
            "txref" => $ref
        );

        $data_string = json_encode($query);
                
        $ch = curl_init('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify');                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                              
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($ch);

        curl_close($ch);

        $resp = json_decode($response, true);
        
        if ($resp['status'] = "success") {
           $txn = $resp['data']['txid'];
           
            $paymentStatus = $resp['data']['status'];
            $chargeResponsecode = $resp['data']['chargecode'];
    
            if (($chargeResponsecode == "00" || $chargeResponsecode == "0") && ($paymentStatus == "successful")) {

            $order = Deposit::where('deposit_number',$resp['data']['txref'])->first();
            $order['txnid'] = $txn;
            $order['status'] = 1;
            $user = \App\Models\User::findOrFail($order->user_id);
            $user->balance = $user->balance + ($order->amount);
            $user->save();
            $order->update();
                              // store in transaction table
                    if ($order->status == 1) {
                        $transaction = new Transaction;
                        $transaction->txn_number = Str::random(3).substr(time(), 6,8).Str::random(3);
                        $transaction->user_id = $order->user_id;
                        $transaction->amount = $order->amount;
                        $transaction->user_id = $order->user_id;
                        $transaction->currency_sign = $order->currency;
                        $transaction->currency_code = $order->currency_code;
                        $transaction->currency_value= $order->currency_value;
                        $transaction->method = $order->method;
                        $transaction->txnid = $order->txnid;
                        $transaction->details = 'Payment Deposit';
                        $transaction->type = 'plus';
                        $transaction->save();
                    }
                

            return redirect(route('user.success',1));

        }

        else {
           
           return redirect(route('user.success',0));
        }

        
    }
        else {
           return redirect(route('user.success',0));
        }
    }

   }

}
