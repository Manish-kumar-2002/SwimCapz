<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="GeniusCart-New - Multivendor Ecommerce system">
    <meta name="author" content="GeniusOcean">

    @if (isset($page->meta_tag) && isset($page->meta_description))
        <meta name="keywords" content="{{ $page->meta_tag }}">
        <meta name="description" content="{{ $page->meta_description }}">
        <title>{{ $gs->title }}</title>
    @elseif(isset($blog->meta_tag) && isset($blog->meta_description))
        <meta property="og:title" content="{{ $blog->title }}" />
        <meta property="og:description"
            content="{{ $blog->meta_description != null ? $blog->meta_description : strip_tags($blog->meta_description) }}" />
        <meta property="og:image" content="{{ asset('assets/images/blogs/' . $blog->photo) }}" />
        <meta name="keywords" content="{{ $blog->meta_tag }}">
        <meta name="description" content="{{ $blog->meta_description }}">
        <title>{{ $gs->title }}</title>
    @elseif(isset($productt))
        <meta name="keywords" content="{{ !empty($productt->meta_tag) ? implode(',', $productt->meta_tag) : '' }}">
        <meta name="description"
            content="{{ $productt->meta_description != null ? $productt->meta_description : strip_tags($productt->description) }}">
        <meta property="og:title" content="{{ $productt->name }}" />
        <meta property="og:description"
            content="{{ $productt->meta_description != null ? $productt->meta_description : strip_tags($productt->description) }}" />
        <meta property="og:image" content="{{ asset('assets/images/thumbnails/' . $productt->thumbnail) }}" />
        <meta name="author" content="GeniusOcean">
        <title>{{ substr($productt->name, 0, 11) . '-' }}{{ $gs->title }}</title>
    @else
        <meta property="og:title" content="{{ $gs->title }}" />
        <meta property="og:image" content="{{ asset('assets/images/' . $gs->logo) }}" />
        <meta name="keywords" content="{{ $seo->meta_keys }}">
        <meta name="author" content="GeniusOcean">
        <title>{{ $gs->title }}</title>
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/' . $gs->favicon) }}" />
    <!-- Google Fonts -->
    @if ($default_font->font_value)
        <link
            href="https://fonts.googleapis.com/css?family={{ $default_font->font_value }}:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Roboto:ital,wght@0,400;0,500;0,700;1,300&&display=swap"
            rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700;800;900&display=swap"
            rel="stylesheet">
    @endif

    <link rel="stylesheet"
        href="{{ asset('assets/front/css/styles.php?color=' . str_replace('#', '', $gs->colors) . '&header_color=' . $gs->header_color) }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/plugin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/webfonts/flaticon/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/template.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/front/css/category/default.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/custom/countrySelect.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/custom/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/custom/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/custom/global.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/custom/style2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/custom/responsive.css') }}">
    @if ($default_font->font_family)
        <link rel="stylesheet" id="colorr"
            href="{{ asset('assets/front/css/font.php?font_familly=' . $default_font->font_family) }}">
    @else
        <link rel="stylesheet" id="colorr"
            href="{{ asset('assets/front/css/font.php?font_familly=' . 'Open Sans') }}">
    @endif

    @if (!empty($seo->google_analytics))
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ $seo->google_analytics }}');
        </script>
    @endif
    @if (!empty($seo->facebook_pixel))
        <script>
            ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $seo->facebook_pixel }}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ $seo->facebook_pixel }}&ev=PageView&noscript=1" />
        </noscript>
    @endif

    <style>
        .errors{
            color:red;
            font-weight:bold
        }
    </style>
    @yield('css')
</head>

