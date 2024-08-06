@extends('layouts.front')
@section('content')
<style>
    .error{
        color:red;
        font-size:14px;
    }
</style>
<!--==================== Registration Form Start ====================-->
<div class="full-row">
   <div class="container">
      <div class="row">
         <div class="col">
            <div class="woocommerce">
               <div class="row">
                  <div class="col-lg-6 col-md-8 col-12 mx-auto">
                  <div class="form-head">
                                        <img src="{{asset('assets/images/'.$gs->logo)}}" alt="">
                                        <h2>Reset Password</h2>
                                    </div>
                     <div class="registration-form">
                        @include('includes.admin.form-login')
                        <!-- <h3>{{ __('Forget Password') }}</h3> -->
                        <form id="forgotform" action="{{route('user.change.password')}}" method="POST">
                           {{ csrf_field() }}
                           <div class="input-wrap">
                           <label for="pwd">New Password <span class="required">*</span></label>
                           <div class="icon-wrap">
                           <input type="password" id="password" required="" name="newpass"  placeholder="{{ __('Password') }}" >
                           <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon" onclick="togglePasswordVisibility('newpass')"></span>
                        </div>
                        <span id="passcheck" class="error"> Please Fill the password</span>
                           </div>

                           <div class="input-wrap">
                           <label for="pwd">Confirm Password <span class="required">*</span></label>
                           <div class="icon-wrap">
                           <input type="password" id="conpassword" required="" name="renewpass"  placeholder="{{ __('Confirm Password') }}" >
                           <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon" onclick="togglePasswordVisibility('renewpass')"></span>
                        </div>
                        <span id="conpasscheck" class="error"> Password didn't match </span>
                           </div>
                           <input type="hidden" name="token" value="{{ $token }}">
                           <!-- <p class="switch">{{ __('A password will be sent to your email address.') }}</p> -->
                           <p>
                              <input class="authdata" type="hidden" value="{{ __('Checking...')}}">
                              <button type="submit" id="submitbtn" class="btn register" name="register" value="Register">{{ __('Submit') }}</button>
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
@endsection
@section('script')
<script src="{{asset('assets/front/js/forget_password.js')}}" type="text/javascript">
</script>
@endsection
