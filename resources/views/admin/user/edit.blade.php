@extends('layouts.load')
@section('content')
    <div class="content-area">
        <div class="add-product-content1">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            @include('alerts.admin.form-error')
                            <form id="geniusformdata" action="{{ route('admin-user-edit', $data->id) }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Customer Profile Image') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="img-upload">
                                            @if ($data->is_provider == 1)
                                                <div id="image-preview"
													class="img-preview"
                                                    style="background: url({{ $data->photo ?
													asset($data->photo) : asset('assets/images/noimage.png') }});">
                                                @else
                                                    <div id="image-preview"
														class="img-preview"
                                                        style="background: url({{ $data->photo ?
														asset('assets/images/users/' . $data->photo) : asset('assets/images/noimage.png') }});">
                                            @endif
                                            @if ($data->is_provider != 1)
                                                <label for="image-upload" class="img-label" id="image-label"><i
                                                        class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                <input type="file" name="photo" class="img-upload" id="image-upload">
                                            @endif
                                        </div>
                                        <p class="text">{{ __('Preferred Size: (600x600) or Square Sized Image') }}</p>
                                    </div>
                                </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Name') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <input type="text" class="input-field" name="name" placeholder="{{ __('User Name') }}"
                                    required="" value="{{ $data->name }}">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-4">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Email') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <input
                                    error-class="error-email"
                                    type="email" class="input-field email-validate" name="email"
                                    placeholder="{{ __('Email Address') }}" value="{{ $data->email }}">

                                <p class="errors error-email" style="color:red;"></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Phone') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <input
                                    max="10"
                                    type="text" class="input-field only-numeric" name="phone"
                                    placeholder="{{ __('Phone Number') }}"
                                    required=""
                                    value="{{ $data->phone }}"
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Address') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <input type="text" class="input-field" name="address" placeholder="{{ __('Address') }}"
                                    required="" value="{{ $data->address }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Country') }} </h4>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <select class="input-field country" name="country" id="country" required>
                                    <option value="">{{ __('Select Country') }}</option>
                                    @foreach (Helper::getCountries() as $dt)
                                        <option value="{{ $dt->id }}"
                                            {{ $data->country == $dt->id ? 'selected' : '' }}>
                                            {{ $dt->country_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-lg-4">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('State') }} </h4>
                                </div>
                            </div>
                            <div class="col-lg-7">
								<select class="input-field state" name="state" required>
                                    <option value="">{{ __('Select State') }}</option>
                                  	@foreach (Helper::getStates( $data->country) as $dt)
                                        <option value="{{ $dt->id }}"
                                            {{ $data->state == $dt->id ? 'selected' : '' }}>
                                            {{ $dt->state }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

						<div class="row">
                            <div class="col-lg-4">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('City') }} </h4>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <input type="text" class="input-field" name="city" placeholder="{{ __('City') }}"
                                    value="{{ $data->city }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Postal Code') }} </h4>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <input
                                    type="text"
                                    class="input-field only-numeric"
                                    max="6"
                                    name="zip"
                                    placeholder="{{ __('Postal Code') }}"
                                    value="{{ $data->zip }}"
                                />
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