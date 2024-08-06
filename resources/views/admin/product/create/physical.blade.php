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
                    <h4 class="heading">{{ __('Add Product') }}   <a class="add-btn" href="{{ route('admin-prod-index') }}"><i
                                class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Products') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-prod-index') }}">{{ __('All Products') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-prod-create') }}">{{ __('Add Product') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <form id="geniusform" action="{{ route('admin-prod-store') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            @include('alerts.admin.form-both')
            <div class="row">
                <div class="col-lg-8">
                    <div class="add-product-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product-description">
                                    <div class="body-area">
                                        <div class="gocover"
                                            style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Product Name') }}* </h4>
                                                    <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="text" class="input-field"
                                                    placeholder="{{ __('Enter Product Name') }}" name="name"
                                                    required="">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Product Sku') }}* </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="text" class="input-field"
                                                    placeholder="{{ __('Enter Product Sku') }}" name="sku"
                                                    required=""
                                                    value="{{ Str::random(3) . substr(time(), 6, 8) . Str::random(3) }}">
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Category') }}*</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <select id="cat" name="category_id" required="">
                                                    <option value="">{{ __('Select Category') }}</option>
                                                    @foreach ($cats as $cat)
                                                        <option data-href="{{ route('admin-subcat-load', $cat->id) }}"
                                                            value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Sub Category') }}*</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <select id="subcat" name="subcategory_id" disabled="">
                                                    <option value="">{{ __('Select Sub Category') }}</option>
                                                </select>
                                            </div>
                                        </div> --}}

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Type') }}*</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <select id="type" name="product_type[]" multiple required>
                                                    <option value="">{{ __('Select type') }}</option>
                                                    @foreach ($product_type as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div id="catAttributes"></div>
                                        <div id="subcatAttributes"></div>
                                        <div id="childcatAttributes"></div>



                                        {{-- <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">

                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <ul class="list">
                                                    <li>
                                                        <input class="checkclick1" name="product_condition_check"
                                                            type="checkbox" id="product_condition_check" value="1">
                                                        <label
                                                            for="product_condition_check">{{ __('Allow Product Condition') }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="showbox">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Product Condition') }}*</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <select name="product_condition">
                                                        <option value="2">{{ __('New') }}</option>
                                                        <option value="1">{{ __('Used') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">

                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <ul class="list">
                                                    <li>
                                                        <input class="checkclick1" name="shipping_time_check"
                                                            type="checkbox" id="check1" value="1">
                                                        <label
                                                            for="check1">{{ __('Allow Estimated Shipping Time') }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="showbox">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Product Estimated Shipping Time') }}*
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <input type="text" class="input-field"
                                                        placeholder="{{ __('Estimated Shipping Time') }}" name="ship">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">

                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <ul class="list">
                                                    <li>
                                                        <input class="checkclickc" name="color_check" type="checkbox"
                                                            id="check3" value="1">
                                                        <label for="check3">{{ __('Allow Product Colors') }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="showbox">

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">
                                                            {{ __('Product Colors') }}*
                                                        </h4>
                                                        <p class="sub-heading">
                                                            {{ __('(Choose Your Favorite Colors)') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <table class="table color-table" style="width:100%;">
                                                        <tbody>
                                                            <tr>
                                                                <th style="width:50%;padding:2px;">
                                                                    <input type="color" name="color_all[]"
                                                                        class="input-field"/>
                                                                </th>
                                                                <th style="width:50%;padding:2px;">
                                                                    <button class="btn btn-success color-add-more" type="button">+ Add More</button>
                                                                </th>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">

                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <ul class="list">
                                                    <li>
                                                        <input class="checkclicks" name="size_check" type="checkbox"
                                                            id="tcheck" value="1">
                                                        <label for="tcheck">{{ __('Allow Product Sizes') }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="showbox">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">
                                                            {{ __('Product Size') }}*
                                                        </h4>
                                                        <p class="sub-heading">
                                                            {{ __('(eg. S,M,L,XL,XXL,3XL,4XL)') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="select-input-tsize" id="tsize-section">
                                                        <div class="tsize-area dipr">
                                                            <!-- <span class="remove tsize-remove"><i
                                                                    class="fas fa-times"></i></span> -->
                                                            <input type="text" name="size_all[]"
                                                                class="input-field tsize"
                                                                placeholder="{{ __('Enter Product Size') }}">
                                                                <a href="javascript:;" id="tsize-btn" class="add-more ml-4 mb-4 btn-success"><i
                                                                class="fas fa-plus"></i>{{ __('Add More') }} </a>
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">
                                                        {{ __('Size Chart (Optional)') }}
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="file" class="form-control" name="size_chart">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">
                                                        {{ __('Product Description') }}*
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="text-editor">
                                                    <textarea class="nic-edit-p" name="details"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">
                                                        {{ __('Product Buy/Return Policy') }}
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="text-editor">
                                                    <textarea class="nic-edit-p" name="policy"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="checkbox-wrapper">
                                                    <input type="checkbox" name="seo_check" value="1"
                                                        class="checkclick" id="allowProductSEO" value="1">
                                                    <label for="allowProductSEO">{{ __('Allow Product SEO') }}</label>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="showbox">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Meta Tags') }} </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <ul id="metatags" class="myTags">
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">
                                                            {{ __('Meta Description') }} 
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="text-editor">
                                                        <textarea name="meta_description" class="input-field" placeholder="{{ __('Meta Description') }}"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="add-product-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product-description">
                                    <div class="body-area">
                                        {{-- <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Youtube Video URL') }}</h4>
                                                    <p class="sub-heading">{{ __('(Optional)') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input name="youtube" type="text" class="input-field"
                                                    placeholder="{{ __('Enter Youtube Video URL') }}">
                                            </div>
                                        </div> --}}

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">

                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="featured-keyword-area">
                                                    <div class="heading-area">
                                                        <h4 class="title">{{ __('Feature Tags') }}</h4>
                                                    </div>

                                                    <div class="feature-tag-top-filds" id="feature-section">
                                                        <div class="feature-area">
                                                            <span class="remove feature-remove"><i
                                                                    class="fas fa-times"></i></span>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <input type="text" name="features[]"
                                                                        class="input-field"
                                                                        placeholder="{{ __('Enter Your Keyword') }}">
                                                                </div>

                                                                <div class="col-lg-6">
                                                                    <div class="input-group colorpicker-component cp">
                                                                        <input type="text" name="colors[]"
                                                                            value="#000000" class="input-field cp" />
                                                                        <span class="input-group-addon"><i></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <a href="javascript:;" id="feature-btn" class="add-fild-btn"><i
                                                            class="icofont-plus"></i> {{ __('Add More Field') }}</a>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Tags') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <ul id="tags" class="myTags">
                                                </ul>
                                            </div>
                                        </div> --}}

                                        <div class="row text-center">
                                            <div class="col-6 offset-3">
                                                <button class="addProductSubmit-btn"
                                                    type="submit">{{ __('Create Product') }}</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery"
        aria-hidden="true">
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
                                    <label for="image-upload" id="prod_gallery"><i
                                            class="icofont-upload-alt"></i>{{ __('Upload File') }}</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <a href="javascript:;" class="upload-done" data-dismiss="modal"> <i
                                        class="fas fa-check"></i> {{ __('Done') }}</a>
                            </div>
                            <div class="col-sm-12 text-center">(
                                <small>{{ __('You can upload multiple Images.') }}</small> )</div>
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
    <script src="{{ asset('assets/admin/js/jquery.Jcrop.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.SimpleCropper.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select2.js') }}"></script>

    <script type="text/javascript">
        (function($) {
            "use strict";

            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "Select Products",
                    maximumSelectionLength: 4,
                });
            });

            // Gallery Section Insert

            $(document).on('click', '.remove-img', function() {
                var id = $(this).find('input[type=hidden]').val();
                $('#galval' + id).remove();
                $(this).parent().parent().remove();
            });

            $(document).on('click', '#prod_gallery', function() {
                $('#uploadgallery').click();
                $('.selected-image .row').html('');
                $('#geniusform').find('.removegal').val(0);
            });


            $("#uploadgallery").change(function() {
                var total_file = document.getElementById("uploadgallery").files.length;
                for (var i = 0; i < total_file; i++) {
                    $('.selected-image .row').append('<div class="col-sm-6">' +
                        '<div class="img gallery-img">' +
                        '<span class="remove-img"><i class="fas fa-times"></i>' +
                        '<input type="hidden" value="' + i + '">' +
                        '</span>' +
                        '<a href="' + URL.createObjectURL(event.target.files[i]) + '" target="_blank">' +
                        '<img src="' + URL.createObjectURL(event.target.files[i]) +
                        '" alt="gallery image">' +
                        '</a>' +
                        '</div>' +
                        '</div> '
                    );
                    $('#geniusform').append('<input type="hidden" name="galval[]" id="galval' + i +
                        '" class="removegal" value="' + i + '">')
                }

            });

            // Gallery Section Insert Ends

        })(jQuery);
    </script>

    <script type="text/javascript">
        (function($) {
            "use strict";

            $('.cropme').simpleCropper();

        })(jQuery);


        $(document).on('click', '#size-check', function() {
            if ($(this).is(':checked')) {
                $('#default_stock').addClass('d-none')
            } else {
                $('#default_stock').removeClass('d-none');
            }
        })
        $(document).ready(function() {
            // Initialize Select2 with placeholder
            $('#type').select2({
                placeholder: 'Select a type',
            });
        });
    </script>


    @include('partials.admin.product.product-scripts')
@endsection
