<?php

namespace App\Traits;

use App\Jobs\EmailHandle;

trait SendEmail {
    
    /**
     * This function is used to handle order request email
     *
     */
    public function orderReceivedNotification($order)
    {
        $maildata = [
            'to' => $order->email,
            'subject' => 'Your order '.$order->order_number.' is Received!',
            'body' => "Hello ".$order->name.","."\n
            Your order request is received.",
        ];

        dispatch(new EmailHandle($maildata));
    }
    
    /**
     * This function is used to handle after user create
     *
     */
    public function userAccountCreated($user)
    {
        $maildata = [
            'to' => $user->email,
            'subject' => 'Account Created',
            'body' => "Hello ".$user->name.","."\n
            Thank you for signup.",
        ];

        dispatch(new EmailHandle($maildata));
    }

    /**
     * This function is used to handle after user create
     *
     */
    public function orderMarkAsCancelledNotification($order)
    {
        $maildata = [
            'to' => $order->email,
            'subject' => 'Your order '.$order->order_number.' is Cancelled!',
            'body' => "Hello ".$order->name.","."\n
            We are sorry for the inconvenience caused. We are looking forward to your next visit.",
        ];

        dispatch(new EmailHandle($maildata));
    }

    public function orderMarkAsAcceptedNotification($order)
    {
        $maildata = [
            'to' => $order->email,
            'subject' => 'Your order '.$order->order_number.' is Accepted!',
            'body' => "Hello ".$order->name.","."\n
            Your order is accepted.",
        ];

        dispatch(new EmailHandle($maildata));
    }
    public function orderMarkAsRejectedNotification($order)
    {
        $maildata = [
            'to' => $order->email,
            'subject' => 'Your order '.$order->order_number.' is Declined!',
            'body' => "Hello ".$order->name.","."\n
            We are sorry for the inconvenience caused. We are looking forward to your next visit.",
        ];

        dispatch(new EmailHandle($maildata));
    
    }

    /**
     * This function is used to handle after user create
     *
     */
    public function orderCompletedNotifications($order)
    {
        $maildata = [
            'to' => $order->email,
            'subject' => 'Your order '.$order->order_number.' is Completed!',
            'body' => "Hello ".$order->name.","."\n
            Thank you for shopping with us. We are looking forward to your next visit.",
        ];
        
        dispatch(new EmailHandle($maildata));
    }

}