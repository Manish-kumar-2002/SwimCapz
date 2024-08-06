<?php

namespace App\Http\Controllers\Auth\User;

use App\{
	Models\User,
	Models\Notification,
	Classes\GeniusMailer,
	Classes\SendMail,
	Models\Generalsetting,
	Http\Controllers\Controller
};
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Auth;
use Validator;

class RegisterController extends Controller
{

    public function register(Request $request)
    {
    	$gs = Generalsetting::findOrFail(1);
    	
    	if($gs->is_capcha == 1)
        {
            $rules = [
                'g-recaptcha-response' => 'required|captcha'
            ];
            $customs = [
                'g-recaptcha-response.required' => "Please verify that you are not a robot.",
                'g-recaptcha-response.captcha' => "Captcha error! try again later or contact site admin..",
            ];
            $validator = Validator::make($request->all(), $rules, $customs);
            if ($validator->fails()) {
              	return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
        }

        //--- Validation Section
		$rules = [
			'name'     => 'required|max:100',
			'email'    => 'required|email|unique:users',
			'phone'    => 'required|numeric|unique:users',
			'password' => 'required|confirmed',
			'terms'    => 'required' // Corrected the 'terms' validation rule
		];
		
		$customMessages = [
			'name.required'      => 'The name field is required.',
			'name.max'           => 'The name field should not exceed 100 characters.',
			'email.required'     => 'The email field is required.',
			'email.email'        => 'Please enter a valid email address.',
			'email.unique'       => 'This email is already registered. Please log in or use a different email address.',
			'phone.required'     => 'The phone number field is required.',
			'phone.numeric'      => 'The phone number should be numeric.',
			'phone.unique'       => 'This phone number is already registered. Please use a different phone number.',
			'password.required'  => 'The password field is required.',
			'password.confirmed' => 'Passwords do not match. Please enter the same password in both fields.',
			'terms.required'     => 'You must accept the terms and conditions to proceed.',
		];

        $validator = Validator::make($request->all(), $rules,$customMessages);
        
        if ($validator->fails()) {
          	return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        
	        $user = new User;
	        $input = $request->all();
	        $input['password'] = bcrypt($request['password']);
	        $token = md5(time().$request->name.$request->email);
	        $input['verification_link'] = $token;
	        $input['affilate_code'] = md5($request->name.$request->email);
			$input['receive_occasion_news']=$request->has('receive_occasion_news') ? 1 : 0;

			$user->fill($input)->save();
			$user->userNotification();

			if($input['receive_occasion_news']) {
				$this->subscribeNews($request->email);
			}

	        if($gs->is_verification_email == 1) {
				$to = $request->email;
				$subject = 'Confirm Your Email for SwimCapz.';
				$msg="Click on the following link to confirm your email:
				[<a href='".url('user/register/verify/'.$token)."'>'".url('user/register/verify/'.$token)."'</a>]";
				
				//Sending Email To Customer
				$template = 'emails.email_verification_link';
				$mailer = new SendMail();
				$mailer->sendCustomMail($template,$msg,$to,$subject);
				if ($request->has('model_signup')) {
					return response()->json(array('success' => true ,'message' => 'Account created successfully! Check your email for a confirmation link.'));
				}
          		return response()->json('Account created successfully! Check your email for a confirmation link.');

	        } else {

				$user->email_verified = 'Yes';
				$user->update();
				$notification = new Notification;
				$notification->user_id = $user->id;
				$notification->save();
				
				
				$data = [
					'to' => $user->email,
					'type' => "new_registration",
					'cname' => $user->name,
					'oamount' => "",
					'aname' => "",
					'aemail' => "",
					'onumber' => "",
				];
				$mailer = new SendMail();
				$mailer->sendAutoMail($data);


            	Auth::login($user);
				
				if ($request->has('model_signup')) {
					return response()->json(array('success' => true , 'user_id' => Auth::user()->id));
				}

          		return response()->json(1);
	        }

    }

	private function subscribeNews($email)
	{
		$subs = Subscriber::where('email', '=', $email)->first();
		if(!$subs) {

			$subscribe = new Subscriber();
			$subscribe->email=$email;
			$subscribe->save();

			$data = [
				'to' =>$email,
				'subject' => "Welcome to SwimCapz! You've successfully subscribed.",
				'body' => "Thank you for subscribing to SwimCapz newsletters. Stay tuned for exciting updates and offers!",
			];

			$mailer = new GeniusMailer();
			$mailer->sendCustomMail($data);

		}
	}

    public function token($token)
    {	
		$gs = Generalsetting::findOrFail(1);
        if($gs->is_verification_email == 1)
	    {
			$user = User::where('verification_link','=',$token)->first();
			if(isset($user)) {
				$user->email_verified = 'Yes';
				$user->update();
				$notification = new Notification;
				$notification->user_id = $user->id;
				$notification->save();

				/* Welcome Email For User */
				$data = [
					'to' => $user->email,
					'type' => "new_registration",
					'cname' => $user->name,
					'oamount' => "",
					'aname' => "",
					'aemail' => "",
					'onumber' => "",
				];
				$mailer = new SendMail();
			    $mailer->sendAutoMail($data);
				
				return view('emails.email_verified',['gs'=>$gs]);
			} else {
				return redirect('/')
						->with('error',__('Invalid or expired confirmation code. Please request a new one.'));
			}
    	}
    	else {
    		return redirect('/');
    	}
    }
}