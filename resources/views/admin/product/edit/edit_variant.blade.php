@extends('layouts.admin')

@section('content')
    <style>
        .red-cross-icon {
            color: red;
            font-size: 24px;
            /* Adjust the size as needed */
            position: absolute;
            top: 2px;
            /* Adjust the top position to place it above the image */
            right: 17px;
            /* Adjust the right position to place it on the top-right corner of the image */
            cursor: pointer;
        }
    </style>
    <div class="content-area">

        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Edit Variant') }}
                        <a class="add-btn" href="{{ route('admin-prod-edit', $product->id) }}?variants=true"><i
                                class="fas fa-arrow-left"></i>{{ __('Back') }}</a>
                    </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Products') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-prod-edit', $product->id) }}?variants=true">{{ __('Variant') }}</a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Edit Product Variant') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="add-product-content1 add-product-content2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ asset('assets/images/' . $gs->admin_loader) }})
                                no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            @include('alerts.admin.form-both')
                            <form method="POST" action="{{ route('product.update-variant') }}" accept-charset="UTF-8"
                                id="editVariantsForm" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Color:<span class="text-danger">*</span></strong>
                                            <select class="form-control" name="color_code" required="">
                                                <option value="">Select</option>
                                                @foreach ($color as $color_val)
                                                    <option value="{{ $color_val }}"
                                                        style="background-color:{{ $color_val }};color:white;"
                                                        {{ $data->color_code == $color_val ? 'selected' : '' }}>
                                                        {{ $color_val }}</option>
                                                @endforeach

                                            </select>
                                            <strong style="color:red;" id="errors-color_id"></strong>

                                        </div>
                                    </div>
                                    <input type="hidden" name="variant_id" value="{{ $data->id }}">
                                    <input type="hidden" name="product_id" value="{{ $data->product_id }}">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Size:<span class="text-danger">*</span></strong>
                                            <select class="form-control" name="size" required="">
                                                <option value="">Select</option>
                                                @foreach ($size as $size_val)
                                                    <option value="{{ $size_val }}"
                                                        {{ $data->size == $size_val ? 'selected' : '' }}>
                                                        {{ $size_val }}</option>
                                                @endforeach

                                            </select>
                                            <strong style="color:red;" id="errors-size_id"></strong>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Price $:<span class="text-danger">*</span></strong>

                                            <input type="text" class="form-control only-numeric" name="price"
                                                value="{{ $data->price }}" id="price" min="0" required="" />

                                            <strong style="color:red;" id="errors-price"></strong>

                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Discounted Price $:</strong>

                                            <input type="text" class="form-control only-numeric" name="discount_price"
                                                value="{{ $data->discount_price }}" id="discount-price" min="0" />

                                            <strong style="color:red;" id="errors-discount-price"></strong>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Minimum order:<span class="text-danger">*</span></strong>

                                            <input type="text" class="form-control only-numeric" name="minimum_order"
                                                value="{{ $data->minimum_order }}" id="minimum-order" required=""
                                                min="0" />

                                            <strong style="color:red;" id="errors-minimum-order"></strong>

                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Quantity:<span class="text-danger">*</span></strong>
                                            <input type="text" id="quantity" value="{{ $data->quantity }}"
                                                name="quantity" min="0" max="10000" value=""
                                                class="form-control only-numeric" required="" />

                                            <strong style="color:red;" id="errors-quantity"></strong>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Default:<span class="text-danger">*</span></strong>
                                            <div class="pt-2"> <input type="radio" name="default" value="1"
                                                    required="" {{ $data->default == 1 ? 'checked' : '' }}> Yes
                                                <input type="radio" name="default" value="2" required=""
                                                    {{ $data->default == 2 ? 'checked' : '' }}> No
                                            </div>


                                            <strong style="color:red;" id="errors-default"></strong>

                                        </div>
                                    </div>

                                    @if (!empty($data->variantImages))
                                        <div class="row mx-3">
                                            <div class="form-group">
                                                <strong>Images:</strong>
                                            </div>
                                            @foreach ($data->variantImages as $img_val)
                                                <div class="col-xs-12 col-sm-4 col-md-4 image-container"
                                                    id="images-{{ $img_val->id }}">
                                                    <div class="form-group">
                                                        <i class="fas fa-times-circle red-cross-icon"
                                                            data-href="{{ route('product.variant-image-delete', [$data->id]) }}"
                                                            data-id="{{ $img_val->id }}"
                                                            data-image="{{ $img_val->images }}"
                                                            data-target="#confirm-variant-image-delete"
                                                            data-toggle="modal"></i>
                                                        <img src="{{ asset('assets/product/' . $img_val->images) }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>Upload Images (up to 3):<span class="text-danger">*</span></strong>
                                            <input type="file" class="form-control" name="images[]" id="image-input"
                                                multiple>
                                            <strong style="color:red;" id="errors-images"></strong>
                                            <br>
                                            <div id="image-preview"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Front Image:<span class="text-danger">*</span></strong>
                                            <div class="img-upload">
                                                <div id="image-preview" class="img-preview"
                                                    style="background: url(
                                                        {{ $data->front_image
                                                            ? asset('assets/product/front/' . $data->front_image)
                                                            : asset('assets/images/noimage.png') }});">
                                                    <label for="image-upload" class="img-label" id="image-label"><i
                                                            class="icofont-upload-alt"></i>Upload Image</label>
                                                    <input type="file" name="front_image" class="img-upload"
                                                        id="image-upload">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Back Image:<span class="text-danger">*</span></strong>
                                            <div class="img-upload">
                                                <div id="image-preview1" class="img-preview1"
                                                    style="background:url(
                                                        {{ $data->back_image ? asset('assets/product/back/' . $data->back_image) : asset('assets/images/noimage.png') }});">
                                                    <label for="image-upload" class="img-label" id="image-label"><i
                                                            class="icofont-upload-alt"></i>Upload Image</label>
                                                    <input type="file" name="back_image" class="img-upload"
                                                        id="image-upload">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Front Image (Overlay):<span class="text-danger">*</span></strong>
                                            <div class="img-upload">
                                                <div id="image-preview" class="img-preview"
                                                    style="background: url(
                                                        {{ $data->front_image_overlay
                                                            ? asset('assets/product/front/' . $data->front_image_overlay)
                                                            : asset('assets/images/noimage.png') }});">
                                                    <label for="image-upload" class="img-label" id="image-label"><i
                                                            class="icofont-upload-alt"></i>Upload Image</label>
                                                    <input type="file" name="front_image_overlay" class="img-upload"
                                                        id="image-upload">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Back Image(Overlay):<span class="text-danger">*</span></strong>
                                            <div class="img-upload">
                                                <div id="image-preview1" class="img-preview1"
                                                    style="background:url(
                                                        {{ $data->back_image_overlay
                                                            ? asset('assets/product/back/' . $data->back_image_overlay)
                                                            : asset('assets/images/noimage.png') }});">
                                                    <label for="image-upload" class="img-label" id="image-label"><i
                                                            class="icofont-upload-alt"></i>Upload Image</label>
                                                    <input type="file" name="back_image_overlay" class="img-upload"
                                                        id="image-upload">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="loader text-center" style="display:none;"></div>
                                    <div class="form-group text-center" style="">
                                        <button type="submit"
                                            class="btn btn-primary black-button black-btn addVariantsSubmit-btn">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm-variant-image-delete" tabindex="-1" role="dialog" aria-labelledby="modal1"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p class="text-center">{{ __('Are you sure you want to delete this image?') }}</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <form action="" class="d-inline delete-form" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="old_image" id="old-image">
                        <input type="hidden" id="old-id">
                        <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection


