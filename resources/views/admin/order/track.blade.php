{{-- {{dd($order)}} --}}
@extends('layouts.load')
@section('content')
    {{-- ADD ORDER TRACKING --}}
    @if (empty($order->trackingNo))
        <div class="add-product-content1">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area" id="modalEdit">
                            {{-- <div class="gocover"
                            style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div> --}}
                            {{-- <input type="hidden" name="id" value="{{$order->id}}"> --}}
                            <form id="trackform" action="{{ route('admin-order-tracking-number-store') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                @include('alerts.admin.form-both')

                                <input type="hidden" name="id" value="{{ $order->id }}">
                                <input style="display:none" name="order_id" id="order_id" value="{{ $order->id }}">

                                <div class="row">
                                    <div class="col-lg-5">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Tracking Number') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input class="input-field" id="track-title" name="trackingNo"
                                            placeholder="{{ __('Enter Tracking Number') }}" required=""></input>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-5">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Courier') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <select class="input-field" id="track-details" name="courierSlug" required>
                                            @foreach ($courier as $item)
                                                <option value="{{ $item->slug }}">{{ $item->courierName }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-5">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <button class="addProductSubmit-btn" id="track-btn"
                                            type="submit">{{ __('ADD') }}</button>
                                        <button class="addProductSubmit-btn ml=3 d-none" id="cancel-btn"
                                            type="button">{{ __('Cancel') }}</button>
                                        <input type="hidden" id="add-text" value="{{ __('ADD') }}">
                                        <input type="hidden" id="edit-text" value="{{ __('UPDATE') }}">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        @if (!$checkpoints->isEmpty())
            <h5 class="text-center">{{ __('TRACKING DETAILS') }}</h5>
            <hr>
            <div class="content-area no-padding">
                <div class="add-product-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="product-description">
                                <div class="body-area" id="modalEdit">


                                    <div class="table-responsive show-table ml-3 mr-3">
                                        <table class="table" id="track-load"
                                            data-href={{ route('admin-order-track-load', $order->id) }}>
                                            <tr>
                                                <th>{{ __('Courier') }}</th>
                                                <th>{{ __('Location') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Time') }}</th>
                                                <th>{{ __('Message') }}</th>
                                                <th>{{ __('Country') }}</th>
                                            </tr>
                                            @foreach ($checkpoints as $track)
                                                <tr>
                                                    <td width="30%" class="t-title">{{ $track->slug }}</td>
                                                    <td width="40%" class="t-title">{{ $track->location }}</td>
                                                    <td width="10%" class="t-title">
                                                        {{ date('Y-m-d', strtotime($track->checkpoint_time)) }}</td>
                                                    <td width="10%" class="t-title">
                                                        {{ date('h:i:s:a', strtotime($track->checkpoint_time)) }}</td>
                                                    <td width="20%" class="t-text">{{ $track->message }}</td>
                                                    <td width="10%" class="t-text">{{ $track->country_name }}</td>
                                                    <td>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div>
                <h3>No tracking details !</h3>
            </div>
        @endif
    @endif
@endsection
