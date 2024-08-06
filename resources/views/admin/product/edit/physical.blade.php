@extends('layouts.admin')
@section('styles')
    <link href="{{ asset('assets/admin/css/product.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/jquery.Jcrop.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/Jcrop-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/select2.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">
                        @if (request()->view == 'true')
                            {{ __('View Product') }}&nbsp;&nbsp;
                        @else
                            {{ __('Edit Product') }}&nbsp;&nbsp;
                        @endif

                        {{-- <a class="add-btn" href="{{ url()->previous() }}"><i --}}
                        <a class="add-btn" href="{{ route('admin-prod-index') }}"><i
                                class="fas fa-arrow-left"></i>{{ __('Back') }}</a>
                    </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-prod-index') }}">{{ __('Products') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ request()->view == 'true' ? __('View') : __('Edit') }}</a>
                        </li>
                        @unless (request()->view == 'true')
                            <li>
                                <a href="javascript:;">{{ __('Add Variant') }}</a>
                            </li>
                        @endunless
                    </ul>
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab"
                    aria-controls="tab1" aria-selected="true">Product</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2"
                    aria-selected="false">Variants</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                @include('admin.product.edit.edit_product')
            </div>
            <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                <div class="gocover loader"
                    style="background: url({{ asset('assets/images/' . $gs->admin_loader) }})
                    no-repeat scroll center center rgba(45, 45, 45, 0.5);z-index:9999">
                </div>
                @include('admin.product.edit.list_variant')

            </div>
        </div>

    </div>

    <div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Image Gallery') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="top-area">
                        <div class="row">
                            <div class="col-sm-6 text-right">
                                <div class="upload-img-btn">
                                    <form method="POST" enctype="multipart/form-data" id="form-gallery">
                                        @csrf
                                        <input type="hidden" id="pid" name="product_id" value="">
                                        <input type="file" name="gallery[]" class="hidden" id="uploadgallery"
                                            accept="image/*" multiple>
                                        <label for="image-upload" id="prod_gallery"><i
                                                class="icofont-upload-alt"></i>{{ __('Upload File') }}</label>
                                    </form>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <a href="javascript:;" class="upload-done" data-dismiss="modal"> <i
                                        class="fas fa-check"></i> {{ __('Done') }}</a>
                            </div>
                            <div class="col-sm-12 text-center">
                                (
                                <small>
                                    {{ __('You can upload multiple Images.') }}
                                </small>
                                )
                            </div>
                        </div>
                    </div>
                    <div class="gallery-images">
                        <div class="selected-image">
                            <div class="row">


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        // Gallery Section Update

        $(function($) {
            "use strict";

            $(document).on("click", ".set-gallery", function() {
                var pid = $(this).find('input[type=hidden]').val();
                $('#pid').val(pid);
                $('.selected-image .row').html('');
                $.ajax({
                    type: "GET",
                    url: "{{ route('admin-gallery-show') }}",
                    data: {
                        id: pid
                    },
                    success: function(data) {
                        if (data[0] == 0) {
                            $('.selected-image .row').addClass('justify-content-center');
                            $('.selected-image .row').html(
                                '<h3>{{ __('No Images Found.') }}</h3>');
                        } else {
                            $('.selected-image .row').removeClass('justify-content-center');
                            $('.selected-image .row h3').remove();
                            var arr = $.map(data[1], function(el) {
                                return el
                            });

                            for (var k in arr) {
                                $('.selected-image .row').append('<div class="col-sm-6">' +
                                    '<div class="img gallery-img">' +
                                    '<span class="remove-img"><i class="fas fa-times"></i>' +
                                    '<input type="hidden" value="' + arr[k]['id'] + '">' +
                                    '</span>' +
                                    '<a href="' +
                                    '{{ asset('assets/images/galleries') . '/' }}' + arr[k]
                                    [
                                        'photo'
                                    ] + '" target="_blank">' +
                                    '<img src="' +
                                    '{{ asset('assets/images/galleries') . '/' }}' + arr[k]
                                    [
                                        'photo'
                                    ] + '" alt="gallery image">' +
                                    '</a>' +
                                    '</div>' +
                                    '</div>');
                            }
                        }

                    }
                });
            });


            $(document).on('click', '.remove-img', function() {
                var id = $(this).find('input[type=hidden]').val();
                $(this).parent().parent().remove();
                $.ajax({
                    type: "GET",
                    url: "{{ route('admin-gallery-delete') }}",
                    data: {
                        id: id
                    }
                });
            });

            $(document).on('click', '#prod_gallery', function() {
                $('#uploadgallery').click();
            });


            $("#uploadgallery").change(function() {
                $("#form-gallery").submit();
            });

            $(document).on('submit', '#form-gallery', function() {
                $.ajax({
                    url: "{{ route('admin-gallery-store') }}",
                    method: "POST",
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data != 0) {
                            $('.selected-image .row').removeClass('justify-content-center');
                            $('.selected-image .row h3').remove();
                            var arr = $.map(data, function(el) {
                                return el
                            });
                            for (var k in arr) {
                                $('.selected-image .row').append('<div class="col-sm-6">' +
                                    '<div class="img gallery-img">' +
                                    '<span class="remove-img"><i class="fas fa-times"></i>' +
                                    '<input type="hidden" value="' + arr[k]['id'] + '">' +
                                    '</span>' +
                                    '<a href="' +
                                    '{{ asset('assets/images/galleries') . '/' }}' + arr[k]
                                    [
                                        'photo'
                                    ] + '" target="_blank">' +
                                    '<img src="' +
                                    '{{ asset('assets/images/galleries') . '/' }}' + arr[k]
                                    [
                                        'photo'
                                    ] + '" alt="gallery image">' +
                                    '</a>' +
                                    '</div>' +
                                    '</div>');
                            }
                        }

                    }

                });
                return false;
            });

        })(jQuery);

        // Gallery Section Update Ends
    </script>

    <script src="{{ asset('assets/admin/js/jquery.Jcrop.js') }}"></script>

    <script src="{{ asset('assets/admin/js/jquery.SimpleCropper.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select2.js') }}"></script>

    <script type="text/javascript">
        (function($) {
            "use strict";

            $('.cropme').simpleCropper();

            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "Select Products",
                    maximumSelectionLength: 4,
                });
            });

        })(jQuery);
    </script>


    <script type="text/javascript">
        (function($) {
            "use strict";

            $(document).ready(function() {

                let html =
                    `<img src="{{ empty($data->photo) ? asset('assets/images/noimage.png') : (filter_var($data->photo, FILTER_VALIDATE_URL) ? $data->photo : asset('assets/images/products/' . $data->photo)) }}" alt="">`;
                $(".span4.cropme").html(html);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

            });


            $('.ok').on('click', function() {

                setTimeout(
                    function() {


                        var img = $('#feature_photo').val();

                        $.ajax({
                            url: "{{ route('admin-prod-upload-update', $data->id) }}",
                            type: "POST",
                            data: {
                                "image": img
                            },
                            success: function(data) {
                                if (data.status) {
                                    $('#feature_photo').val(data.file_name);
                                }
                                if ((data.errors)) {
                                    for (var error in data.errors) {
                                        $.notify(data.errors[error], "danger");
                                    }
                                }
                            }
                        });

                    }, 1000);



            });

        })(jQuery);
    </script>

    <script type="text/javascript">
        (function($) {
            "use strict";

            $('#imageSource').on('change', function() {
                var file = this.value;
                if (file == "file") {
                    $('#f-file').show();
                    $('#f-link').hide();
                }
                if (file == "link") {
                    $('#f-file').hide();
                    $('#f-link').show();
                }
            });



            $(document).on('click', '#size-check', function() {
                if ($(this).is(':checked')) {
                    $('#default_stock').addClass('d-none')
                } else {
                    $('#default_stock').removeClass('d-none');
                }
            })



        })(jQuery);
        $(document).ready(function() {
            // Initialize Select2 with placeholder
            $('#type').select2({
                placeholder: 'Select a type',
            });
        });


        $(document).ready(function() {
            $("form#product-variant-form").submit(function(event) {
                event.preventDefault();

                $('.loader').show();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        $('.loader').hide();

                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);

                        clear();
                    },
                    error: function(response) {
                        $('.loader').hide();

                        let errors = response.responseJSON.errors;
                        $.each(errors, function(key, val) {
                            $('.error-' + key).html(val[0]);
                        });

                        clear();
                    }
                });

            });


            /* if varient page active previously */
            @if ($isVarients)
                $('#tab2-tab').trigger('click');
            @endif

        });

        $(document).on('focus', 'input, select', function() {
            $('.errors').html('');
        });

        function clear() {

            setTimeout(function() {
                $('.errors').html('');
            }, 3000);

        }
    </script>
    @include('admin.product.edit.pricing_break_script')
    @include('partials.admin.product.product-scripts')
@endsection
