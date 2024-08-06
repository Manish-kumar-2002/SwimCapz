<?php

namespace App\Models\TraitModel;

use App\Classes\GeniusMailer;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Traits\SendEmail;
use Exception;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;

trait OrderTrait
{
    use SendEmail;

    public function getCancelNoteAttribute() {
        if ($this->order_status != Order::ORDER_REJECTED &&
            $this->order_status != Order::ORDER_CANCELLED &&
                $this->payment_status != Order::PAYMENT_SUCCESS) {
            return null;
        }

        $track=$this->orderTrack()->latest()->first();
        return $track ? $track->text : '';
    }

    public function scopeOnlyRunningOrders($query) {
        return $query->whereIn('order_status', [
            Order::ORDER_SUCCESS,
            Order::ORDER_ACCEPTED,
            Order::ORDER_SHIPPED,
            Order::ORDER_COMPLETED
        ]);
    }
    public function getDefinedOrderStatusList()
    {
        return [
            Order::ORDER_SUCCESS => [
                'text'      =>Order::ORDER_SUCCESS_MSG,
                'icon'      =>'fas fa-clock',
                'bgColor'   =>'bg2',
                'total'     =>$this->getOrderCount(Order::ORDER_SUCCESS),
                'url'       =>route('admin-orders-awating-approval', Order::ORDER_SUCCESS)
            ],
            // Order::ORDER_ACCEPTED => [
            //     'text'      =>Order::ORDER_ACCEPTED_MSG,
            //     'icon'      =>'fas fa-check-circle',
            //     'bgColor'   =>'bg3',
            //     'total'     =>$this->getOrderCount(Order::ORDER_ACCEPTED),
            //     'url'       =>route('admin-orders-accepted', Order::ORDER_ACCEPTED)
            // ],
            // Order::ORDER_REJECTED => [
            //     'text'      =>Order::ORDER_REJECTED_MSG,
            //     'icon'      =>'fa fa-ban',
            //     'bgColor'   =>'bg4',
            //     'total'     =>$this->getOrderCount(Order::ORDER_REJECTED),
            //     'url'       =>route('admin-orders-rejected', Order::ORDER_REJECTED) 
            // ],
            Order::ORDER_SHIPPED => [
                'text'      =>Order::ORDER_SHIPPED_MSG,
                'icon'      =>'fas fa-truck',
                'bgColor'   =>'bg5',
                'total'     =>$this->getOrderCount(Order::ORDER_SHIPPED),
                'url'       =>route('admin-orders-shipped', Order::ORDER_SHIPPED)
            ],
            Order::ORDER_COMPLETED => [
                'text'      =>Order::ORDER_COMPLETED_MSG,
                'icon'      =>'fa fa-check-square',
                'bgColor'   =>'bg6',
                'total'     =>$this->getOrderCount(Order::ORDER_COMPLETED),
                'url'       =>route('admin-orders-completed', Order::ORDER_COMPLETED)
            ],
            Order::ORDER_CANCELLED => [
                'text'      =>Order::ORDER_CANCELLED_MSG,
                'icon'      =>'fas fa-times-circle',
                'bgColor'   =>'bg7',
                'total'     =>$this->getOrderCount(Order::ORDER_CANCELLED),
                'url'       =>route('admin-orders-cancelled', Order::ORDER_CANCELLED)
            ]
        ];
    }

    public function orderMarkAsCompleted()
    {
        $this->orderCompletedNotifications($this);
        $this->update(['order_status' =>  Order::ORDER_COMPLETED]);
    }

    public function orderMarkAsRejected()
    {
        DB::beginTransaction();
        try {

            $this->refundInitiate( Order::ORDER_REJECTED);
            DB::commit();

            $this->orderMarkAsRejectedNotification($this);

        }catch(Exception $e) {
            DB::rollback();
        }
        
    }

    public function orderMarkAsCancelled()
    {
        DB::beginTransaction();
        try {

            $this->refundInitiate(Order::ORDER_CANCELLED);
            DB::commit();
            $this->orderMarkAsCancelledNotification($this);

        }catch(Exception $e) {
            DB::rollback();
        }
        
    }

    public function orderReceived()
    {
        $this->orderReceivedNotification($this);
    }
    public function orderMarkAsAccepted($request)
    {
        $this->update([
            'order_status'      =>$request->status
        ]);

        $this->orderMarkAsAcceptedNotification($this);
        
    }

