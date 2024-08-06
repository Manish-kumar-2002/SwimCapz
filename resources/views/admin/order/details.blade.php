@extends('layouts.admin')

@section('styles')
    <style type="text/css">
        .order-table-wrap table#example2 {
            margin: 10px 20px;
        }

        .width-45 {
            width: width-45;
        }

        .width-10 {
            width: width-10;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
@endsection
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">
                        {{ __('Order Details') }}
                        <a class="add-btn" href="javascript:history.back();">
                            <i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
                    </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Orders') }}</a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Order Details') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="order-table-wrap">
            @include('alerts.admin.form-both')
            @include('alerts.form-success')
            <div class="row">

                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Order Details') }}
                            </h4>
                        </div>
                        <div class="table-responsive-sm">
                            <table class="table">
                                <caption style="display:none;">&nbsp;</caption>
                                <tbody>
                                    <tr>
                                        <th scope class="width-45">{{ __('Order ID') }}</th>
                                        <td class="width-10">:</td>
                                        <td class="width-45">{{ $order->order_number }}</td>
                                    </tr>
                                    <tr>
                                        <th scope class="width-45">{{ __('Total Product') }}</th>
                                        <td class="width-10">:</td>
                                        <td class="width-45">{{ $order->totalQty }}</td>
                                    </tr>
                                    @if ($order->shipping_title != null)
                                        <tr>
                                            <th scope class="width-45">{{ __('Shipping Method') }}</th>
                                            <td class="width-10">:</td>
                                            <td class="width-45">{{ $order->shipping_title }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th scope class="width-45">{{ __('Total Cost') }}</th>
                                        <td class="width-10">:</td>
                                        <td class="width-45">
                                            {{ Helper::convertPrice($order->pay_amount) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope class="width-45">{{ __('Order Date') }}</th>
                                        <td class="width-10">:</td>
                                        <td class="width-45">
                                            {{ date('d-M-Y H:i:s a', strtotime($order->created_at)) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope class="width-45">{{ __('Payment Method') }}</th>
                                        <td class="width-10">:</td>
                                        <td class="width-45">{{ $order->method }}</td>
                                    </tr>

                                    <tr>
                                        <th scope class="width-45">{{ __('Order Status') }}</th>
                                        <td class="width-10">:</td>
                                        <td class="width-45">{{ $order->order_custom_status }}</td>
                                    </tr>

                                    @if ($order->method == Helper::PAYMENT_METHOD_PO)
                                        <tr>
                                            <th scope class="width-45">{{ __('PO Number') }}</th>
                                            <th scope class="width-10">:</th>
                                            <td class="width-45">
                                                {{ $order->po_order }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope class="width-45">{{ __('Attachement') }}</th>
                                            <th scope class="width-10">:</th>
                                            <td class="width-45">
                                                <a href="{{ $order->attachement }}" download>Download Attachement</a>
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($order->payment_status == Helper::PAYMENT_PENDING)
                                        <span class='badge badge-danger'>{{ __(Helper::PAYMENT_PENDING_MSG) }}</span>
                                    @elseif ($order->payment_status == Helper::PAYMENT_REFUND)
                                        <span class='badge badge-danger'>{{ __(Helper::PAYMENT_REFUND_MSG) }}</span>
                                    @else
                                        <span class='badge badge-success'>{{ __(Helper::PAYMENT_SUCCESS_MSG) }}</span>
                                    @endif

                                    @if (!empty($order->cancel_note))
                                        <tr>
                                            <th scope class="width-45">{{ __('Cancel Note') }}</th>
                                            <th scope class="width-10">:</th>
                                            <td class="width-45">
                                                <b style="color:red;">
                                                    {{ $order->cancel_note }}
                                                </b>
                                            </td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>
                        <div class="footer-area">
                            <a href="{{ route('admin-order-invoice', $order->id) }}" class="mybtn1"><i
                                    class="fas fa-eye"></i> {{ __('View Invoice') }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Billing Details') }}
                                <a class="f15" href="javascript:;" data-toggle="modal"
                                    data-target="#billing-details-edit"><i class="fas fa-edit"></i>{{ __('Edit') }}</a>
                            </h4>
                        </div>
                        <div class="table-responsive-sm">
                            <table class="table">
                                <caption style="display:none;">&nbsp;</caption>
                                <tbody>
                                    <tr>
                                        <th scope class="width-45">{{ __('Name') }}</th>
                                        <th scope class="width-10">:</th>
                                        <td class="width-45">{{ $order->billing_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope class="width-45">{{ __('Email') }}</th>
                                        <th scope class="width-10">:</th>
                                        <td class="width-45">{{ $order->billing_email }}</td>
                                    </tr>
                                    <tr>
                                        <th scope class="width-45">{{ __('Phone') }}</th>
                                        <th scope class="width-10">:</th>
                                        <td class="width-45">{{ $order->billing_phone }}</td>
                                    </tr>
                                    <tr>
                                        <th scope class="width-45">{{ __('Address') }}</th>
                                        <th scope class="width-10">:</th>
                                        <td class="width-45">{{ $order->billing_address }}</td>
                                    </tr>
                                    @if ($order->billing_country != null && @$order->billingCountry)
                                        <tr>
                                            <th scope class="width-45">{{ __('Country') }}</th>
                                            <th scope class="width-10">:</th>
                                            <td class="width-45">{{ $order->billingCountry->country_name }}</td>
                                        </tr>
                                    @endif

                                    @if ($order->billing_state != null && @$order->billingState)
                                        <tr>
                                            <th scope class="width-45">{{ __('State') }}</th>
                                            <th scope class="width-10">:</th>
                                            <td class="width-45">{{ $order->billingState->state }}</td>
                                        </tr>
                                    @endif

                                    @if ($order->billing_city != null)
                                        <tr>
                                            <th scope class="width-45">{{ __('City') }}</th>
                                            <th scope class="width-10">:</th>
                                            <td class="width-45">{{ $order->billing_city }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th scope class="width-45">{{ __('Postal Code') }}</th>
                                        <th scope class="width-10">:</th>
                                        <td class="width-45">{{ $order->billing_zip }}</td>
                                    </tr>

                                    @if ($order->coupon_code != null)
                                        <tr>
                                            <th scope class="width-45">{{ __('Coupon Code') }}</th>
                                            <th scope class="width-10">:</th>
                                            <td class="width-45">{{ $order->coupon_code }}</td>
                                        </tr>
                                    @endif

                                    @if ($order->coupon_discount != null)
                                        <tr>
                                            <th scope class="width-45">{{ __('Coupon Discount') }}</th>
                                            <th scope class="width-10">:</th>
                                            <td class="width-45">
                                                {{ Helper::convertPrice($order->coupon_discount) }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Shipping Details') }}
                                <a class="f15" href="javascript:;" data-toggle="modal"
                                    data-target="#shipping-details-edit"><i
                                        class="fas fa-edit"></i>{{ __('Edit') }}</a>
                            </h4>
                        </div>
                        <div class="table-responsive-sm">
                            <table class="table">
                                <caption style="display:none">&nbsp;</caption>
                                <tbody>
                                    <tr>
                                        <th scope class="width-45">
                                            <strong>{{ __('Name') }}:</strong>
                                        </th>
                                        <th scope class="width-10">:</th>
                                        <td>{{ $order->shipping_name == null ? $order->customer_name : $order->shipping_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope class="width-45"><strong>{{ __('Email') }}:</strong></th>
                                        <th scope class="width-10">:</th>
                                        <td class="width-45">
                                            {{ $order->shipping_email == null ? $order->customer_email : $order->shipping_email }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope class="width-45"><strong>{{ __('Phone') }}:</strong></th>
                                        <th scope class="width-10">:</th>
                                        <td class="width-45">
                                            {{ $order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope class="width-45"><strong>{{ __('Address') }}:</strong></th>
                                        <th scope class="width-10">:</th>
                                        <td class="width-45">
                                            {{ $order->shipping_address == null ? $order->customer_address : $order->shipping_address }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope class="width-45"><strong>{{ __('Country') }}:</strong></th>
                                        <th scope class="width-10">:</th>
                                        <td class="width-45">
                                            @if ($order->shipping_country)
                                                {{ $order->shippingCountry->country_name }}
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope class="width-45">{{ __('State') }}</th>
                                        <th scope class="width-10">:</th>
                                        <td class="width-45">
                                            @if ($order->shipping_state)
                                                {{ @$order->shippingState->state }}
                                            @endif
                                        </td>
                                    </tr>

                                    @if ($order->shipping_city)
                                        <tr>
                                            <th scope class="width-45"><strong>{{ __('City') }}:</strong></th>
                                            <th scope class="width-10">:</th>
                                            <td class="width-45">
                                                {{ $order->shipping_city }}
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th scope class="width-45"><strong>{{ __('Postal Code') }}:</strong></th>
                                        <th scope class="width-10">:</th>
                                        <td class="width-45">
                                            {{ $order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Order Status Change') }}
                            </h4>
                        </div>

                        <div class="table-responsive-sm">
                            <form  action="{{route('admin-order-update',$order->id)}}" method="POST" enctype="multipart/form-data">
                                
                                <input type="hidden" id="hidden_update" name="_token" value={{csrf_token()}}>
                                <table class="table">
                                    <caption style="display:none;">&nbsp;</caption>
                                    <tr>
                                        <th scope>Order Status :-</th>
                                        <th scope>
                                            @include('admin.order.partials._orderStatus', [
                                                'order_status' => $order->order_status,
                                            ])
                                        </th>
                                    </tr>
                                    @if ($order->order_status != Helper::ORDER_REJECTED && $order->order_status != Helper::ORDER_CANCELLED)
                                        <tr style="display:none;" class="order_track_text">
                                            <th scope>Notes :-</th>
                                            <th scope>
                                                <textarea rows="3" class="form-control" name="track_text"></textarea>
                                            </th>
                                        </tr>
                                        <tr style="display:none;" class="button-tr">
                                            <th scope>&nbsp;</th>
                                            <th scope>
                                                <button type="submit"
                                                    class="btn btn-success">{{ __('Change') }}</button>
                                            </th>
                                        </tr>
                                    @endif
                                </table>
                            </form>
                            {{-- this is tracking number filling form , if tracking number is not given before  --}}
                            <input style="display: none" id="order_id" value="{{ $order->id }}">
                            @if (empty($order->trackingNo))
                                <form id="trackform" style="display: none"
                                    action="{{ route('admin-order-tracking-number-store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    @include('alerts.admin.form-both')
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 order-details-table">
                    @include('admin.order.partials._orderTable', ['order' => $order])
                </div>
                <div class="col-lg-12 text-center mt-2">
                    <a class="btn sendEmail send" href="javascript:;" class="send"
                        data-email="{{ $order->email }}" data-toggle="modal" data-target="#vendorform">
                        <i class="fa fa-send"></i> {{ __('Send Email') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content Area End -->
    </div>
    </div>


    </div>

    {{-- BILLING DETAILS EDIT MODAL --}}

    @include('admin.order.partials.billing-details', $order)

    {{-- BILLING DETAILS MODAL ENDS --}}

    {{-- SHIPPING DETAILS EDIT MODAL --}}

    @include('admin.order.partials.shipping-details')

    {{-- SHIPPING DETAILS MODAL ENDS --}}

    {{-- ADD PRODUCT MODAL --}}

    @include('admin.order.partials.add-product')

    {{-- MESSAGE MODAL --}}
    <div class="sub-categori">
        <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vendorformLabel">{{ __('Send Email') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="contact-form">
                                        <form id="emailreply">
                                            {{ csrf_field() }}
                                            <ul class="send-mail-chat">
                                                <li>
                                                    <input type="email" class="input-field eml-val" id="eml"
                                                        name="to" value="" required="">
                                                    <label for="eml">{{ __('Email') }} <span
                                                            class="span-required">*</span></label>
                                                </li>
                                                <li>
                                                    <input type="text" class="input-field" id="subj"
                                                        name="subject" required="">
                                                    <label for="subj">{{ __('Subject') }} <span
                                                            class="span-required">*</span></label>
                                                </li>
                                                <li>

                                                    <textarea class="input-field textarea" name="message" id="msg" required=""></textarea>
                                                    <label for="msg">{{ __('Your Message') }} <span
                                                            class="span-required">*</span></label>
                                                </li>
                                            </ul>
                                            <button class="submit-btn" id="emlsub"
                                                type="submit">{{ __('Send Email') }}</button>
                                        </form>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MESSAGE MODAL ENDS --}}

    {{-- ORDER MODAL --}}

    <div class="modal fade" id="confirm-delete2" tabindex="-1" role="dialog" aria-labelledby="modal1"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{ asset('assets/images/' . $gs->admin_loader) }}" alt="">
                </div>
                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __('Update Status') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p class="text-center">{{ __("You are about to update the order's status.") }}</p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-success btn-ok order-btn">{{ __('Proceed') }}</a>
                </div>

            </div>
        </div>
    </div>

    {{-- ORDER MODAL ENDS --}}
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            $('#hidden_update').val("{{ csrf_token() }}");
            $('#emailreply input[name="_token"]').val("{{ csrf_token() }}");
            console.log($('#hidden_update').val());
        });
    </script>

    <script type="text/javascript">
        (function($) {
            "use strict";


            $('.track-text').change(function() {
                let val = $(this).val();
                if (val == {{ Helper::ORDER_REJECTED }} || val == {{ Helper::ORDER_CANCELLED }}) {
                    $('.order_track_text').show();
                } else {
                    $('.order_track_text').hide();
                }


                let _preSelected = $(this).attr('data-selected');
                let _value = $(this).val();
                console.log(_preSelected, _value);
                if (_preSelected == _value) {
                    $('.button-tr').hide();
                } else {
                    $('.button-tr').show();
                }

                if (val == '{{ Helper::ORDER_SHIPPED }}') {
                    $('.button-tr').hide();
                    @if ($order->trackingNo != null)
                        $('.button-tr').show();
                    @else
                        $('#trackform').show();
                    @endif
                } else if (_preSelected == _value) {
                    $('.button-tr').hide();
                    $('#trackform').hide();
                } else {
                    $('.button-tr').show();
                    $('#trackform').hide();
                }

            });

            function disablekey() {
                document.onkeydown = function(e) {
                    return false;
                }
            }

            function enablekey() {
                document.onkeydown = function(e) {
                    return true;
                }
            }

            $(document).on('click', '.license', function(e) {
                var id = $(this).parent().find('input[type=hidden]').val();
                var key = $(this).parent().parent().find('input[type=hidden]').val();
                $('#key').html(id);
                $('#license-key').val(key);
            });
            $(document).on('click', '#license-edit', function(e) {
                $(this).hide();
                $('#edit-license').show();
                $('#license-cancel').show();
            });
            $(document).on('click', '#license-cancel', function(e) {
                $(this).hide();
                $('#edit-license').hide();
                $('#license-edit').show();
            });
            // ADD OPERATION

            $(document).on('click', '.edit-product', function() {

                if (admin_loader == 1) {
                    $('.submit-loader').show();
                }
                $('#edit-product-modal .modal-content .modal-body').html('').load($(this).data('href'),
                    function(response, status, xhr) {
                        if (status == "success") {
                            if (admin_loader == 1) {
                                $('.submit-loader').hide();
                            }
                        }
                    });
            });

            // ADD OPERATION END

            // SHOW PRODUCT FORM SUBMIT

            $(document).on('submit', '#show-product', function(e) {
                e.preventDefault();
                if (admin_loader == 1) {
                    $('.submit-loader').show();
                }
                $('button.addProductSubmit-btn').prop('disabled', true);
                disablekey();
                $.ajax({
                    method: "POST",
                    url: $(this).prop('action'),
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data[0]) {
                            $('#product-show').html('').load(mainurl +
                                "/admin/order/product-show/" + data[1],
                                function(response, status, xhr) {
                                    if (status == "success") {
                                        if (admin_loader == 1) {
                                            $('.submit-loader').hide();
                                        }
                                    }
                                });
                        } else {
                            if (admin_loader == 1) {
                                $('.submit-loader').hide();
                            }
                            $('#product-show').html('<div class="col-lg-12 text-center"><h4>' +
                                data[1] + '.</h4></div>')
                        }

                        $('button.addProductSubmit-btn').prop('disabled', false);

                        enablekey();
                    }

                });

            });

            // SHOW PRODUCT FORM SUBMIT ENDS


            $('#delete-product-modal').on('show.bs.modal', function(e) {
                $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            });

        })(jQuery);


        /* change billing country */
        $(document).on('change', '#billing-details-edit .billing-countries', function() {
            let selected_id = $(this).val();
            let url = '{{ url('ajax/search-states') }}/' + selected_id;
            $.get(url, {
                selected: '{{ @$order->billing_state ?? null }}'
            }, function(response) {
                $('.billing-states').html(response);
            });
        });

        /* change billing state */
        $(document).on('change', '.shipping-countries', function() {
            let selected_id = $(this).val();
            let url = '{{ url('ajax/search-states') }}/' + selected_id;
            $.get(url, {
                selected: '{{ @$order->shipping_state ?? null }}'
            }, function(response) {
                $('.shipping-states').html(response);
            });
        });
        $(function() {
            $('.billing-countries').trigger('change'); //billing state load
            $('.shipping-countries').trigger('change'); //shipping state load


            $('.billing-countries, .shipping-countries').select2({
                placeholder: '-Select-',
                ajax: {
                    url: "{{ route('ajax.search.countries') }}",
                    data: function(params) {
                        var query = {
                            search: params.term
                        }
                        return query;
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                },
            });

            $('.billing-states').select2({
                placeholder: '-Select-',
                ajax: {
                    url: '{{ route('ajax.search.states') }}',
                    data: function(params) {
                        let country = $('.billing-countries').val();
                        var query = {
                            search: params.term,
                            country: country
                        }
                        return query;
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                },
            });

            $('.shipping-states').select2({
                placeholder: '-Select-',
                ajax: {
                    url: '{{ route('ajax.search.states') }}',
                    data: function(params) {
                        let country = $('.shipping-countries').val();
                        var query = {
                            search: params.term,
                            country: country
                        }
                        return query;
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                },
            });
        })

        $(".mobile").keydown(function(event) {
            let _text = $(this).val();

            if (event.keyCode == 46 || event.keyCode == 8) {
                // Allow only backspace and delete
            } else {

                if (event.keyCode < 48 || event.keyCode > 57) {
                    event.preventDefault();
                    // Ensure that it is a text and stop the keypress
                }

                if (_text.length >= 11) {
                    event.preventDefault();
                }
            }
        });
    </script>
    <script>
        function validateForm() {
            var name = document.getElementById('billing_name').value.trim();
            var email = document.getElementById('billing_email').value.trim();
            var phone = document.getElementById('billing_phone').value.trim();
            var postalCode = document.getElementById('billing_postal_code').value.trim();
            var nameError = document.getElementById('nameError');
            var emailError = document.getElementById('emailError');
            var phoneError = document.getElementById('phoneError');
            var postalCodeError = document.getElementById('postalCodeError');
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regex to validate email format

            // Name validation
            if (name === '') {
                nameError.textContent = 'Name cannot be empty.';
                return false;
            } else {
                nameError.textContent = '';
            }

            // Email validation
            if (email === '') {
                emailError.textContent = 'Email cannot be empty.';
                return false;
            } else if (!emailRegex.test(email)) { // Check if email format is valid
                emailError.textContent = 'Invalid email format.';
                return false;
            } else {
                emailError.textContent = '';
            }

            // Phone number validation
            if (phone === '') {
                phoneError.textContent = 'Phone number cannot be empty.';
                return false;
            } else if (!isValidPhoneNumber(phone)) { // Check if phone number format is valid
                phoneError.textContent = 'Invalid phone number format.';
                return false;
            } else {
                phoneError.textContent = '';
            }

            // Postal code validation
            if (postalCode === '') {
                postalCodeError.textContent = 'Postal code cannot be empty.';
                return false;
            } else if (isNaN(postalCode) || postalCode.length !== 6) {
                postalCodeError.textContent = 'Postal code must be 6 digits.';
                return false;
            } else {
                postalCodeError.textContent = '';
            }

            return true;
        }
        // Phone number validation function
        function isValidPhoneNumber(phoneNumber) {
            var phoneRegex = /^\d{6,14}$/; // Matches 6 to 14 digits
            return phoneRegex.test(phoneNumber);
        }

        // Add event listeners to input fields for real-time validation
        document.getElementById('billing_name').addEventListener('input', validateForm);
        document.getElementById('billing_email').addEventListener('input', validateForm);
        document.getElementById('billing_phone').addEventListener('input', validateForm);
        document.getElementById('billing_postal_code').addEventListener('input', validateForm);
    </script>
    <script>
        function validateShippingForm() {
            var name = document.getElementById('shipping_name').value.trim();
            var email = document.getElementById('shipping_email').value.trim();
            var phone = document.getElementById('shipping_phone').value.trim();
            var postalCode = document.getElementById('shipping_postal_code').value.trim();
            var nameError = document.getElementById('shippingnameError');
            var emailError = document.getElementById('shippingemailError');
            var phoneError = document.getElementById('shippingphoneError');
            var postalCodeError = document.getElementById('shippingpostalCodeError');
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regex to validate email format

            // Name validation
            if (name === '') {
                nameError.textContent = 'Name cannot be empty.';
                return false;
            } else {
                nameError.textContent = '';
            }

            // Email validation
            if (email === '') {
                emailError.textContent = 'Email cannot be empty.';
                return false;
            } else if (!emailRegex.test(email)) { // Check if email format is valid
                emailError.textContent = 'Invalid email format.';
                return false;
            } else {
                emailError.textContent = '';
            }

            // Phone number validation
            if (phone === '') {
                phoneError.textContent = 'Phone number cannot be empty.';
                return false;
            } else if (!isValidPhoneNumber(phone)) { // Check if phone number format is valid
                phoneError.textContent = 'Invalid phone number format.';
                return false;
            } else {
                phoneError.textContent = '';
            }

            // Postal code validation
            if (postalCode === '') {
                postalCodeError.textContent = 'Postal code cannot be empty.';
                return false;
            } else if (isNaN(postalCode) || postalCode.length !== 6) {
                postalCodeError.textContent = 'Postal code must be 6 digits.';
                return false;
            } else {
                postalCodeError.textContent = '';
            }

            return true;
        }
        // Phone number validation function
        function isValidPhoneNumber(phoneNumber) {
            var phoneRegex = /^\d{6,14}$/; // Matches 6 to 14 digits
            return phoneRegex.test(phoneNumber);
        }

        // Add event listeners to input fields for real-time validation
        document.getElementById('shipping_name').addEventListener('input', validateShippingForm);
        document.getElementById('shipping_email').addEventListener('input', validateShippingForm);
        document.getElementById('shipping_phone').addEventListener('input', validateShippingForm);
        document.getElementById('shipping_postal_code').addEventListener('input', validateShippingForm);
    </script>
    <script>
        $('input,textarea').val("");
        $('.send-mail-chat input, .send-mail-chat textarea').focusout(function() {
            var text_val = $(this).val();
            if (text_val === "") {
                console.log("empty!");
                $(this).removeClass('has-value');
            } else {
                $(this).addClass('has-value');
            }
        });
    </script>
    <script>
        function appendValues_billing_details() {
            let isValid = true;

            const tokenInputorder = document.getElementById('hiddenorder');
            tokenInputorder.value = "{{ csrf_token() }}";

            const nameInput = document.getElementById('billing_name');
            nameInput.value = "{{ $order->billing_name }}";

            const billingCity = document.getElementById('billing_city');
            billingCity.value = "{{ $order->billing_city }}";

            const billingAddress = document.getElementById('billing_address');
            billingAddress.value = "{{ $order->billing_address }}";

            const emailInput = document.getElementById('billing_email');
            emailInput.value = "{{ $order->billing_email }}";

            const phoneInput = document.getElementById('billing_phone');
            phoneInput.value = "{{ $order->billing_phone }}";

            const postalCodeInput = document.getElementById('billing_postal_code');
            postalCodeInput.value = "{{ $order->billing_zip }}";

            return isValid;
        }

        function appendValues_shipping_details() {
            let isValid = true;

            const shippingNameInput = document.getElementById('shipping_name');
            shippingNameInput.value = "{{ $order->shipping_name == null ? $order->customer_name : $order->shipping_name }}";
           
            const tokenInput = document.getElementById('hiddenshipping');
            tokenInput.value = "{{ csrf_token() }}";
            
            const shippingEmailInput = document.getElementById('shipping_email');
            shippingEmailInput.value = "{{$order->shipping_email == null ? $order->customer_email : $order->shipping_email}}" ;
            
            const shippingPhoneInput = document.getElementById('shipping_phone');
            shippingPhoneInput.value = "{{$order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone}}" ;
            
            const shippingAddressInput = document.getElementById('shipping_address');
            shippingAddressInput.value = "{{$order->shipping_address == null ? $order->customer_address : $order->shipping_address}}" ;
            
            const shippingCityInput = document.getElementById('shipping_city');
            shippingCityInput.value = "{{$order->shipping_city == null ? $order->customer_city : $order->shipping_city}}" ;
            
            const shippingPostalInput = document.getElementById('shipping_postal_code');
            shippingPostalInput.value = "{{$order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip}}" ;

        }
        appendValues_shipping_details();
        appendValues_billing_details();
    </script>
@endsection
