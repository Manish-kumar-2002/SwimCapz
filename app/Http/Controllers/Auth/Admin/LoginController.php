<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
       $this->middleware('guest:admin', ['except' => ['logout']]);
    }

    public function showForm()
    {
        return view('admin.login');
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

        if (Auth::guard('admin')->attempt([
            'email' => $request->email, 'password' => $request->password
        ], $request->remember)) {
            return response()->json(route('admin.dashboard'));
        }

        return response()->json(array('errors' => [
            0 => 'Credentials not matched with our records, try again.'
        ]));

    }


    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()
            ->route('admin.login')
            ->with('success', 'Logout successfully.');
    }
}
