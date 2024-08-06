<style>
    .color-circle {
        display: inline-block;
        width: 20px;
        /* Adjust the size as needed */
        height: 20px;
        /* Adjust the size as needed */
        border-radius: 50%;
        /* Makes it a circle */
    }
</style>
<div class="col-xs-12 col-sm-12 col-md-12 table-responsive" style="background-color:#fff">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 text-right mb-2 mt-2">
            @if (request()->view != 'true')
                <button type="button" class="btn btn-primary black-button black-btn form-button" data-toggle="modal"
                    data-target="#variations-modal" onclick= "resetform()">Add
                    <i class="fa fa-plus" aria-hidden="true"></i> </button>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 text-right mb-2 mt-2">
            <table class="table">
                <caption style="display:none;">&nbsp;</caption>
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Color</th>
                        <th scope="col">Size</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        @if (request()->view != 'true')
                            <th scope="col">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="variants-table">
                    @if (!empty($product_variant->isNotEmpty()))
                        @foreach ($product_variant as $val)
                            <tr id="record_{{ $val->id }}">
                                <td scope="row">
                                    <span class="color-circle" style="background-color: {{ $val->color_code }};"></span>
                                </td>
                                <td>{{ $val->size }}</td>
                                <td>${{ $val->price }} </td>
                                <td>{{ $val->quantity }}</td>

                                @if (request()->view != 'true')
                                    <td>
                                        <a href="{{ route('product.show-variant', [$val->product_id, $val->id]) }}"
                                            class="btn btn-icon btn-sm btn-primary" data-toggle="tooltip" title=""
                                            data-original-title="Edit"><i class="fa fa-edit"></i>
                                        </a>
                                        <a class="btn btn-icon btn-sm btn-danger"
                                            data-href="{{ route('product.variant-delete', [$val->product_id, $val->id]) }}"
                                            data-variant="{{ $val->id }}" data-toggle="modal"
                                            data-target="#delete-variants" class="delete"><i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                @endif

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" style="text-align:center;" class="not-found">No result found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" id="variations-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myLargeModalLabel">Add Variant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Add Variant</p>
                <div class="container">
                    <form method="POST" action="{{ route('admin-product-variant') }}" accept-charset="UTF-8"
                        id="product-variant-form" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Color:<span class="text-danger">*</span></strong>
                                    <select class="form-control" name="color_code">
                                        <option value="">Select</option>
                                        @foreach ($color as $color_val)
                                            <option value="{{ $color_val }}"
                                                style="background-color:{{ $color_val }};color:white;">
                                                {{ $color_val }}</option>
                                        @endforeach

                                    </select>
                                    <strong class="errors error-color_code" style="color:red;"
                                        id="errors-color_id"></strong>

                                </div>
                            </div>
                            <input type="hidden" name="prod_id" value="{{ $data->id }}">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Size:<span class="text-danger">*</span></strong>
                                    <select class="form-control" name="size">
                                        <option value="">Select</option>
                                        @foreach ($size as $size_val)
                                            <option value="{{ $size_val }}">{{ $size_val }}</option>
                                        @endforeach

                                    </select>

                                    <strong class="errors error-size" style="color:red;" id="errors-size_id"></strong>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Price $:<span class="text-danger">*</span></strong>

                                    <input type="text" class="form-control only-numeric" name="price"
                                        id="price" min="0" />

                                    <strong class="errors error-price" style="color:red;" id="errors-price"></strong>

                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Discounted Price $:</strong>

                                    <input type="text" class="form-control only-numeric" name="discount_price"
                                        id="discount-price" min="0" />

                                    <strong class="errors error-discount_price" style="color:red;"
                                        id="errors-discount-price"></strong>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Minimum Order:<span class="text-danger">*</span></strong>
                                    <input type="text" class="form-control only-numeric" name="minimum_order"
                                        id="minimum-order" min="0" />

                                    <strong class="errors error-minimum_order" style="color:red;"
                                        id="errors-minimum-order"></strong>

                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Quantity:<span class="text-danger">*</span></strong>
                                    <input type="text" id="quantity" name="quantity" min="0"
                                        max="10000" value="" class="form-control only-numeric">

                                    <strong class="errors error-quantity" style="color:red;"
                                        id="errors-quantity"></strong>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Default:
                                        <span class="text-danger">*</span></strong>
                                    <div class="pt-2">
                                        <input type="radio" name="default" value="1"> Yes
                                        <input type="radio" name="default" checked="checked" value="2"> No
                                    </div>
                                    <strong class="errors error-default" style="color:red;"
                                        id="errors-default"></strong>

                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Images (up to 3):<span class="text-danger">*</span></strong>
                                    <input type="file" class="form-control img-upload1" name="images[]" id="image-input"
                                        multiple />
                                    <div id="image-preview" class="preview"></div>

                                    <strong class="errors error-images mt-2" style="color:red;"
                                        id="errors-images"></strong>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Front Image:<span class="text-danger">*</span></strong>
                                    <div class="img-upload">
                                        <div id="image-preview" class="img-preview preview"
                                            style="background: url({{ url('assets/images/noimage.png') }});">
                                            <label for="image-upload" class="img-label" id="image-label"><i
                                                    class="icofont-upload-alt"></i>Upload Image</label>
                                            <input type="file" name="front_image" class="img-upload img-upload1"
                                                id="image-upload" />
                                        </div>
                                    </div>
                                    <strong class="errors error-front_image" style="color:red;"
                                        id="errors-image-upload"></strong>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Back Image:<span class="text-danger">*</span></strong>
                                    <div class="img-upload">
                                        <div id="image-preview1" class="img-preview1 preview"
                                            style="background: url({{ url('assets/images/noimage.png') }});">
                                            <label for="image-upload" class="img-label" id="image-label"><i
                                                    class="icofont-upload-alt"></i>Upload Image</label>
                                            <input type="file" name="back_image" class="img-upload img-upload1"
                                                id="image-upload" />
                                        </div>

                                        <strong class="errors error-back_image" style="color:red;"
                                            id="errors-image-upload"></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Front Image (Overlay):<span class="text-danger">*</span></strong>
                                    <div class="img-upload">
                                        <div id="image-preview" class="img-preview preview"
                                            style="background: url({{ url('assets/images/noimage.png') }});">
                                            <label for="image-upload" class="img-label" id="image-label"><i
                                                    class="icofont-upload-alt"></i>Upload Image</label>
                                            <input type="file" name="front_image_overlay" class="img-upload img-upload1"
                                                id="image-upload" />
                                        </div>
                                    </div>

                                    <strong class="errors error-front_image_overlay" style="color:red;"
                                        id="errors-image-upload"></strong>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Back Image (Overlay):<span class="text-danger">*</span></strong>
                                    <div class="img-upload">
                                        <div id="image-preview1" class="img-preview1 preview"
                                            style="background: url({{ url('assets/images/noimage.png') }});">
                                            <label for="image-upload" class="img-label" id="image-label"><i
                                                    class="icofont-upload-alt"></i>Upload Image</label>
                                            <input type="file" name="back_image_overlay" class="img-upload img-upload1"
                                                id="image-upload" />
                                        </div>

                                        <strong class="errors error-back_image_overlay" style="color:red;"
                                            id="errors-image-upload"></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center errors error-other mt-3 mb-3 " style="color:red;"></div>
                        <div class="form-group text-center variants-save-btn" style="">
                            <button type="submit"
                                class="btn btn-primary black-button black-btn form-button form-button">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
