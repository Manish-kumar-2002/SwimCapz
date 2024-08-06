<?php

namespace App\Http\Controllers\User;

use App\Classes\SendMail;
use App\Models\FavoriteSeller;
use App\Models\Order;
use App\Models\Addresses;
use App\Models\PaymentGateway;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Exists;

class UserController extends UserBaseController
{

    public function index()
    {
        $user = $this->user;
        return view('user.dashboard', compact('user'));
    }

   

    public function profile(Request $request)
    {
        $user = $this->user;
        if($request->ajax() && $request->has('load-address')) {
            return view('user._assets._userAddress', compact('user'));
        }

        return view('user.profile', compact('user'));
    }

    public function addressAdd()
    {
        return view('user.address.add');
    }
    
    public function addressStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email',
            'phone'     => 'required|digits:10',
            'address'   => 'required',
            'city'      => 'required',
            'zip'       => 'required|digits:6',
            'state'     => 'required',
            'country'   => 'required',
            'address_type'=> 'required'
        ],['phone.digits' => 'Please enter a valid phone number']);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }

        DB::beginTransaction();
        try {

            $address                =new Addresses();
            $address->user_id       =Auth::user()->id;
            $address->name          =$request->name;
            $address->email         =$request->email;
            $address->phone         =$request->phone;
            $address->company       =@$request->company ?? '';
            $address->address       =$request->address;
            $address->appartment    =@$request->appartment ?? '';
            $address->city          =$request->city;
            $address->zip           =$request->zip;
            $address->state         =$request->state;
            $address->country       =$request->country;
            $address->address_type  =$request->address_type;
            $address->save();
            
            //default check
            $isDefaultExist=Addresses::where([
                'user_id'   => Auth::user()->id,
                'isDefault' => 1
            ])->exists();

            if(!$isDefaultExist) {
                $address->isDefault=1;
                $address->save();
            }

            DB::commit();
            return response()->json(array(
                'message' => 'Address added successfully.'
            ), 200);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json(array(
                'error' => $e
            ), 400);
        }
    }

    public function addressEdit($id)
    {
        $address=Addresses::find($id);
        return view('user.address.edit', compact('address'));
    }

    public function addressDestroy($id)
    {
        Addresses::find($id)->delete();
        return response()->json(array('message' => 'Address deleted successfully.'), 200);
    }
    public function addressUpdate($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email',
            'phone'     => 'required|digits:10',
            'address'   => 'required',
            'city'      => 'required',
            'zip'       => 'required|digits:6',
            'state'     => 'required',
            'country'   => 'required',
            'address_type'=> 'required'
        ],['phone.digits' => 'Please enter a valid phone number']);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }

        DB::beginTransaction();
        try {

            $address                =Addresses::find($id);
            $address->name          =$request->name;
            $address->email         =$request->email;
            $address->phone         =$request->phone;
            $address->company       =@$request->company ?? '';
            $address->address       =$request->address;
            $address->appartment    =@$request->appartment ?? '';
            $address->city          =$request->city;
            $address->zip           =$request->zip;
            $address->state         =$request->state;
            $address->country       =$request->country;
            $address->address_type  =$request->address_type;
            $address->save();
            
            DB::commit();
            return response()->json(array(
                'message' => 'Address updated successfully.'
            ), 200);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json(array(
                'error' => $e
            ), 400);
        }
    }

    public function setDefaultAddress(Request $request)
    {
        //set default 0
        Addresses::where('user_id', Auth::user()->id)
                ->update([
                    'isDefault'=>0
                ]);

        //set default
        Addresses::where('id' , $request->id)
                ->update([
                    'isDefault'=>1
                ]);

        return response()->json(array('message' => 'Default address set successfully.'));
    }

    public function profileupdate(Request $request)
    {
        $rules =[
            'photo' => 'mimes:jpeg,jpg,png,svg,jfif',
            'email' => 'required|unique:users,email,' . $this->user->id,
            'phone' => 'required|digits:10|unique:users,phone,' . $this->user->id,
            'name'  =>'required'
        ];

        $validator = Validator::make($request->all(), $rules, [
            'phone.digits' => 'Please enter a valid phone number'
        ]);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }

        $input = $request->all();
        $data = $this->user;
        if ($file = $request->file('photo')) {
            $extensions = ['jpeg', 'jpg', 'png', 'svg','jfif'];
            if (!in_array($file->getClientOriginalExtension(), $extensions)) {
                return response()->json(array('errors' => ['Image format not supported']));
            }

            $name = \PriceHelper::ImageCreateName($file);
            $file->move('assets/images/users/', $name);
            if ($data->photo != null) {
                if (file_exists(public_path() . '/assets/images/users/' . $data->photo)) {
                    unlink(public_path() . '/assets/images/users/' . $data->photo);
                }
            }
            $input['photo'] = $name;
        }

        $isNewEmail=false;
        if($this->user->email != $request->email) {
            $input['email_verified']='NO';

            $token = md5(time().$request->name.$request->email);
	        $input['verification_link'] = $token;
            $isNewEmail=true;
        }


        $data->update($input);
        if($isNewEmail) {
            $to = $request->email;
            $subject = 'Confirm Your Email for SwimCapz.';
            $msg="Click on the following link to confirm your email:
            [<a href='".url('user/register/verify/'.$token)."'>'".url('user/register/verify/'.$token)."'</a>]";
            
            $template = 'emails.email_verification_link';
            $mailer = new SendMail();
            $mailer->sendCustomMail($template,$msg,$to,$subject);
            $msg = __('Successfully updated your profile! Check your email for a verification link.');

        }else {
            $msg = __('Successfully updated your profile');
        }
        
        return response()->json($msg);
    }

    public function resetform()
    {
        return view('user.reset');
    }

    public function reset(Request $request)
    {
        $user = $this->user;
        if ($request->cpass) {
            if (Hash::check($request->cpass, $user->password)) {
                if($request->cpass == $request->newpass)
                {
                    return response()->json(array('errors' => [
                        0 => __('New password cannot be the same as the old password.')
                    ]));
                }
                elseif ($request->newpass == $request->renewpass) {
                    $input['password'] = Hash::make($request->newpass);
                } else {
                    return response()->json(array('errors' => [0 => __('Confirm password does not match.')]));
                }
            } else {
                return response()->json(array('errors' => [0 => __('Current password does not match.')]));
            }
        }
        $user->update($input);
        $msg = __('Password changed successfully.');
        return response()->json($msg);
    }

    public function loadpayment($slug1, $slug2)
    {
        $data['payment'] = $slug1;
        $data['pay_id'] = $slug2;
        $data['gateway'] = '';
        if ($data['pay_id'] != 0) {
            $data['gateway'] = PaymentGateway::findOrFail($data['pay_id']);
        }
        return view('load.payment-user', $data);
    }

    public function favorite($id1, $id2)
    {
        $fav = new FavoriteSeller();
        $fav->user_id = $id1;
        $fav->vendor_id = $id2;
        $fav->save();
        $data['icon'] = '<i class="icofont-check"></i>';
        $data['text'] = __('Favorite');
        return response()->json($data);
    }

    public function favorites()
    {
        $user = $this->user;
        $favorites = FavoriteSeller::where('user_id', '=', $user->id)->get();
        return view('user.favorite', compact('user', 'favorites'));
    }

    public function favdelete($id)
    {
        $wish = FavoriteSeller::findOrFail($id);
        $wish->delete();
        return redirect()->route('user-favorites')->with('success', __('Successfully Removed The Seller.'));
    }

    public function affilate_code()
    {
        $user = $this->user;
        return view('user.affilate.affilate-program', compact('user'));
    }

    public function affilate_history()
    {
        $user = $this->user;
        $affilates = Order::where('status', '=', 'completed')->where('affilate_users', '!=', null)->get();
        $final_affilate_users = array();
        $i = 0;
        foreach ($affilates as $order) {
            $affilate_users = json_decode($order->affilate_users, true);
            foreach ($affilate_users as $key => $auser) {
                if ($auser['user_id'] == $user->id) {
                    $final_affilate_users[$i]['customer_name'] = $order->customer_name;
                    $final_affilate_users[$i]['product_id'] = $auser['product_id'];
                    $final_affilate_users[$i]['charge'] = \PriceHelper::showOrderCurrencyPrice(($auser['charge'] * $order->currency_value), $order->currency_sign);

                    $i++;
                }
            }
        }
        return view('user.affilate.affilate-history', compact('user', 'final_affilate_users'));
    }

    public function verified_email(){
        return view('emails.email_verified');
    }

}
