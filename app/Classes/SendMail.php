<?php
/**
 * Created by PhpStorm.
 * User: ShaOn
 * Date: 11/29/2018
 * Time: 12:49 AM
 */

namespace App\Classes;

use App\{
    Models\Order,
    Models\EmailTemplate,
    Models\Generalsetting
};
use PDF;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class SendMail
{

    public $mail;
    public $gs;

    public function __construct()
    {
        $this->gs = Generalsetting::findOrFail(1);
    }

    public function sendCustomMail($template,$msg,$to,$subject)
    {
        try{
            Mail::send($template, ['msg' => $msg], function ($message) use ($to, $subject) {
                $message->to($to)
                ->subject($subject);
            });
        }catch (Exception $e){

        }

        return true;
    }
    public function sendAutoMail(array $mailData)
    {

        $temp = EmailTemplate::where('email_type','=',$mailData['type'])->first();

        try{

            $body = preg_replace("/{customer_name}/", $mailData['cname'] ,$temp->email_body);
            $body = preg_replace("/{order_amount}/", $mailData['oamount'] ,$body);
            $body = preg_replace("/{admin_name}/", $mailData['aname'] ,$body);
            $body = preg_replace("/{admin_email}/", $mailData['aemail'] ,$body);
            $body = preg_replace("/{order_number}/", $mailData['onumber'] ,$body);
            $body = preg_replace("/{website_title}/", $this->gs->title ,$body);
            $subject = $temp->email_subject;
            $to = $mailData['to'];
            Mail::send([], [], function ($message) use ($to, $subject, $body) {
                $message->to($to)
                    ->subject($subject)
                    ->setBody($body, 'text/html');
            });

        }
        catch (Exception $e){

        }

        return true;

    }

}