<!-- delete variants model -->
<div class="modal fade" id="delete-variants" tabindex="-1" role="dialog" aria-labelledby="modal1"
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
                <p class="text-center">{{ __('Are you sure you want to delete?') }}</p>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                <form action="" class="d-inline delete-form" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- delete end variants model -->
<script>
    
    function resetform() {
    // Use vanilla JavaScript or jQuery consistently, here we choose jQuery
        $('#product-variant-form')[0].reset(); // Reset the form

        // Clear file input fields
        $('input[type="file"].img-uploade1').each(function() {
        $(this).val('');
        });

        // Clear the image preview
        $('#image-preview').html('');

        // Reset background images
        $('.preview').each(function() {
            $(this).css('background', 'url("http://127.0.0.1:8035/assets/images/noimage.png")');
        });
    }


    // JavaScript to display selected images in the preview area
    // JavaScript to display selected images in the preview area and limit to 3 images
    const imageInput = document.getElementById('image-input');
    const preview = document.getElementById('image-preview');
    const priceInput = document.getElementById('price');
    const discountPriceInput = document.getElementById('discount-price');

    imageInput.addEventListener('change', function() {
        preview.innerHTML = '';

        // Ensure a maximum of 3 images are selected
        if (this.files.length > 3) {
            $('#errors-images').text('You can upload a maximum of 3 images.');
            imageInput.value = '';
            return false;
        } else {
            $('#errors-images').text('');
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
    discountPriceInput.addEventListener('input', function() {
        const price = parseFloat(priceInput.value);
        const discountedPrice = parseFloat(discountPriceInput.value);

        if (discountedPrice > price) {
            $('#errors-discount-price').text('Discounted Price should not be greater than Price.');
            discountPriceInput.value = ''; // Clear the input field
        } else {
            $('#errors-discount-price').text('');
        }
    });
</script>
