@extends('layouts.front')

@section('content')



    <!-- breadcrumb -->
    <!--==================== Login Form Start ====================-->
    <div class="full-row">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="woocommerce">
                        <div class="row">
                            <div class="col-lg-6 col-md-8 col-12 mx-auto">
                                <div class="form-head">
                                    <a href="{{ route('front.index') }}">
                                        <img src="{{ asset('assets/images/' . $gs->logo) }}" alt="">
                                    </a>
                                    <h2>Sign In</h2>
                                    <p>Welcome back, you've been missed!</p>
                                </div>
                                <div class="sign-in-form">
                                    @include('alerts.admin.form-login')

                                    @if (Session::has('auth-modal'))
                                        <div class="alert alert-danger alert-dismissible">

                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                            {{ Session::get('auth-modal') }}
                                        </div>
                                    @endif

                                    @if (Session::has('forgot-modal'))
                                        <div class="alert alert-success alert-dismissible">

                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                            {{ Session::get('forgot-modal') }}
                                        </div>
                                    @endif
                                    <form class="woocommerce-form-login" id="loginform"
                                        action="{{ route('user.login.submit') }}" method="POST">
                                        @csrf
                                        <div class="input-wrap">
                                            <label for="username">{{ __('Email address') }}<span
                                                    class="required">*</span></label>
                                            <input type="email" name="email" id="username"
                                                placeholder="{{ __('Enter your email') }}" required="">

                                        </div>

                                        <div class="input-wrap">
                                            <label for="password">{{ __('Password') }}<span
                                                    class="required">*</span></label>
                                            <div class="icon-wrap">
                                                <input type="password" name="password" id="password"
                                                    placeholder="{{ __('Enter your password') }}" required="" />
                                                <span toggle="#password-field"
                                                    class="fa fa-fw fa-eye-slash field-icon toggle-password"
                                                    onclick="togglePasswordVisibility('password')"></span>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <!-- <p>
                                                            <a href="{{ route('user.register') }}"  class="text-secondary">{{ __("Don't have any account?") }}</a>
                                                        </p> -->
                                            <p class="forget_password">
                                                <a href="{{ route('user.forgot') }}"
                                                    class="text-secondary">{{ __('Forgot Password?') }}</a>
                                            </p>

                                        </div>


                                        <input type="hidden" name="modal" value="1">
                                        @if (Session::has('auth-modal'))
                                            <input type="hidden" name="auth_modal" value="1">
                                        @endif
                                        <input id="authdata" type="hidden" value="{{ __('Authenticating...') }}">

                                        <button type="submit" class="btn black" name="login"
                                            value="Log in">{{ __('SIGN IN') }}</button></p>
                                        @if ($socialsetting->f_check == 1 || $socialsetting->g_check == 1)
                                            <!-- <div class="social-area text-center">
                                                                <h3 class="title  mt-3">{{ 'OR' }}</h3>
                                                                <p class="text">{{ __('Sign In with social media') }}</p>
                                                                <ul class="social-links">
                                                                    @if ($socialsetting->f_check == 1)
    <li>
                                                                    <a href="{{ route('social-provider', 'facebook') }}">
                                                                        <i class="fab fa-facebook-f"></i>
                                                                    </a>
                                                                    </li>
    @endif
                                                                    @if ($socialsetting->g_check == 1)
    <li>
                                                                    <a href="{{ route('social-provider', 'google') }}">
                                                                        <i class="fab fa-google-plus-g"></i>
                                                                    </a>
                                                                    </li>
    @endif
                                                                </ul>
                                                            </div> -->
                                        @endif
                                    </form>
                                    <p class="switch">
                                        {{ __('Ready to get started?') }}<a href="{{ route('user.register') }}"
                                            class="text-secondary">{{ __('  Sign Up') }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--==================== Login Form Start ====================-->


@endsection

@section('script')
    <script>
        function togglePasswordVisibility(fieldName) {
            var input = document.getElementsByName(fieldName)[0];
            var eyeIcon = input.nextElementSibling;

            if (input.type === "password") {
                input.type = "text";
                eyeIcon.className = "fa fa-fw fa-eye field-icon";
            } else {
                input.type = "password";
                eyeIcon.className = "fa fa-fw fa-eye-slash field-icon";
            }
        }
    </script>
@endsection
