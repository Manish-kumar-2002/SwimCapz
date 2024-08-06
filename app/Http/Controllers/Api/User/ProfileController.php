<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;

use App\Http\Resources\UserResource;
use App\Models\FavoriteSeller;
use App\Models\Product;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ProfileController extends Controller
{
    public function dashboard()
    {
        try {
            $user = Auth::guard('api')->user();
            $data['user'] = $user;
            $data['affilate_income'] = Product::vendorConvertPrice($user->affilate_income);
            $data['current_balance'] = Product::vendorConvertPrice($user->current_balance);
            $data['completed_orders'] = (string) Auth::user()->orders()->where('status', 'completed')->count();
            $data['pending_orders'] = (string) Auth::user()->orders()->where('status', 'pending')->count();
            $data['recent_orders'] = (string) Auth::user()->orders()->latest()->take(5)->get();
            return response()->json(['status' => true, 'data' => $data, 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            
            $rules =
                [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . auth()->user()->id,
                'phone' => 'required',
                'fax' => 'required',
                'city' => 'required',
                'country' => 'required',
                'zip' => 'required',
                'address' => 'required',
                'photo' => 'mimes:jpeg,jpg,png,svg',

            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'data' => [], 'error' => $validator->errors()]);
            }

            //--- Validation Section Ends
            $input = $request->all();
            $data = auth()->user();
            if ($file = $request->file('photo')) {
                $name = time() . $file->getClientOriginalName();
                $file->move('assets/images/users/', $name);
                if ($data->photo != null) {
                    if (file_exists(public_path() . '/assets/images/users/' . $data->photo)) {
                        unlink(public_path() . '/assets/images/users/' . $data->photo);
                    }
                }
                $input['photo'] = $name;
            }

            if ($request->shop_name) {
                unset($input['shop_name']);
            }

            if ($request->balance) {
                unset($input['balance']);
            }

            if ($request->is_vendor) {
                unset($input['is_vendor']);
            }

            if ($request->email) {
                unset($input['email']);
            }

            if ($request->ban) {
                unset($input['ban']);
            }

            if ($request->mail_sent) {
                unset($input['mail_sent']);
            }

            if ($request->date) {
                unset($input['date']);
            }

            if ($request->current_balance) {
                unset($input['current_balance']);
            }

            $data->update($input);

            return response()->json(['status' => true, 'data' => new UserResource($data), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function updatePassword(Request $request)
    {

        $rules =
            [
            'current_password' => 'required',
            'new_password' => 'required',
            'renew_password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'data' => [], 'error' => $validator->errors()]);
        }

        try {
            $user = auth()->user();
            if (Hash::check($request->current_password, $user->password)) {
                if ($request->new_password == $request->renew_password) {
                    $input['password'] = Hash::make($request->new_password);
                } else {
                    return response()->json([
                        'status' => true, 'data' => [],
                        'error' => ['message' => 'Confirm password does not match.']]);
                }
            } else {
                return response()->json([
                    'status' => true, 'data' => [],
                    'error' => ['message' => 'Current password does not match.']]);
            }
            $user->update($input);
            return response()->json([
                'status' => true, 'data' => ['message' => 'Successfully changed your password.'], 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => $e->getMessage()]);
        }
    }

    public function favorite(Request $request)
    {
        try {
            $input = $request->all();
            $user = Auth::guard('api')->user();
            $ck = FavoriteSeller::where('user_id', $user->id)->where('vendor_id', $input['vendor_id'])->exists();
            if (!$ck) {
                $fav = new FavoriteSeller();
                $fav->user_id = $user->id;
                $fav->vendor_id = $input['vendor_id'];
                $fav->save();
                return response()->json([
                    'status' => true,
                    'data' => ['message' => 'Successfully added to favorite seller.'], 'error' => []]);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => [], 'error' => ['message' => 'Added to favorite already.']]);
            }

        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => $e->getMessage()]);
        }
    }

    public function favorites()
    {

        try {
            $user = Auth::guard('api')->user();
            $favorites = FavoriteSeller::where('user_id', '=', $user->id)->get();
            $vendors = array();
            foreach ($favorites as $key => $favorite) {
                $seller = User::find($favorite->vendor_id);
                if ($seller) {
                    $vendors[$key]['id'] = $favorite->id;
                    $vendors[$key]['shop_id'] = $seller->id;
                    $vendors[$key]['shop_name'] = $seller->shop_name;
                    $vendors[$key]['owner_name'] = $seller->owner_name;
                    $vendors[$key]['shop_address'] = $seller->shop_address;
                    $vendors[$key]['shop_link'] = route('front.vendor', str_replace(' ', '-', ($seller->shop_name)));
                }
            }
            return response()->json(['status' => true, 'data' => $vendors, 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => $e->getMessage()]);
        }
    }

    public function favdelete($id)
    {
        try {
            $wish = FavoriteSeller::find($id);
            $wish->delete();
            return response()->json([
                'status' => true,
                'data' => ['message' => 'Successfully removed the seller.'], 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => $e->getMessage()]);
        }
    }
}
