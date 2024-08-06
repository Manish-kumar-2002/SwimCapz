@extends('layouts.load')

@section('content')
    <div class="content-area">
        <div class="add-product-content1">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            @include('alerts.admin.form-error')
                            <form id="geniusformdata" action="{{ route('admin-currency-update', $data->id) }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Name') }} *</h4>
                                            <p class="sub-heading">{{ __('(Enter Currency Code)') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" name="name"
                                            placeholder="{{ __('Enter Currency Code') }}" required=""
                                            value="{{ $data->name }}">
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Sign') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" name="sign"
                                            placeholder="{{ __('Enter Currency Sign') }}" required=""
                                            value="{{ $data->sign }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Value') }} *</h4>
                                            <small>{{ __('(Please Enter The Value For 1 USD = ?)') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" name="value"
                                            placeholder="{{ __('Enter Currency Value') }}" readonly="true"
                                            value="{{ $data->value }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Icon') }}</h4>
                                            <small>{{ __('(Please choose icon)') }}</small>
                                        </div>
                                    </div>
                                   <div class="col-lg-8">
                                   <div class="row">
                                    <div class="col-12">
                                    <div class="col-lg-11 choose-file-input p-0 pr-4">
                                        <input type="file" class="input-field" name="icon" accept="image/*">
                                    </div>
                                    <div class="col-lg-12 text-center mt-2 p-0" style="height: auto;width:100px;border: 1px solid rgba(0, 0, 0, 0.1) ;height: 100px;display: flex;align-items: center;justify-content: center;">
                                        <img src='{{ asset('assets/images/currencies/' . $data->icon) }}'>
                                    </div>
                                    </div>
                                   </div>
                                   </div>
                                </div>
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
@endsection
