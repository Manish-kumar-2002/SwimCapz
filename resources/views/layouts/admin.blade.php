<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="GeniusOcean">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title -->
    <title>{{ $gs->title }}</title>
    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/' . $gs->favicon) }}" />
    <!-- Bootstrap -->
    <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet" />
    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/fontawesome.css') }}">
    <!-- icofont -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/icofont.min.css') }}">
    <!-- Sidemenu Css -->
    <link href="{{ asset('assets/admin/plugins/fullside-menu/css/dark-side-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/plugins/fullside-menu/waves.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('assets/admin/css/plugin.css') }}" rel="stylesheet" />

    <link href="{{ asset('assets/admin/css/jquery.tagit.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-coloroicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/toastr.min.css') }}">
    <!-- Main Css -->

    <!-- stylesheet -->
    @if (DB::table('admin_languages')->where('is_default', '=', 1)->first()->rtl == 1)
        <link href="{{ asset('assets/admin/css/rtl/style.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/css/rtl/custom.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/css/rtl/responsive.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/css/common.css') }}" rel="stylesheet" />
    @else
        <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/css/custom.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/css/responsive.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/css/common.css') }}" rel="stylesheet" />
    @endif

    @yield('styles')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- checkbox toggle -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.1.1/css/bootstrap5-toggle.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.1.1/js/bootstrap5-toggle.ecmas.min.js"></script>
   
    <style>
        p.errors {
            color: red;
        }

        span.required {
            color: red;
        }
        a {
            text-decoration: none ;
        }

        .form-check-label{
            font-size: 35px;
        }
        .form-check-label{
            font-size: 35px;
        }
        .form-check-label i.fa-toggle-on {
            color:green;
            cursor:pointer;
        }

        .form-check-label i.fa-toggle-off {
            color:red;
            cursor:pointer;
        }
    </style>
</head>