<body>

    <div id="page_wrapper" class="bg-white">
        <div class="loader">
            <div class="spinner"></div>
        </div>

        @yield('content')

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title text-center" id="exampleModalLabel">You May Like </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="cross__product__show">
                        {{-- dsfasdasdf --}}
                    </div>

                </div>
            </div>
        </div>


    </div>

    <!-- Auto Modal -->
    <div class="modal fade" id="common-modal" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog user-information modal-lg">
            <div class="modal-content">
                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block"></h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="modal-panel"><!-- content --></div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center footer-content">
                    <button type="button" class="btn btn-default"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal end -->


    <script>
        var mainurl = "{{ url('/') }}";
        var gs = {!! json_encode(
            DB::table('generalsettings')
            ->where('id', '=', 1)
            ->first(['is_loader', 'decimal_separator', 'thousand_separator', 'is_cookie', 'is_talkto', 'talkto']),
        ) !!};
        var ps_category = {{ $ps->category }};

        var lang = {
            'days': '{{ __('Days') }}',
            'hrs': '{{ __('Hrs') }}',
            'min': '{{ __('Min') }}',
            'sec': '{{ __('Sec') }}',
            'cart_already': '{{ __('Already Added To Card.') }}',
            'cart_out': '{{ __('Out Of Stock') }}',
            'cart_success': '{{ __('Successfully Added To Cart.') }}',
            'cart_empty': '{{ __('Cart is empty.') }}',
            'coupon_found': '{{ __('Coupon Found.') }}',
            'no_coupon': '{{ __('No Coupon Found.') }}',
            'already_coupon': '{{ __('Coupon Already Applied.') }}',
            'enter_coupon': '{{ __('Enter Coupon First') }}',
            'minimum_qty_error': '{{ __('Minimum Quantity is:') }}',
            'affiliate_link_copy': '{{ __('Affiliate Link Copied Successfully') }}'
        };
    </script>
    <!-- Include Scripts -->
    <script src="{{ asset('assets/front/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/jquery-ui.min.js') }}"></script>

    <script src="{{ asset('assets/front/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/plugin.js') }}"></script>
    <script src="{{ asset('assets/front/js/waypoint.js') }}"></script>
    <script src="{{ asset('assets/front/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/wow.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/front/js/lazy.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/front/js/lazy.plugin.js') }}"></script>
    <script src="{{ asset('assets/front/js/jquery.countdown.js') }}"></script>
    @yield('zoom')
    <script src="{{ asset('assets/front/js/paraxify.js') }}"></script>
    <script src="{{ asset('assets/front/js/select2.min.js') }}"></script>


    <script src="{{ asset('assets/front/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/custom.js') }}"></script>
    <script src="{{ asset('assets/front/js/main.js') }}"></script>
    <script src="{{ asset('assets/front/custom-js/slick.js') }}"></script>
    <script src="{{ asset('assets/front/custom-js/jquery.nice-select.js') }}"></script>
    <script src="{{ asset('assets/front/custom-js/custom2.js') }}"></script>

    <script>
        lazy();

        function lazy() {
            $(".lazy").Lazy({
                scrollDirection: 'vertical',
                effect: "fadeIn",
                effectTime: 1000,
                threshold: 0,
                visibleOnly: false,
                onError: function(element) {
                    console.log('error loading ' + element.data('src'));
                }
            });
        }
    </script>

    <script>
        function formatState (opt) {
            
            if (!opt.id) {
                return opt.text;
            }

            var optimage = $(opt.element).attr('data-img');
            console.log(optimage)
            if(!optimage){
                return opt.text;
            } else {
                var $opt = $(
                    '<span class="country-flag"><img src="' + optimage + '" /> ' + opt.text + '</span>'
                );
                return $opt;
            }
        };

        $(document).ready(function() {
            $('.select2-js-init').select2({
                minimumResultsForSearch: Infinity,
                templateResult: formatState,
                templateSelection: formatState
            });
            $('.select2-js-search-init').select2();
        });


        $(document).on("submit", ".subscribeform", function(e) {
            var $this = $(this);
            e.preventDefault();
            $this.find("input").prop("readonly", true);
            $this.find("button").prop("disabled", true);
            $("#sub-btn").prop("disabled", true);
            $.ajax({
                method: "POST",
                url: $(this).prop("action"),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.errors) {
                        for (var error in data.errors) {
                            toastr.error(data.errors[error]);
                        }
                    } else {
                        toastr.success(data);
                        $this.find("input[name=email]").val("");
                    }
                    $this.find("input").prop("readonly", false);
                    $this.find("button").prop("disabled", false);
                },
            });
        });
    </script>



    @php
        echo Toastr::message();
    @endphp
    @yield('script')

    <!--auto link modal -->
    <script>
        $(document).on('click', '.link-modal', function(e) {
            e.preventDefault(0);

            let url = $(this).attr('link-url');
            let title = $(this).attr('link-title');
            let isFooter = $(this).attr('link-isFooter') ? 1 : 0;

            $('#common-modal').modal('show');
            $('#common-modal .modal-panel').html('<p>Loading.....</p>');
            $('#common-modal .modal-title').html(title ? title : '');
            $.get(url, function(response) {
                $('#common-modal .modal-panel').html(response);

                if (isFooter) {
                    $('#common-modal .footer-content').html('');
                }

            });
        });

        // when dynamic attribute changes
        $(".attribute-input, #sortby, #pageby").on('change', function() {
            $(".ajax-loader").show();
            filter();
        });

        /* search product */
        $("#prod_name").on('keyup', function(e) {
            $(".ajax-loader").show();
            filter();
        });

        function filter() {
            let filterlink = '';

            if ($("#prod_name").val() != '') {
                if (filterlink == '') {
                    filterlink +=
                        '{{ route('front.category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}' +
                        '?search=' + $("#prod_name").val();
                } else {
                    filterlink += '&search=' + $("#prod_name").val();
                }
                console.log('#prod_name', filterlink);
            }



            $(".attribute-input").each(function() {
                if ($(this).is(':checked')) {

                    if (filterlink == '') {
                        filterlink +=
                            '{{ route('front.category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}' +
                            '?' + $(this).attr('name') + '=' + $(this).val();
                    } else {
                        filterlink += '&' + encodeURI($(this).attr('name')) + '=' + $(this).val();

                    }
                    console.log('.attribute-input', filterlink);
                }
            });

            if ($("#sortby").val() != '') {
                if (filterlink == '') {
                    filterlink +=
                        '{{ route('front.category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}' +
                        '?' + $("#sortby").attr('name') + '=' + $("#sortby").val();
                } else {
                    filterlink += '&' + $("#sortby").attr('name') + '=' + $("#sortby").val();
                }

                console.log('#sortby', filterlink);
            }

            if ($("#pageby").val() != '') {
                if (filterlink == '') {
                    filterlink +=
                        '{{ route('front.category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}' +
                        '?' + $("#pageby").attr('name') + '=' + $("#pageby").val();
                } else {
                    filterlink += '&' + $("#pageby").attr('name') + '=' + $("#pageby").val();
                }

                //console.log('#pageby',filterlink);
            }

            {{-- console.log('==========>',filterlink); --}}
            $("#ajaxContent").load(encodeURI(filterlink), function(data) {
                // add query string to pagination
                addToPagination();
                $(".ajax-loader").fadeOut(1000);
            });
        }


        //   append parameters to pagination links
        function addToPagination() {


            // add to attributes in pagination links
            $('ul.pagination li a').each(function() {
                let url = $(this).attr('href');
                let queryString = '?' + url.split('?')[1]; // "?page=1234...."

                let urlParams = new URLSearchParams(queryString);
                let page = urlParams.get('page'); // value of 'page' parameter

                let fullUrl =
                    '{{ route('front.category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}?page=' +
                    page + '&search=' + '{{ urlencode(request()->input('search')) }}';

                $(".attribute-input").each(function() {
                    if ($(this).is(':checked')) {
                        fullUrl += '&' + encodeURI($(this).attr('name')) + '=' + encodeURI($(this).val());
                    }
                });

                if ($("#sortby").val() != '') {
                    fullUrl += '&sort=' + encodeURI($("#sortby").val());
                }

                if ($("#min_price").val() != '') {
                    fullUrl += '&min=' + encodeURI($("#min_price").val());
                }

                if ($("#max_price").val() != '') {
                    fullUrl += '&max=' + encodeURI($("#max_price").val());
                }

                if ($("#pageby").val() != '') {
                    fullUrl += '&pageby=' + encodeURI($("#pageby").val());
                }


                $(this).attr('href', fullUrl);
            });
        }
    </script>


    <script type="text/javascript">
        $(document).on('click', '.colorCombinations', function(e) {
            e.preventDefault(0);

            let _href = $(this).attr('data-href');
            let _link = $(this).attr('data-img');
            let _product = $(this).attr('data-product-class');
            let _price = $(this).attr('data-price');
            let _discount = $(this).attr('data-discount');

            //console.log('=========>', _href, _link, _product, _price, _discount);

            $('.' + _product).attr('href', _href);
            $('.' + _product).find('.lazy-product').attr('src', _link);

            $('.' + _product + '-price').text(_price);
            $('.' + _product + '-discount_price').text(_discount);
        });

        /* home page when design click */
        $(document).on('click', '.designNow', function() {
            let clickedLink = $(this).attr('data-link-class');

            let _link = $('.slide-card .cap-wrap .' + clickedLink);
            window.location.href = _link.attr('href');
        });


        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @elseif (Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @elseif (Session::has('info'))
            toastr.info("{{ Session::get('info') }}");
        @endif
    </script>

    @include('globalValidation')
</body>

</html>
