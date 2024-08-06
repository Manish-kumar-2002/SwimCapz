<?php

namespace App\Traits;

use App\Classes\SendMail;
use App\Models\Order;
use App\Models\TrackingWebhook;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

trait TrackingTrait
{
    private $apiKey = "asat_bf197970df714bc28b23487b8ce66119";
    /**
     *  make order on afterShip
     * */
    public function createTracking(Request $request)
    {
        // $rules=[
        //     'trackinsgNo' => 'required',
        //     'courierSdlug' => 'required',
        //     'id' => 'required',
        // ];
        // $v = Validator::make($request->all(), $rules);
        //     if($v->fails()) {
        //         return response()->json($v->getMessageBag()->first(),400);
        //     }
        $trackingNumber = $request->input("trackingNo");
        $courierSlug = $request->input("courierSlug");
        $id = $request->input("id");
        $url = 'https://api.aftership.com/tracking/2024-04/trackings';
        $data = [
            'tracking' => [
                'tracking_number' => $trackingNumber,
                'slug' => $courierSlug
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'as-api-key: ' . $this->apiKey,
            'Content-Type: application/json'
        ]);
        $response = curl_exec($ch);
        // dd($response);
        curl_close($ch);
        $order = Order::find($id);
        $order->trackingNo = $trackingNumber;
        $order->courierSlug = $courierSlug;
        $order->courierId = json_decode($response)->data->tracking->id;
        $order->order_status = 4;
        $order->save();
        $this->sendshippingMail($order->email, $order->name, $order->order_number);
        return $response;
    }

    /**
     *  read order on afterShip
     * */
    public function getTracking($trackingNumber, $courierSlug)
    {
        $url = "https://api.aftership.com/v4/trackings/{$courierSlug}/{$trackingNumber}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'aftership-api-key: ' . $this->apiKey,
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     *  webhook afterShip
     * */
    public function webhook($data)
    {
        TrackingWebhook::create(['response' => json_encode($data)]);

        return response()->json(['status' => 'success']);
    }

    private function sendshippingMail($email, $name, $id)
    {
        $to = $email;
        $subject = 'shipment with SwimCapz.';
        $msg = " 
                 Hello $name,</br>
                 </br>
                Good news! Your order $id has been shipped.</br>
                </br>
                You can track your shipment using the following tracking number: [Tracking Number]</br>
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
