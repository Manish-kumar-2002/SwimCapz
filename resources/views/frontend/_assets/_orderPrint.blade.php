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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style type="text/css">
        #color-bar {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-left: 5px;
            margin-top: 5px;
        }

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

        .heading-bold h5 {
            font-size: 20px;
            font-weight: bolder;
        }

        .address-align address {
            line-height: 1.8;
        }

        .table>thead>tr>th {
            border-top: 1px solid #ddd !important;
        }
    </style>
</head>

<body onload="window.print();">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- Starting of Dashboard data-table area -->
                <div class="section-padding add-product-1">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="product__header">
                                <div class="row reorder-xs">
                                    <div class="col-12" style="margin-bottom: 31px;margin-left: 10px;">
                                        <div class="product-header-title">
                                            <h2>{{ __('Order#') }} {{ $order->order_number }} [
                                                {{ $order->order_custom_status }}
                                                ]
                                            </h2>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="dashboard-content address-align">
                                                <div class="view-order-page" id="print">


                                                    <div class="table-responsive">
                                                        <table class="table table-hover dt-responsive">
                                                            <tr>
                                                                <th>{{ __('Order Details') }}</th>
                                                                <th>{{ __('Billing Address') }}</th>
                                                                <th>{{ __('Shipping Address') }}</th>
                                                                <th>{{ __('Payment Information') }}</th>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <p class="order-date">
                                                                        <strong>{{ __('Order Date:') }}</strong>
                                                                        {{ date('d-M-Y', strtotime($order->created_at))
                                                                        }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <address>
                                                                        <strong> {{ __('Name:') }}</strong>
                                                                        {{ $order->billing_name }}<br>
                                                                        <strong> {{ __('Email:') }}</strong>
                                                                        {{ $order->billing_email }}<br>
                                                                        <strong> {{ __('Phone:') }}</strong>
                                                                        {{ $order->billing_phone }}<br>
                                                                        <strong>{{ __('Address:') }}</strong>
                                                                        {{ $order->billing_address }}<br>
                                                                        {{ $order->billing_city }}-{{
                                                                        $order->billing_zip }}
                                                                    </address>
                                                                </td>
                                                                <td>
                                                                    <address>
                                                                        <strong> {{ __('Name:') }}</strong>
                                                                        {{ $order->shipping_name == null ?
                                                                        $order->customer_name : $order->shipping_name
                                                                        }}<br>
                                                                        <strong> {{ __('Email:') }}</strong>
                                                                        {{
                                                                        $order->shipping_email == null ?
                                                                        $order->customer_email :
                                                                        $order->shipping_email
                                                                        }}<br>
                                                                        <strong> {{ __('Phone:') }}</strong>
                                                                        {{
                                                                        $order->shipping_phone == null ?
                                                                        $order->customer_phone :
                                                                        $order->shipping_phone
                                                                        }}<br>
                                                                        <strong>{{ __('Address:') }}</strong>
                                                                        {{
                                                                        $order->shipping_address == null ?
                                                                        $order->customer_address :
                                                                        $order->shipping_address
                                                                        }}<br>
                                                                        {{
                                                                        $order->shipping_city == null ?
                                                                        $order->customer_city :
                                                                        $order->shipping_city
                                                                        }}
                                                                        {{
                                                                        $order->shipping_zip == null ?
                                                                        $order->customer_zip :
                                                                        $order->shipping_zip
                                                                        }}
                                                                    </address>
                                                                </td>
                                                                <td>
                                                                    <p><strong>{{ __('Paid Amount:') }}</strong>
                                                                        {{ Helper::convertPrice($order->pay_amount) }}
                                                                    </p>
                                                                    <p><strong>{{ __('Payment Method:') }}</strong> {{
                                                                        $order->method }}
                                                                    </p>
                                                                    @if ($order->method == 'stripe' &&
                                                                    $order->payment_status == 1)
                                                                    <p><strong>Transaction Id :</strong> {{
                                                                        $order->stripe_id }}</p>
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                        </table>
                                                    </div>
                                                    <!-- Ordered Products: -->
                                                    @include('frontend._assets.orders._printOrderDetails', [
                                                    'order' => $order
                                                    ])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Ending of Dashboard data-table area -->
                    </div>
                    <!-- ./wrapper -->
                    <!-- ./wrapper -->
                    <script type="text/javascript">
                        (function ($) {
                            "use strict";

                            setTimeout(function () {
                                window.close();
                            }, 500);

                        })(jQuery);
                    </script>
</body>

</html>