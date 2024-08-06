<div class="modal fade" id="shipping-details-edit" tabindex="-1" role="dialog" aria-labelledby="shipping-details-edit"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="submit-loader">
                <img src="{{ asset('assets/images/' . $gs->admin_loader) }}" alt="">
            </div>
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Shipping Details') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="content-area">

                    <div class="add-product-content1">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product-description">
                                    <div class="body-area">
                                        <form action="{{ url('admin/order/update/'.$order->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            <input type="hidden" id="hiddenshipping" name="_token" value="">

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Name') }}
                                                            <span class="span-required">*</span>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <input type="text" class="input-field" name="shipping_name"
                                                        id="shipping_name" placeholder="{{ __('Name') }}"
                                                        required=""
                                                        value="{{ $order->shipping_name == null ? $order->customer_name : $order->shipping_name }}">
                                                    <span id="shippingnameError"
                                                        style="color: red;font-weight: bold;"></span>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Email') }}
                                                            <span class="span-required">*</span>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <input type="email" class="input-field" name="shipping_email"
                                                        placeholder="{{ __('Email') }}" required=""
                                                        value="{{ $order->shipping_email == null ? $order->customer_email : $order->shipping_email }}"
                                                        id="shipping_email">
                                                    <span id="shippingemailError"
                                                        style="color: red;font-weight: bold;"></span>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Phone') }}
                                                            <span class="span-required">*</span>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <input type="text" class="input-field mobile"
                                                        name="shipping_phone" id="shipping_phone"
                                                        placeholder="{{ __('Phone') }}" required=""
                                                        value="{{ $order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone }}">
                                                    <span id="shippingphoneError"
                                                        style="color: red;font-weight: bold;"></span>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Address') }}
                                                            <span class="span-required">*</span>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <input type="text" class="input-field" id="shipping_address"
                                                        name="shipping_address" placeholder="{{ __('Address') }}"
                                                        required=""
                                                        value="{{ $order->shipping_address == null ? $order->customer_address : $order->shipping_address }}">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Country') }}
                                                            <span class="span-required">*</span>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <select class="input-field shipping-countries"
                                                        name="shipping_country" required="">
                                                        <option value="">{{ __('Select Country') }}</option>
                                                        @foreach (Helper::getCountries() as $data)
                                                            <option value="{{ $data->id }}"
                                                                {{ $order->shipping_country == $data->id ? 'selected' : '' }}>
                                                                {{ $data->country_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('State') }}
                                                            <span class="span-required">*</span>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <select class="input-field shipping-states" name="shipping_state"
                                                        required="">
                                                        <option value="">{{ __('Select State') }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('City') }}
                                                            <span class="span-required">*</span>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <input type="text" class="input-field" name="shipping_city"
                                                        id = "shipping_city" placeholder="{{ __('City') }}"
                                                        value="{{ $order->shipping_city == null ? $order->customer_city : $order->shipping_city }}" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Postal Code') }}
                                                            <span class="span-required">*</span>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <input type="text" class="input-field" name="shipping_zip"
                                                        id="shipping_postal_code"
                                                        placeholder="{{ __('Postal Code') }}" required=""
                                                        value="{{ $order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip }}">
                                                    <span id="shippingpostalCodeError"
                                                        style="color: red; font-weight: bold;"></span>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="left-area">

                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <button class="addProductSubmit-btn"
                                                        type="submit">{{ __('Submit') }}
                                                    </button>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>

</div>
