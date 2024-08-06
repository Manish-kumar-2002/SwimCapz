@extends('layouts.admin')
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Edit Profile') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.profile') }}">{{ __('Edit Profile') }} </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-product-content1">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">

                            <div class="gocover"
                                style="background: url({{ asset('assets/images/' . $gs->admin_loader) }})
                                no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>

                            <div class="mt-5">
                                <form id="geniusform-" action="{{ route('admin.profile.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    @include('alerts.admin.form-both')

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Current Profile Image') }}</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="img-upload">
                                                <div id="image-preview" class="img-preview"
                                                    style="background: url({{ $data->photo ?
                                                    asset('assets/images/admins/' . $data->photo) :
                                                    asset('assets/images/noimage.png') }});">
                                                    <label
                                                        for="image-upload"
                                                        class="img-label" id="image-label"
                                                    ><i class="icofont-upload-alt"></i>
                                                    {{ __('Upload Image') }}</label>
                                                    <input
                                                        type="file" name="photo"
                                                        class="img-upload" id="image-upload">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Name') }}
                                                <span class="required">*</span></h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <input
                                                type="text"
                                                class="input-field"
                                                name="name"
                                                placeholder="{{ __('User Name') }}"
                                                id="name"
                                                value="{{ $data->name }}"
                                            >
                                            <strong
                                                class="errors error-name mt-3"
                                                style="color:red;font-weight:bold"
                                            ></strong>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Email') }} <span class="required">*</span></h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <input
                                                type="email"
                                                class="input-field"
                                                name="email"
                                                id="email"
                                                placeholder="{{ __('Email Address') }}"
                                                value="{{ $data->email }}"
                                            >
                                            <strong
                                                class="errors error-email mt-3"
                                                style="color:red;font-weight:bold"
                                            ></strong>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Phone') }} <span class="required">*</span></h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <input
                                                type="text"
                                                class="input-field only-numeric"
                                                name="phone"
                                                id="phone"
                                                placeholder="{{ __('Phone Number') }}"
                                                value="{{ $data->phone }}"
                                                max="10"
                                            >
                                            <strong
                                                class="errors error-phone mt-3"
                                                style="color:red;font-weight:bold"
                                            ></strong>
                                        </div>
                                    </div>
                                    @if (Auth::guard('admin')->user()->id == 1)
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Shop Name') }} <span class="required">*</span></h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <input
                                                    type="text"
                                                    class="input-field"
                                                    name="shop_name"
                                                    id="shop_name"
                                                    placeholder="{{ __('Shop Name') }}"
                                                    value="{{ $data->shop_name }}"
                                                >
                                                <strong
                                                    class="errors error-shop_name mt-3"
                                                    style="color:red;font-weight:bold"
                                                ></strong>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="left-area">

                                            </div>
                                        </div>
                                        <div class="col-lg-7">
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
    </div>
@endsection
@section('scripts')
    <script>
        $(function(){
            $('#geniusform-').submit(function(e){
                e.preventDefault(0);
                e.stopImmediatePropagation;

                var name = $("#name").val();
                var email = $("#email").val();
                var phone = $("#phone").val();

                @if (Auth::guard('admin')->user()->id == 1)
                    var shop_name = $("#shop_name").val();
                @endif

                var isValidate = true;
        
                // Validate name
                if (name.trim() === "") {
                    $('strong.error-name').html("Name is required");
                    isValidate = false;
                } else {
                    $('strong.error-name').html("");
                }

                if (email.trim() === "") {
                    $('strong.error-email').html("Email is required");
                    isValidate=false;
                } else if (!isValidEmail(email)) {
                    $('strong.error-email').html("Please enter a valid email address.");
                    isValidate=false;
                } else {
                    $('strong.error-email').html("");
                }

                const phoneRegex = /^\d{0,11}$/;
                if (phone.trim() === "") {
                    $('strong.error-phone').html("Phone no. is required");
                    isValidate=false;
                } else if (!phone.match(phoneRegex)) {
                    $('strong.error-phone').html("Phone no. can have a maximum of 11 digits");
                    isValidate=false;
                }else if (phone.length < 10) {
                    $('strong.error-phone').html("Phone no. can have a minimum of 10 digits");
                    isValidate=false;
                } else {
                    $('strong.error-phone').html("");
                }

                @if (Auth::guard('admin')->user()->id == 1)

                    if (shop_name.trim() === "") {
                        $('strong.error-shop_name').html("Shop Name is required");
                        isValidate=false;
                    } else {
                        $('strong.error-shop_name').html("");
                    }

                @endif
                
                if(isValidate == false) {
                    return;
                }

                if (admin_loader == 1) {
                    $(".gocover").show();
                }

                var fd = new FormData(this);
                var geniusform = $(this);
                $("button.addProductSubmit-btn").prop("disabled", true);
                $.ajax({
                method: "POST",
                url: $(this).prop("action"),
                data: fd,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.errors) {
                        geniusform.parent().find(".alert-success").hide();
                        geniusform.parent().find(".alert-danger").show();
                        geniusform.parent().find(".alert-danger ul").html("");
                        for (var error in data.errors) {
                            $(".alert-danger ul").append("<li>" + data.errors[error] + "</li>");
                        }
                        geniusform.find("input , select , textarea").eq(1).focus();
                    } else {
                        geniusform.parent().find(".alert-danger").hide();
                        geniusform.parent().find(".alert-success").show();
                        geniusform.parent().find(".alert-success p").html(data);
                        geniusform.find("input , select , textarea").eq(1).focus();
                    }
                    if (admin_loader == 1) {
                        $(".gocover").hide();
                    }

                    $("button.addProductSubmit-btn").prop("disabled", false);

                    $(window).scrollTop(0);
                },
                });
            });
        });

        function isValidEmail(email) {
			// Regular expression for basic email validation
			var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			return emailPattern.test(email);
		}

        $(document).on('focus','input,textarea', function(){
            $('.errors').html('');
        });
    </script>
@endsection
