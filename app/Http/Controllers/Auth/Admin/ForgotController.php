<?php

namespace App\Http\Controllers\Auth\Admin;

use App\{
    Models\Admin,
    Classes\GeniusMailer,
    Http\Controllers\Controller
};

use Illuminate\{
  Http\Request,
  Support\Facades\Hash
};
use Illuminate\Support\Facades\Mail;

class ForgotController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest:admin');
    }

    public function showForm()
    {
      return view('admin.forgot');
    }

public function forgot(Request $request)
{
    $input = $request->all();
    
    if (Admin::where('email', '=', $request->email)->count() > 0) {
        // user found
        $user = Admin::where('email', '=', $request->email)->first();
        $token = md5(time() . $user->name . $user->email);
        $input['email_token'] = $token;
        $user->update($input);

        $subject = "Reset Password Request";
        $resetLink = route('admin.change.token', $token);

        try {
            Mail::send('emails.reset_password', ['resetLink' => $resetLink , 'userName' => $user->name], function ($message) use ($request, $subject) {
                $message->to($request->email)
                        ->subject($subject);
            });
            
            return response()->json(__('An email has been sent to your registered email id. Check the inbox and click the reset link provided.'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Email sending error: ' . $e->getMessage());

            return response()->json(['error' => 'An error occurred while sending the email. Please try again later.']);
        }
    } else {
        // user not found
        return response()->json(['errors' => [0 => __('No account found with this email.')]]);
    }
}
  public function changeForgotPass(Request $request)
  {
      $token = $request->file_token;
      $user = Admin::where('email_token', $token)->first();
  
      if (!$user) {
          return response()->json(['errors' => [0 => __('Invalid Token.')]]);
      }
  
      $input = [];
  
      if ($request->newpass !== $request->renewpass) {
          return response()->json(['errors' => [0 => __('Confirm password does not match.')]]);
      }
  
      // Additional password validation (e.g., minimum length, complexity)
      if (strlen($request->newpass) < 8) {
          return response()->json(['errors' => [0 => __('Password should be at least 8 characters long.')]]);
      }
  
      // You can add more password complexity checks here
  
      $input['password'] = Hash::make($request->newpass);
  
      $user->email_token = null;
      $user->update($input);
  
      $msg = __('Successfully changed your password.') . '<a href="' . route('admin.login') . '"> ' . __('Login Now') . '</a>';
      return response()->json($msg);
  }
  
    public function showChangePassForm($token)
    {
      if($token){
        if( Admin::where('email_token', $token)->exists() ){
          return view('admin.changepass',compact('token'));  
        }
      }
    }

    public function changepass(Request $request)
    {
        $token = $request->file_token;
        $user =  Admin::where('email_token', $token)->first();

        if($user){
          if ($request->cpass){
            if (Hash::check($request->cpass, $user->password)){
                if ($request->newpass == $request->renewpass){
                    $input['password'] = Hash::make($request->newpass);
                }else{
                    return response()->json(array('errors' => [ 0 => __('Confirm password does not match.') ]));
                }
            }else{
                return response()->json(array('errors' => [ 0 => __('Current password does not match.') ]));
            }
        }

        $user->email_token = null;
        $user->update($input);

        $msg = __('Successfully changed your password.').'<a href="'.route('admin.login').'"> '.__('Login Now').'</a>';
        return response()->json($msg);
        }else{
          return response()->json(array('errors' => [ 0 => __('Invalid Token.') ]));
        }
    }
}