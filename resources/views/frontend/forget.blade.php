@extends('layouts.front')
@section('content')

<!--==================== Registration Form Start ====================-->
<div class="full-row">
   <div class="container">
      <div class="row">
         <div class="col">
            <div class="woocommerce">
               <div class="row">
                  <div class="col-lg-6 col-md-8 col-12 mx-auto">
                  <div class="form-head reset-form">
                                       <a href="{{ route('front.index') }}"> <img src="{{asset('assets/images/'.$gs->logo)}}" alt=""></a>
                                        <h2>Forgot Password</h2>
                                        <p>Enter your email address below and we’ll send you an email with
instructions on how to reset your password.</p>
                                    </div>
                     <div class="registration-form">
                        @include('includes.admin.form-login')
                        <!-- <h3>{{ __('Forget Password') }}</h3> -->
                        <form id="forgotform" action="{{route('user.forgot.submit')}}" method="POST">
                           {{ csrf_field() }}
                           <div class="input-wrap">
                           <label for="reg_email">{{ __('Email address') }}<span class="required">*</span></label>
                           <input type="email" name="email"  placeholder="{{ __('Enter Email Address') }}" id="reg_email"  required="">
   
                           </div>
                           <!-- <p class="switch">{{ __('A password will be sent to your email address.') }}</p> -->
                           <p>
                              <input class="authdata" type="hidden" value="{{ __('Checking...')}}">
                              <button type="submit" class="btn register" name="register" value="Register">{{ __('Reset Password') }}</button>
                           </p>
                        </form>
                     
                        <p class="switch"><a href="{{asset('user/login')}}"><i class="fa fa-arrow-left"></i> Back to log In</a></p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--==================== Registration Form Start ====================-->
@endsection
@section('script')
@endsection