    public function refundInitiate($orderStatus) {
         
        $update=[];
        if ($this->stripe_id && $this->stripe_status == "succeeded") {

            $stripe = new StripeClient(env('STRIPE_SECRET'));
            $response=$stripe->refunds->create(['charge' => $this->stripe_id]);
    
            if($response && $response['status'] == 'succeeded') {

                $update['refund_id' ]    =$response['id'];
                $update['refund_amount' ]=$response['amount'];
                $update['refund_date' ]  =date('Y-m-d H:i:s', $response['created']);
                $update['payment_status' ] =Order::PAYMENT_REFUND;

            }

        }
        
        $update['order_status' ]    =$orderStatus;
        $this->update($update);
    }

    public function maintainTrack($request)
    {
        if ($request->track_text) {

            $title = ucwords($this->getStatusLabelByStatus($request->status));

            $data = new OrderTrack();
            $data->order_id = $this->id;
            $data->title = $title;
            $data->text = $request->track_text;
            $data->save();
        }

    }

    public function scopePendingOrders($query)
    {
        return $query->where('order_status', Order::ORDER_PENDING)
            ->get();
    }

    public function scopeSuccessOrders($query)
    {
        return $query->where('order_status', Order::ORDER_SUCCESS)
            ->get();
    }

    public function scopeAcceptedOrders($query)
    {
        return $query->where('order_status', Order::ORDER_ACCEPTED)
            ->get();
    }
    public function scopeRejectedOrders($query)
    {
        return $query->where('order_status', Order::ORDER_REJECTED)
        ->get();
    }
    public function scopeShippedOrders($query)
    {
        return $query->where('order_status', Order::ORDER_SHIPPED)
        ->get();
    }
    public function scopeCompletedOrders($query)
    {
        return $query->where('order_status', Order::ORDER_COMPLETED)
        ->get();
    }
    public function scopeRemovePending($query)
    {
        return $query->where('order_status', '!=' ,Order::ORDER_PENDING);
    }

    public function getOrderCustomStatusAttribute()
    {
        return array_key_exists($this->order_status, $this->orderStatus) ? 
            $this->orderStatus[$this->order_status] : 'Sorry, Status not found.';
       
    }
    public function getPaymentCustomStatusAttribute()
    {
        return array_key_exists($this->payment_status, $this->orderPaymentStatus) ? 
            $this->orderPaymentStatus[$this->payment_status] : 'Not Found';
       
    }

    public function orderStatusChange($request) {
        $input=$request->all();
        $this->maintainTrack($request);

        if ($input['status'] == Order::ORDER_COMPLETED) {
            $this->orderMarkAsCompleted();
        } elseif ($input['status'] == Order::ORDER_REJECTED) {
            $this->orderMarkAsRejected($request);
        } elseif ($input['status'] == Order::ORDER_CANCELLED) {
            $this->orderMarkAsCancelled($request);
        } elseif ($input['status'] == Order::ORDER_ACCEPTED) {
            $this->orderMarkAsAccepted($request);
        } else {
            $this->update([
                'order_status'      =>$input['status']
            ]);
        }
    }

    public function getStatusLabelByStatus($status)
    {
        return array_key_exists($status, $this->orderStatus) ? 
            $this->orderStatus[$status] : 'Sorry, Status not found.';
       
    }

    public function getPaymentStatusLabelByStatus($status)
    {
        return array_key_exists($status, $this->orderPaymentStatus) ?
            $this->orderPaymentStatus[$status] : 'Sorry, Status not found.';
    }

    protected function setOrderStatus()
    {
        $this->orderStatus=array(
            Order::ORDER_PENDING     =>Order::ORDER_PENDING_MSG,
            Order::ORDER_SUCCESS     =>Order::ORDER_SUCCESS_MSG,
            Order::ORDER_ACCEPTED    =>Order::ORDER_ACCEPTED_MSG,
            Order::ORDER_REJECTED    =>Order::ORDER_REJECTED_MSG,
            Order::ORDER_SHIPPED     =>Order::ORDER_SHIPPED_MSG,
            Order::ORDER_COMPLETED   =>Order::ORDER_COMPLETED_MSG,
            Order::ORDER_CANCELLED   =>Order::ORDER_CANCELLED_MSG
        );
    }
    protected function setOrderPaymentStatus()
    {
        $this->orderPaymentStatus=array(
            Order::PAYMENT_PENDING     =>Order::PAYMENT_PENDING_MSG,
            Order::PAYMENT_SUCCESS     =>Order::PAYMENT_SUCCESS_MSG,
            Order::PAYMENT_REFUND      =>Order::PAYMENT_REFUND_MSG
        );
    }
}