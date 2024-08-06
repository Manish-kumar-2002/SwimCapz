@extends('layouts.front')

@section('content')
    <style>
        .error {
            color: red;
            font-size: 14px;
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
                                   <a href="{{ route('front.index') }}"> <img src="{{ asset('assets/images/' . $gs->logo) }}" alt=""></a>
                                    <h2>Create Account</h2>
                                    <p>Creating an account is free and allows you to track and manage your orders.</p>
                                </div>
                                <div class="registration-form">
                                    @include('includes.admin.form-login')
                                    <form id="registerform" action="{{ route('user-register-submit') }}" method="POST">
                                        @csrf

                                        <div class="input-wrap">
                                            <label for="fullname">Name</label>
                                            <input type="text" name="name" id="name" required=""
                                                placeholder="{{ __('Name') }}">
                                            <span id="nameError" class="error"></span>
                                        </div>

                                        <div class="input-wrap">
                                            <label for="eaddress">Email address</label>
                                            <input name="email" type="email" id="email" required=""
                                                placeholder="{{ __('Email Address') }}">
                                            <span id="emailError" class="error"></span>
                                        </div>

                                        <div class="input-wrap">
                                            <label for="eaddress">Phone no.</label>
                                            <input
                                                class="only-numeric"
                                                name="phone"
                                                type="text"
                                                min="1"
                                                max="10"
                                                id="phone"
                                                required=""
                                                placeholder="{{ __('Phone no') }}"
                                            />
                                            <span id="phoneError" class="error"></span>
                                        </div>

                                        <div class="input-wrap">
                                            <label for="pwd">Password</label>
                                            <div class="icon-wrap">
                                                <input type="password" id="password" required="" name="password"
                                                    placeholder="{{ __('Password') }}">
                                                <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon"
                                                    onclick="togglePasswordVisibility('password')"></span>
                                            </div>
                                            <span id="passcheck" class="error"> Please Fill the password</span>
                                        </div>

                                        <div class="input-wrap">
                                            <label for="pwd">Confirm Password</label>
                                            <div class="icon-wrap">
                                                <input type="password" id="conpassword" required=""
                                                    name="password_confirmation"
                                                    placeholder="{{ __('Confirm Password') }}"
                                                >
                                                <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon"
                                                    onclick="togglePasswordVisibility('password_confirmation')"></span>
                                            </div>
                                            <span id="conpasscheck" class="error"> Password didn't match </span>
                                        </div>

                                        <div class="checkbox-wrap" style="margin-bottom: 6px;">
                                            <input type="checkbox" id="get_updates" name="terms" value="1">
                                            <label
                                                for="get_updates"
                                            ><a
                                                href="{{url('terms-condition')}}"
                                                target="_blank"
                                            ><u>Accept Terms & Conditions</u></a></label>
                                        </div>
                                        <div style="padding-bottom:20px;">
                                            <span id="get_updatesError" class="error"></span>
                                        </div>

                                         <div class="check-wrap">
                                            <input type="checkbox" id="receive_occasion_news" 
                                                name="receive_occasion_news" value="1">
                                            <label for="receive_occasion_news">Receive occasional news updates and special offers from SwimCapz.com - Swim Caps Made To Order - Custom Swim Caps. Your information will remain private and you can unsubscribe at any time</label>
                                        </div>
                                        <div style="padding-bottom:20px;">
                                            <span id="receive_occasion_newsError" class="error"></span>
                                        </div>

                                        @if ($gs->is_capcha == 1)
                                            <div class="form-input mb-3">
                                                {!! NoCaptcha::display() !!}
                                                {!! NoCaptcha::renderJs() !!}
                                                @error('g-recaptcha-response')
                                                    <p class="my-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endif

                                        <input id="processdata" type="hidden" value="{{ __('Processing...') }}">
                                        <button type="button" id="submitbtn" class="btn black" name="register"
                                            value="Register">{{ __('Register') }}</button>
                                        </p>
                                    </form>
                                    <p class="switch">
                                        {{ __('Do have any account?') }}<a href="{{ route('user.login') }}"
                                            class="text-secondary">{{ __(' Login') }}</a>
                                    </p>
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
    <script src="{{ asset('assets/front/js/register.js') }}" type="text/javascript"></script>
@endsection
