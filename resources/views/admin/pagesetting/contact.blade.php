@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Contact Us') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Content Management') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-ps-contact') }}">{{ __('Contact Us Page') }}</a>
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
                            <form id="admin-contact" action="{{ route('admin-ps-update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                @include('alerts.admin.form-both')

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Email') }}
												<span class="required">*</span>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input
											error-class="error-email"
											type="email"
											class="input-field email-validate"
											placeholder="{{ __('Enter Email') }}"
                                            name="email"
											id="email"
											value="{{ $data->email }}"
										/>

										<p class="errors error-email mt-2"></p>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Website') }}
												<span class="required">*</span>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input
											type="text"
											class="input-field"
											placeholder="{{ __('Enter Website') }}"
                                            name="site"
											id="site"
											value="{{ $data->site }}"
										/>

										<p class="errors error-site mt-2"></p>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Phone') }}
												<span class="required">*</span>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input
											type="text"
											max="10"
											class="input-field only-numeric"
											placeholder="{{ __('Enter Phone') }}"
                                            name="phone"
											id="phone"
											value="{{ $data->phone }}"
										/>

										<p class="errors error-phone mt-2"></p>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Fax') }}
												<span class="required">*</span>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input
											type="text"
											class="input-field"
											placeholder="{{ __('Enter Fax') }}"
                                            name="fax"
											id="fax"
											value="{{ $data->fax }}"
										/>

										<p class="errors error-fax mt-2"></p>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Street Address') }}
												<span class="required">*</span>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <textarea
											name="street"
											class="input-field"
											placeholder="Enter Street Address"
											id="street"
										> {{ $data->street }} </textarea>

										<p class="errors error-street mt-2"></p>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Contact Us Email Address') }}
												<span class="required">*</span>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="tawk-area">
											<input
												error-class="error-contact_email"
												type="email"
												class="input-field email-validate"
												placeholder="{{ __('Enter Email') }}"
												name="contact_email"
												id="contact_email"
												value="{{ $data->contact_email }}"
											/>

											<p class="errors error-contact_email mt-2"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
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
