@extends('layouts.load')
@section('content')
    <div class="content-area">

        <div class="add-product-content1">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area" id="modalEdit">
                            @include('alerts.admin.form-error')
                            <form
								id="geniusformdata"
								action="{{ route('admin-order-update', $data->id) }}"
								method="POST"
                                enctype="multipart/form-data"
							>
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Payment Status') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <select name="payment_status" required="">
											<option
												value="{{Helper::PAYMENT_PENDING}}"
												{{$data->payment_status == Helper::PAYMENT_PENDING ? 'selected' : ''}}
											>{{__('UnPaid')}}</option>
											<option
												value="{{Helper::PAYMENT_SUCCESS}}"
												{{$data->payment_status == Helper::PAYMENT_SUCCESS ? 'selected' : ''}}
											>{{__('Paid')}}</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Delivery Status') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <select name="status" required="">
											@foreach (Helper::getOrderStatus() as $key => $row)
												<option
													value="{{$key}}"
													{{$data->payment_status == $key ? 'selected' : ''}}
												>{{__($row['text'])}}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Track Note') }} *</h4>
                                            <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <textarea
											class="input-field"
											required
											name="track_text"
											placeholder="{{ __('Enter Track Note Here') }}"
										></textarea>
                                    </div>
                                </div>

                                <br>
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

@section('scripts')
@endsection