<body id="page-top">

    <div class="page">
        <div class="page-main">
            <!-- Header Menu Area Start -->
            <div class="header">
                <div class="container-fluid">
                    <div class="d-flex mobile-menu-check justify-content-between">
                        <a class="admin-logo" href="{{ route('front.index') }}" target="_blank">
                            <img src="{{ asset('assets/images/' . $gs->logo) }}" alt="">
                        </a>
                        <div class="menu-toggle-button">
                            <a class="nav-link" href="javascript:;" id="sidebarCollapse">
                                <div class="my-toggl-icon">
                                    <span class="bar1"></span>
                                    <span class="bar2"></span>
                                    <span class="bar3"></span>
                                </div>
                            </a>
                        </div>

                        <div class="right-eliment">
                            <ul class="list">
                                <input type="hidden" id="all_notf_count" value="{{ route('all-notf-count') }}">
                                <li class="bell-area">
                                    <a class="dropdown-toggle-1" target="_blank" href="{{ route('front.index') }}">
                                        <i class="fas fa-globe-americas"></i>
                                    </a>
                                </li>

                                <li class="bell-area">
                                    <a id="notf_conv" class="dropdown-toggle-1" href="javascript:;">
                                        <i class="far fa-envelope"></i>
                                        <span
                                            id="conv-notf-count">{{ App\Models\Notification::countConversation() }}</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="dropdownmenu-wrapper" data-href="{{ route('user-notf-show') }}"
                                            id="user-notf-show">
                                        </div>
                                        <div class="dropdownmenu-wrapper" data-href="{{ route('conv-notf-show') }}"
                                            id="conv-notf-show">
                                        </div>
                                        <div class="dropdownmenu-wrapper" data-href="{{ route('product-notf-show') }}"
                                            id="product-notf-show">
                                        </div>
                                        <div class="dropdownmenu-wrapper" data-href="{{ route('order-notf-show') }}"
                                            id="order-notf-show">
                                        </div>
                                        <a id="conv-notf-clear" data-href="{{ route('conv-notf-clear') }}" class="clear" href="javascript:;">
                                            {{ __('Clear All') }}
                                        </a>
                                    </div>
                                </li>

                                {{-- <li class="bell-area">
                                    <a id="notf_product" class="dropdown-toggle-1" href="javascript:;">
                                        <i class="icofont-cart"></i>
                                        <span
                                            id="product-notf-count"
                                        >{{ App\Models\Notification::countProduct() }}</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="dropdownmenu-wrapper" data-href="{{ route('product-notf-show') }}"
                                            id="product-notf-show">
                                        </div>
                                    </div>
                                </li> --}}

                                {{-- <li class="bell-area">
                                    <a id="notf_user" class="dropdown-toggle-1" href="javascript:;">
                                        <i class="far fa-user"></i>
                                        <span
                                            id="user-notf-count"
                                        >{{ App\Models\Notification::countRegistration() }}</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="dropdownmenu-wrapper" data-href="{{ route('user-notf-show') }}"
                                            id="user-notf-show">
                                        </div>
                                    </div>
                                </li> --}}

                                {{-- <li class="bell-area">
                                    <a id="notf_order" class="dropdown-toggle-1" href="javascript:;">
                                        <i class="far fa-newspaper"></i>
                                        <span
                                            id="order-notf-count"
                                        >{{ App\Models\Notification::countOrder() }}</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="dropdownmenu-wrapper" data-href="{{ route('order-notf-show') }}"
                                            id="order-notf-show">
                                        </div>
                                    </div>
                                </li> --}}

                                <li class="login-profile-area">
                                    <a class="dropdown-toggle-1" href="javascript:;">
                                        <div class="user-img">
                                            @php
                                                $path = asset('assets/images/noimage.png');
                                                $srcPath = public_path(
                                                    'assets/images/admins/' . Auth::guard('admin')->user()->photo,
                                                );
                                                if (Auth::guard('admin')->user()->photo && file_exists($srcPath)) {
                                                    $path = asset(
                                                        'assets/images/admins/' . Auth::guard('admin')->user()->photo,
                                                    );
                                                }
                                            @endphp
                                            <img src="{{ $path }}" alt="No Image">
                                        </div>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="dropdownmenu-wrapper">
                                            <ul>
                                                <h5>{{ __('Welcome!') }}</h5>
                                                <li>
                                                    <a href="{{ route('admin.profile') }}"><i
                                                            class="fas fa-user"></i> {{ __('Edit Profile') }}</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.password') }}"><i
                                                            class="fas fa-cog"></i> {{ __('Change Password') }}</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.logout') }}"><i
                                                            class="fas fa-power-off"></i> {{ __('Logout') }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header Menu Area End -->
            <div class="wrapper">
                <!-- Side Menu Area Start -->
                <nav id="sidebar" class="nav-sidebar">
                    <ul class="list-unstyled components" id="accordion">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="wave-effect"><i
                                    class="fa fa-home mr-2"></i>{{ __('Dashboard') }}</a>
                        </li>
                        @if (Auth::guard('admin')->user()->IsSuper())
                            @include('partials.admin-role.super')

                            <li class="mt-3 text-dark text-center">
                                Version 6.0
                            </li>
                        @else
                            @include('partials.admin-role.normal')
                        @endif

                    </ul>

                </nav>
                <!-- Main Content Area Start -->
                @yield('content')
                <!-- Main Content Area End -->
            </div>
            <div class="footer">
               <div class="container-fluid">
               <p>© 2024 SwimCaps.com</p>
               </div>
            </div>
        </div>
    </div>
    @php
        $curr = \App\Models\Currency::where('is_default', '=', 1)->first();
    @endphp
    <script type="text/javascript">
        var mainurl = "{{ url('/') }}";
        var admin_loader = {{ $gs->is_admin_loader }};
        var whole_sell = {{ $gs->wholesell }};
        var getattrUrl = '{{ route('admin-prod-getattributes') }}';
        var curr = {!! json_encode($curr) !!};
        var lang = {
            'additional_price': '{{ __('0.00 (Additional Price)') }}'
        };
    </script>

    <!-- Dashboard Core -->
    <script src="{{ asset('assets/admin/js/vendors/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendors/vue.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendors/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jqueryui.min.js') }}"></script>
    <!-- Fullside-menu Js-->
    <script src="{{ asset('assets/admin/plugins/fullside-menu/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/fullside-menu/waves.min.js') }}"></script>

    <script src="{{ asset('assets/admin/js/plugin.js') }}"></script>
    <script src="{{ asset('assets/admin/js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tag-it.js') }}"></script>
    <script src="{{ asset('assets/admin/js/nicEdit.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/notify.js') }}"></script>

    <script src="{{ asset('assets/admin/js/jquery.canvasjs.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/front/js/toastr.min.js') }}"></script>

    <script src="{{ asset('assets/admin/js/load.js') }}"></script>
    <!-- Custom Js-->
    <script src="{{ asset('assets/admin/js/custom.js') }}"></script>
    <!-- AJAX Js-->
    <script src="{{ asset('assets/admin/js/myscript.js') }}"></script>
    <script src="{{ asset('assets/admin/js/function.js') }}"></script>
    @yield('scripts')
    @if ($gs->is_admin_loader == 0)
        <style>
            div#geniustable_processing {
                display: none !important;
            }
        </style>
    @endif

    <style>
        span.span-required {
            color: red;
        }
    </style>

    <script>
        //Only numeric allow
        $(document).on('keydown', 'minimum-order, .only-numeric', function(event) {
            let _text = $(this).val();
            let decimalCount = _text.split('.').length - 1;
            if (!(
                    (event.keyCode >= 48 && event.keyCode <= 57) ||
                    event.keyCode == 38 || event.keyCode == 40 ||
                    event.keyCode == 46 || event.keyCode == 8 ||
                    event.keyCode == 190 && decimalCount == 0 && _text.indexOf('.') == -1) ||
                event.key == "@" || event.key == "#" ||
                event.key == "!" || event.key == "$" ||
                event.key == "%" || event.key == "&" ||
                event.key == "*" || event.key == "(" ||
                event.key == ")" || event.key == "_" ||
                event.key == "-" || event.key == "+" ||
                event.key == "~" || event.key == "?" ||
                event.key == "!" || event.key == "/" ||
                event.key == "{" || event.key == "}" ||
                event.key == "\\"
            ) {
                event.preventDefault();
            } else {
                let xyz = checkNumber(_text);
                if (event.keyCode != 8 && xyz) {
                    event.preventDefault();
                }
            }

            if (parseInt(_text) <= 1) {
                $(this).val(1);
            }

            let maxChar = $(this).attr('max');
            if (maxChar && event.keyCode != 8) {
                if (_text.length >= maxChar) {
                    event.preventDefault();
                }
            }
        });

        function checkNumber(_text) {
            isAllow = false;
            let _array = _text.split('.');
            if (_array.length == 2 && _array[1].length >= 2) {
                isAllow = true;
            }
            //console.log("=============",_array[1].length);
            return isAllow;
        }

        $(document).on('keyup', '.email-validate', function() {
            let email = $(this).val();
            if (email.length < 5) {
                return;
            }

            let errorPanel = $(this).attr('error-class');
            if (isValidEmail(email)) {
                $('.' + errorPanel).html('');
            } else {
                $('.' + errorPanel).html('invalide email');
            }
        });

        function isValidEmail(email) {
            // Regular expression for basic email validation
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailPattern.test(email);
        }
    </script>
</body>

</html>
