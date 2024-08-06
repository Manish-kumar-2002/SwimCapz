<div class="modal fade" id="billing-details-edit" role="dialog" aria-labelledby="billing-details-edit" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="submit-loader">
                <img src="{{ asset('assets/images/' . $gs->admin_loader) }}" alt="">
            </div>
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Billing Details') }}</h5>
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
                                        <form action="{{ route('admin-order-update', $order->id) }}" method="POST"
                                            enctype="multipart/form-data" onsubmit="return validateForm()">
                                            <input type="hidden" id="hiddenorder" name="_token" value="">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Name') }}
                                                            <span class="span-required">*</span>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <input type="text" value='{{ $order->billing_name }}' class="input-field" name="billing_name"
                                                        id="billing_name" placeholder="Name" 
                                                        value='{{ $order->billing_name }}' required>
                                                    <span id="nameError" style="color: red;font-weight: bold;"></span>
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
                                                    <input type="email" class="input-field" name="billing_email"
                                                        placeholder="{{ __('Email') }}" required=""
                                                        value="{{ $order->billing_email }}" id="billing_email">
                                                    <span id="emailError" style="color: red;font-weight: bold;"></span>
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
                                                    <input type="text" class="input-field mobile only-numeric"
                                                        max="10" name="billing_phone" id="billing_phone"
                                                        placeholder="{{ __('Phone') }}" required=""
                                                        value="{{ $order->billing_phone }}">
                                                    <span id="phoneError" style="color: red;font-weight: bold;"></span>
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
                                                    <input type="text" class="input-field" id="billing_address" name="billing_address"
                                                        placeholder="{{ __('Address') }}" required=""
                                                        value="{{ $order->billing_address }}">
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
                                                    <select class="input-field billing-countries" name="billing_country"
                                                        required="">
                                                        <option value="">{{ __('Select Country') }}</option>
                                                        @foreach (Helper::getCountries() as $data)
                                                            <option value="{{ $data->id }}"
                                                                {{ $order->billing_country == $data->id ? 'selected' : '' }}>
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
                                                    <select class="input-field billing-states" name="billing_state"
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
                                                    <input type="text" class="input-field" id="billing_city" name="billing_city"
                                                        placeholder="{{ __('City') }}"
                                                        value="{{ $order->billing_city }}">
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
                                                    <input type="text" class="input-field only-numeric"
                                                        max="6" name="billing_zip" id="billing_postal_code"
                                                        placeholder="{{ __('Postal Code') }}" required=""
                                                        value="{{ $order->billing_zip }}">
                                                    <span id="postalCodeError"
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
                                                        type="submit">{{ __('Submit') }}</button>
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
