@extends('layouts.front')

@section('content')
    <div class="loader">
        {{-- <img  src="{{asset('assets/images/'.$gs->admin_loader)}}" alt=""> --}}
        <img  src="{{asset('assets/images/'.$gs->loader)}}" alt="">
    </div>
    @include('partials.global.common-header')
    @include('partials.product-details.top')

    @includeIf('partials.global.common-footer')

    @if ($gs->is_report)
        @if (Auth::check())
            {{-- REPORT MODAL SECTION --}}

            <div class="modal fade report" id="report-modal"
			    tabindex="-1" role="dialog" aria-labelledby="report-modal-Title"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="gocover"
                                style="background: url({{ asset('assets/images/' . $gs->loader) }})
								no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>

                            <div class="login-area">
                                <div class="header-area forgot-passwor-area">
                                    <h4 class="title text-center">{{ __('REPORT PRODUCT') }}</h4>
                                    <p class="text">{{ __('Please give the following details') }}</p>
                                </div>
                                <div class="login-form">

                                    <form id="reportform" action="{{ route('product.report') }}" method="POST">

                                        @include('includes.admin.form-login')

                                        {{ csrf_field() }}
                                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                        <input type="hidden" name="product_id" value="{{ $productt->id }}">
                                        <div class="form-input">
                                            <input type="text" name="title" class="User Name form-control border"
                                                placeholder="{{ __('Enter Report Title') }}" required="">

                                        </div>
                                        <br>

                                        <div class="form-input">
                                            <textarea name="note" class="User Name form-control border"
											placeholder="{{ __('Enter Report Note') }}"
                                                required=""></textarea>
                                        </div>

                                        <button type="submit" class="submit-btn">{{ __('SUBMIT') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- REPORT MODAL SECTION ENDS --}}
        @endif
    @endif
@endsection

@section('script')
    <script src="{{ asset('assets/front/js/jquery.elevatezoom.js') }}"></script>

    <!-- Initializing the slider -->
    <script type="text/javascript">
        lazy();

        function _initZoom()
        {
            $("#single-image-zoom").elevateZoom({
                gallery: 'gallery_09',
                zoomType: "inner",
                cursor: "crosshair",
                galleryActiveClass: 'active',
                imageCrossfade: true,
                loadingIcon: 'http://www.elevateweb.co.uk/spinner.gif'
            });
            //pass the images to Fancybox
            $("#single-image-zoom").bind("click", function(e) {
                var ez = $('#single-image-zoom').data('elevateZoom');
                $.fancybox(ez.getGalleryList());
                return false;
            });
        }

        (function($) {
            "use strict";

            _initZoom();

            $(document).on("submit", "#emailreply", function() {
                var token = $(this).find('input[name=_token]').val();
                var subject = $(this).find('input[name=subject]').val();
                var message = $(this).find('textarea[name=message]').val();
                var email = $(this).find('input[name=email]').val();
                var name = $(this).find('input[name=name]').val();
                var user_id = $(this).find('input[name=user_id]').val();
                $('#eml').prop('disabled', true);
                $('#subj').prop('disabled', true);
                $('#msg').prop('disabled', true);
                $('#emlsub').prop('disabled', true);
                $.ajax({
                    type: 'post',
                    url: "{{ URL::to('/user/user/contact') }}",
                    data: {
                        '_token': token,
                        'subject': subject,
                        'message': message,
                        'email': email,
                        'name': name,
                        'user_id': user_id
                    },
                    success: function(data) {
                        $('#eml').prop('disabled', false);
                        $('#subj').prop('disabled', false);
                        $('#msg').prop('disabled', false);
                        $('#subj').val('');
                        $('#msg').val('');
                        $('#emlsub').prop('disabled', false);
                        if (data == 0)
                            toastr.error("Email Not Found");
                        else
                            toastr.success("Message Sent");
                        $('#vendorform').modal('hide');
                    }
                });
                return false;
            });

        })(jQuery);

        $('.add-to-affilate').on('click', function() {

            var value = $(this).data('href');
            var tempInput = document.createElement("input");
            tempInput.style = "position: absolute; left: -1000px; top: -1000px";
            tempInput.value = value;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            toastr.success('Affiliate Link Copied');

        });
    </script>

    <script>
        $(document).on('click', '.color-change', function(e) {
            e.preventDefault(0);
            let url=$(this).attr('href');

           // $('.loader').fadeIn(1000)
            $.get(url +'?color-change=true', function(response) {
                //$('.loader').fadeOut(1000)
                $('.product-panel').html(response);
                _initZoom();
            });
        });
    </script>
@endsection
