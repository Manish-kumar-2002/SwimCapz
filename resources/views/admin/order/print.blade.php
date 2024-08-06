<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="{{ $seo->meta_keys }}">
    <meta name="author" content="GeniusOcean">

    <title>{{ $gs->title }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('assets/print/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/print/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('assets/print/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/print/css/style.css') }}">
    <link href="{{ asset('assets/print/css/print.css') }}" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/' . $gs->favicon) }}">
    <style type="text/css">
        @page {
            size: auto;
            margin: 0mm;
        }

        @page {
            size: A4;
            margin: 0;
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: 287mm;
            }

            html {}

            ::-webkit-scrollbar {
                width: 0px;
                /* remove scrollbar space */
                background: transparent;
                /* optional: just make scrollbar invisible */
            }
        }
    </style>
</head>

<body onload="window.print();">
    <div class="invoice-wrap">
        <div class="invoice__title">
            <div class="row">
                <div class="col-sm-6">
                    <div class="invoice__logo text-left">
                        <img src="{{ asset('assets/images/' . $gs->invoice_logo) }}" alt="woo commerce logo">
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="invoice__metaInfo">
            <div class="col-lg-6">
                <div class="invoice__orderDetails">

                    <p><strong>{{ __('Order Details') }} </strong></p>
                    <span><strong>{{ __('Invoice Number') }} :</strong> {{ sprintf("%'.08d", $order->id) }}</span><br>
                    <span><strong>{{ __('Order Date') }} :</strong>
                        {{ date('d-M-Y', strtotime($order->created_at)) }}</span><br>
                    <span><strong>{{ __('Order ID') }} :</strong> {{ $order->order_number }}</span><br>
                    @if ($order->dp == 0)
                        <span> <strong>{{ __('Shipping Method') }} :</strong>
                            @if ($order->shipping == 'pickup')
                                {{ __('Pick Up') }}
                            @else
                                {{ __('Ship To Address') }}
                            @endif
                        </span><br>
                    @endif
                    <span>
                        <strong>{{ __('Payment Method') }} :</strong>
                        {{ $order->method }}
                    </span><br>
                    <span>
                        <strong>{{ __('Order Status') }} :</strong>
                        {{ $order->order_custom_status }}
                    </span><br>

                    @if ($order->method == Helper::PAYMENT_METHOD_PO)
                        <span>
                            <strong>{{ __('PO Number') }} :</strong>
                            {{ $order->po_order }}
                        </span><br>

                        @if ($order->attachement)
                            <span>
                                <strong>{{ __('Attachement') }} :</strong>
                                <a href="{{ $order->attachement  }}" download>Download Attachement</a>
                            </span><br>
                        @endif
                    @endif

                    @if (!empty($order->cancel_note))
                        <br>
                        <span>
                            <strong>{{ __('Cancel Note') }} :</strong>
                            <b style="color:red;">
                                {{ $order->cancel_note  }}
                            </b>
                        </span>
                        
                    @endif
                </div>
            </div>
        </div>

        <div class="invoice__metaInfo" style="margin-top:0px;">
            @if ($order->dp == 0)
                <div class="col-lg-6">
                    <div class="invoice__orderDetails" style="margin-top:5px;">
                        <p><strong>{{ __('Shipping Details') }}</strong></p>
                        <span>
                            <strong>{{ __('Customer Name') }}</strong>:
                            {{ $order->shipping_name == null ? $order->customer_name : $order->shipping_name }}
                        </span><br>
                        <span><strong>{{ __('Address') }}</strong>:
                            {{ $order->shipping_address == null ? $order->customer_address : $order->shipping_address }}
                        </span><br>
                        <span><strong>{{ __('City') }}</strong>:
                            {{ $order->shipping_city == null ? $order->customer_city : $order->shipping_city }}
                        </span><br>
                        <span>
                            <strong>{{ __('State') }}</strong>:
                            {{ @$order->shippingState->state ?? '' }}
                        </span><br>
                        <span><strong>{{ __('Country') }}</strong>:
                            {{ @$order->shippingCountry->country_name ?? '' }}
                        </span>
                    </div>
                </div>
            @endif
            <div class="col-lg-6" style="width:50%;">
                <div class="invoice__orderDetails" style="margin-top:5px;">
                    <p><strong>{{ __('Billing Details') }}</strong></p>
                    <span><strong>{{ __('Customer Name') }}</strong>:
                        {{ $order->billing_name ?? $order->customer_name }}</span><br>
                    <span><strong>{{ __('Address') }}</strong>:
                        {{ $order->billing_address ?? $order->customer_Address }}</span><br>
                    <span>
                        <strong>{{ __('City') }}</strong>:
                        {{ $order->billing_city ?? $order->customer_city }}
                    </span><br>
                    <span>
                        <strong>{{ __('State') }}</strong>:
                        {{ @$order->billingState->state ?? '' }}
                    </span><br>
                    <span>
                        <strong>{{ __('Country') }}</strong>:
                        {{ @$order->billingCountry->country_name }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="invoice_table">
                @include('admin.order.partials._orderTable', ['order' => $order])
            </div>
        </div>
    </div>
    <!-- ./wrapper -->

    <script type="text/javascript">
        (function($) {
            "use strict";

            setTimeout(function() {
                window.close();
            }, 500);

        })(jQuery);
    </script>

</body>

</html>