@section('scripts')
    {{-- TAGIT --}}
    <script type="text/javascript">
        const imageInput = document.getElementById('image-input');
        const preview = document.getElementById('image-preview');
        const errorsImages = $('#errors-images');

        imageInput.addEventListener('change', function() {
            // Clear previous previews
            preview.innerHTML = '';
            errorsImages.text('');

            // Ensure a maximum of 3 images are selected
            if (this.files.length > 3) {
                errorsImages.text('You can upload a maximum of 3 images.');
                imageInput.value = '';
                return false;
            }

            for (const file of this.files) {
                if (file.type.startsWith('image/')) {
                    const imgContainer = document.createElement('div');
                    imgContainer.className = 'image-container';

                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.width = 100;
                    img.height = 100;
                    imgContainer.appendChild(img);

                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Delete';
                    deleteButton.className = 'btn btn-danger delete-button';
                    deleteButton.addEventListener('click', () => {
                        imgContainer.remove();
                        // Also remove the corresponding file from the input element's files array
                        const index = Array.from(imageInput.files).indexOf(file);
                        if (index !== -1) {
                            let filesArray = Array.from(imageInput.files);
                            filesArray.splice(index, 1);
                            // Create a new FileList object with the updated files array
                            const newFileList = new DataTransfer();
                            filesArray.forEach(file => newFileList.items.add(file));
                            // Assign the new FileList to the input element
                            imageInput.files = newFileList.files;
                        }
                    });
                    imgContainer.appendChild(deleteButton);

                    preview.appendChild(imgContainer);
                }
            }
        });
    </script>


    {{-- TAGIT ENDS --}}
    <script type="text/javascript">
        $(document).ready(function() {
            // Function to validate and update error message
            function validateDiscountedPrice() {
                var price = parseFloat($('#price').val());
                var discountPrice = parseFloat($('#discount-price').val());
                var errorElement = $('#errors-discount-price');
    
                if (discountPrice > price) {
                    $('#discount-price').val('');
                    errorElement.text('Discounted Price should not be greater than Price.');
                } else {
                    errorElement.text('');
                }
            }
    
            // Listen for input events on Discounted Price field
            $('#discount-price').on('input', validateDiscountedPrice);
    
            // Listen for input events on Price field to clear error message
            $('#price').on('input', function() {
                $('#errors-discount-price').text('');
            });
        });
    </script>
    
@endsection
