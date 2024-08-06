<?php

namespace App\Http\Controllers\Auth\User;

use App\{
    Models\User,
    Classes\GeniusMailer,
    Http\Controllers\Controller
};

use Illuminate\{
  Http\Request,
  Support\Facades\Hash
};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class ForgotController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index(){
        if (Session::has('language'))
            {
                $langg = DB::table('languages')->find(Session::get('language'));
            }
            else
            {
                $langg = DB::table('languages')->where('is_default','=',1)->first();
            }
      return view('frontend.forget',compact('langg'));

    }

    public function forgot(Request $request)
    {
      $input =  $request->all();
      if (User::where('email', '=', $request->email)->count() > 0) {
      // user found
        $admin = User::where('email', '=', $request->email)->first();
        $token = md5(time().$admin->name.$admin->email);
        $input['email_token'] = $token;
        $admin->update($input);
        $subject = "Reset password request";
        $resetLink = route('user.change.token', $token);

          Mail::send('emails.reset_password', [
            'resetLink' => $resetLink , 'userName' => $admin->name
          ], function ($message) use ($request, $subject) {
            $message->to($request->email)
                    ->subject($subject);
        });

        return response()->json(__('The verification link has been sent successfully! Please check your email.'));
      }
      else{
      // user not found
      return response()->json(array('errors' => [ 0 => __('No account found with this email.') ]));
      }
    }

    public function showChangePassForm($token)
    {
      if($token){
        if( User::where('email_token', $token)->exists() ){
          return view('user.changepass',compact('token'));
        }
      }
    }

    public function changepass(Request $request)
    { 
        $token = $request->token;
        $admin =  User::where('email_token', $token)->first();
        if($admin){
        
        if ($request->newpass == $request->renewpass){
            $admin->password = Hash::make($request->newpass);
            $admin->email_token = null;
            $admin->save();
        }else{
            return response()->json(
              array('errors' => [ 
                0 => __('Passwords do not match. Please enter the same password in both fields.') 
            ]));
        }
          
        $msg = __('Successfully changed your password.').'<a href="'.route('user.login').'"> '.__('login now').'</a>';
        return response()->json($msg);
        }else{
          return response()->json(array('errors' => [ 0 => __('Invalid Token.') ]));
        }
    }

}
