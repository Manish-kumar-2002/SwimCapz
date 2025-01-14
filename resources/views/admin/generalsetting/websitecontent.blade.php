@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Website Contents') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('General Settings') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-contents') }}">{{ __('Website Contents') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-product-content1 add-product-content2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description py-5">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ asset('assets/images/' . $gs->admin_loader) }})
								no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form action="{{ route('admin-gs-update') }}" id="geniusformdata" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                @include('alerts.admin.form-both')

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Website Title') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field"
                                            placeholder="{{ __('Write Your Site Title Here') }}" name="title"
                                            value="{{ $gs->title }}" required="">
                                    </div>
                                </div>


                                <div class="row justify-content-center" style="display:none;">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Theme Color') }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="input-group colorpicker-component cp">
                                                <input type="text" class="input-field color-field" name="colors"
                                                    value="{{ $gs->colors }}" class="form-control cp" />
                                                <span class="input-group-addon"><i></i></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row justify-content-center" style="display:none;">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Copyright Text') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field"
                                            placeholder="{{ __('Write Your Site Copyright Text Here') }}"
											name="copyright"
                                            value="{{ $gs->copyright }}" required="">
                                    </div>
                                </div>


                                <div class="row justify-content-center" style="display:none;">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Debug Mode') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select
                                                class="process select droplinks
												{{ $gs->is_debug == 1 ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                    value="{{ route('admin-gs-status', ['is_debug', 1]) }}"
                                                    {{ $gs->is_debug == 1 ? 'selected' : '' }}>{{ __('Active') }}
                                                </option>
                                                <option data-val="0"
                                                    value="{{ route('admin-gs-status', ['is_debug', 0]) }}"
                                                    {{ $gs->is_debug == 0 ? 'selected' : '' }}>{{ __('Inactive') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center" style="display:none;">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Cookie') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select
                                                class="process select droplinks {{ $gs->is_cookie == 1 ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                    value="{{ route('admin-gs-status', ['is_cookie', 1]) }}"
                                                    {{ $gs->is_cookie == 1 ? 'selected' : '' }}>{{ __('Activated') }}
                                                </option>
                                                <option data-val="0"
                                                    value="{{ route('admin-gs-status', ['is_cookie', 0]) }}"
                                                    {{ $gs->is_cookie == 0 ? 'selected' : '' }}>{{ __('Deactivated') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="row justify-content-center" style="display:none;">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Use HTTPS') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select
                                                class="process select droplinks {{ $gs->is_secure == 1 ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                    value="{{ route('admin-gs-status', ['is_secure', 1]) }}"
                                                    {{ $gs->is_secure == 1 ? 'selected' : '' }}>{{ __('Yes') }}
                                                </option>
                                                <option data-val="0"
                                                    value="{{ route('admin-gs-status', ['is_secure', 0]) }}"
                                                    {{ $gs->is_secure == 0 ? 'selected' : '' }}>{{ __('No') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Capcha') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select
                                                class="process select status-change droplinks {{ $gs->is_capcha == 1 ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                    value="{{ route('admin-gs-status', ['is_capcha', 1]) }}"
                                                    {{ $gs->is_capcha == 1 ? 'selected' : '' }}>{{ __('Active') }}
                                                </option>
                                                <option data-val="0"
                                                    value="{{ route('admin-gs-status', ['is_capcha', 0]) }}"
                                                    {{ $gs->is_capcha == 0 ? 'selected' : '' }}>{{ __('Inactive') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Google ReCapcha Site Key') }} 
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="tawk-area">
                                            <input class="input-field" name="capcha_site_key"
                                                value="{{ $gs->capcha_site_key }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Google ReCapcha Secret Key') }} 
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="tawk-area">
                                            <input class="input-field" name="capcha_secret_key"
                                                value="{{ $gs->capcha_secret_key }}">
                                        </div>
                                    </div>
                                </div>


                                <div class="row justify-content-center" style="display:none;">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Sign Up Verification') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select
                                                class="process select droplinks {{ $gs->is_verification_email == 1 ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                    value="{{ route('admin-gs-status', ['is_verification_email', 1]) }}"
                                                    {{ $gs->is_verification_email == 1 ? 'selected' : '' }}>
                                                    {{ __('Activated') }}</option>
                                                <option data-val="0"
                                                    value="{{ route('admin-gs-status', ['is_verification_email', 0]) }}"
                                                    {{ $gs->is_verification_email == 0 ? 'selected' : '' }}>
                                                    {{ __('Deactivated') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="row justify-content-center" style="display:none;">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Tawk.to') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select
                                                class="process select droplinks {{ $gs->is_talkto == 1 ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                    value="{{ route('admin-gs-status', ['is_talkto', 1]) }}"
                                                    {{ $gs->is_talkto == 1 ? 'selected' : '' }}>{{ __('Activated') }}
                                                </option>
                                                <option data-val="0"
                                                    value="{{ route('admin-gs-status', ['is_talkto', 0]) }}"
                                                    {{ $gs->is_talkto == 0 ? 'selected' : '' }}>{{ __('Deactivated') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center" style="display:none;">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Tawk.to ID') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="tawk-area">
                                            <textarea class="input-field" name="talkto">{{ $gs->talkto }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center" style="display:none;">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Cronjob URL') }} *
                                            </h4>
                                            <p class="sub-heading">{{ __('(Copy This URL and paste this to cron job.)') }}
                                                <a target="_blank"
                                                    href="https://www.youtube.com/watch?v=Hw0fbM7E80Q">{{ __('Check Tutorial') }}</a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div>
                                            <textarea class="input-field" readonly="">{{ url('/vendor/subscription/check') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center" style="display:none;">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Partner Header') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="tawk-area">
                                            <textarea
                                                class="input-field"
                                                name="partner_title">{{ $gs->partner_title }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center" style="display:none;">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Partner Text') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="tawk-area">
                                            <textarea
                                                class="input-field"
                                                name="partner_text">{{ $gs->partner_text }}</textarea>
                                        </div>
                                    </div>
                                </div>
<hr>
								<div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Address') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="tawk-area">
                                            <textarea
                                                class="input-field"
                                                name="address">{{ $gs->address }}</textarea>
                                        </div>
                                    </div>
                                </div>
								<div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Mobile') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="tawk-area">
                                            <input
                                                max="10"
                                                type="text"
                                                class="input-field only-numeric"
                                                name="mobile"
                                                value="{{ $gs->mobile }}"
                                            />
                                        </div>
                                    </div>
                                </div>
								<div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Email') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="tawk-area">
                                            <input
                                                type="email"
                                                error-class="error-email"
                                                class="input-field email-validate"
                                                name="email"
                                                value="{{ $gs->email }}"
                                            />
                                            <p class="errors error-email" style="color:red;"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
    $('.status-change').on('change', function() {
        var selectedOption = $(this).find('option:selected').data('val');
        if (selectedOption == 1) {
            $(this).removeClass('drop-danger').addClass('drop-success');
        } else if (selectedOption == 0) {
            $(this).removeClass('drop-success').addClass('drop-danger');
        }
    });

    // Initial check to apply class based on initial value
    var initialSelectedOption = $('.status-change').find('option:selected').data('val');
    if (initialSelectedOption == 1) {
        $('.status-change').removeClass('drop-danger').addClass('drop-success');
    } else if (initialSelectedOption == 0) {
        $('.status-change').removeClass('drop-success').addClass('drop-danger');
    }
});
</script>
@endsection
