<?php

namespace App\Http\Controllers\Auth\User;

use App\Classes\SendMail;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'userLogout']]);
    }

    public function login(Request $request)
    {
        $rules = [
            'email'   => 'required|email',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            if (Auth::user()->email_verified == 'No') {
                Auth::logout();
                return response()->json(array('errors' => [0 => __('Your Email is not verified, Please verify you email .')]));
            }

            if (Auth::user()->ban == 1) {
                Auth::logout();
                return response()->json(array('errors' => [0 => __('Your account has been banned.')]));
            }

            $redirectRoute = redirect()->intended(route('user-dashboard'))->getTargetUrl();
            if (Helper::getBackHistoryUrl()) {
                $redirectRoute = Helper::getBackHistoryUrl();
                Helper::clearBackHistoryUrl();
            }
            if ($request->has('model_login')) {
                // $this->sendLoginMail($request->email);
                return response()->json(array('success' => true , 'user_id' => Auth::user()->id));
            } else {
                // $this->sendLoginMail($request->email);
                return response()->json($redirectRoute);
            }
        }

        return response()->json(array('errors' => [
            0 => __('Please enter a valid email address and password.')
        ]));
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    private function sendLoginMail($email){
        $to = $email;
				$subject = 'Login with SwimCapz.';
				$msg="You are Login Successfully";
				//Sending Email To Customer
				$template = 'emails.email_verification_link';
				$mailer = new SendMail();
				$mailer->sendCustomMail($template,$msg,$to,$subject);
    }
}
