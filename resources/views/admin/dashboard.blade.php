@extends('layouts.admin')

@section('content')
<div class="content-area">
    @include('alerts.form-success')

    @if($activation_notify != "")
    <div class="alert alert-danger validation">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                aria-hidden="true">×</span></button>
        <h3 class="text-center">{!! clean($activation_notify, array('Attr.EnableID' => true)) !!}</h3>
        
    </div>
    @endif

    @if(Session::has('cache'))

    <div class="alert alert-success validation">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                aria-hidden="true">×</span></button>
        <h3 class="text-center">{{ Session::get("cache") }}</h3>
    </div>

    @endif

    <div class="row row-cards-one">
        @foreach (Helper::getOrderStatus() as $key => $row)
            <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="mycard {{$row['bgColor']}}">
                    <div class="left">
                        <h5 class="title">{{ $row['text'] }} </h5>
                        <span class="number">{{$row['total']}}</span>
                        <a
                            href="{{$row['url']}}"
                            class="link"
                        >{{ __('View All') }}</a>
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                            <i class="{{$row['icon']}}"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-md-12 col-lg-6 col-xl-3">
            <div class="mycard bg1">
                <div class="left">
                    <h5 class="title">{{ __('Total Products') }}</h5>
                    <span class="number">{{count($products)}}</span>
                    <a href="{{route('admin-prod-index')}}" class="link">{{ __('View All') }}</a>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="icofont-cart-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-6 col-xl-3">
            <div class="mycard bg2">
                <div class="left">
                    <h5 class="title">{{ __('Total Customers') }}</h5>
                    <span class="number">{{count($users)}}</span>
                    <a href="{{route('admin-user-index')}}" class="link">{{ __('View All') }}</a>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="icofont-users-alt-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cards-one">
        <div class="col-md-6 col-xl-3">
            <div class="card c-info-box-area">
                <div class="c-info-box box1">
                    <p>{{ App\Models\User::where( 'created_at', '>', Carbon\Carbon::now()->subDays(30))->get()->count()  }}</p>
                </div>
                <div class="c-info-box-content">
                    <h6 class="title">{{ __('New Customers') }}</h6>
                    <p class="text">{{ __('Last 30 Days') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card c-info-box-area">
                <div class="c-info-box box2">
                    <p>{{ App\Models\User::count() }}</p>
                </div>
                <div class="c-info-box-content">
                    <h6 class="title">{{ __('Total Customers') }}</h6>
                    <p class="text">{{ __('All Time') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card c-info-box-area">
                <div class="c-info-box box3">
                    <p>{{ Helper::getTotalSalesInLastThirtyDays()  }}</p>
                </div>
                <div class="c-info-box-content">
                    <h6 class="title">{{ __('Total Sales') }}</h6>
                    <p class="text">{{ __('Last 30 days') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card c-info-box-area">
                <div class="c-info-box box4">
                     <p>{{ Helper::getTotalSalesAllTime() }}</p>
                </div>
                <div class="c-info-box-content">
                    <h6 class="title">{{ __('Total Products') }}</h6>
                    <p class="text">{{ __('All Time') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">
    
    (function($) {
		"use strict";

    displayLineChart();

    function displayLineChart() {
        var data = {
            labels: [
            {!!$days!!}
            ],
            datasets: [{
                label: "Prime and Fibonacci",
                fillColor: "#3dbcff",
                strokeColor: "#0099ff",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [
                {!!$sales!!}
                ]
            }]
        };
        var ctx = document.getElementById("lineChart").getContext("2d");
        var options = {
            responsive: true
        };
        var lineChart = new Chart(ctx).Line(data, options);
    }

    $('#poproducts').dataTable( {
      "ordering": false,
          'lengthChange': false,
          'searching'   : false,
          'ordering'    : false,
          'info'        : false,
          'autoWidth'   : false,
          'responsive'  : true,
          'paging'  : false
    } );

    $('#pproducts').dataTable( {
      "ordering": false,
      'lengthChange': false,
          'searching'   : false,
          'ordering'    : false,
          'info'        : false,
          'autoWidth'   : false,
          'responsive'  : true,
          'paging'  : false
    } );

        var chart1 = new CanvasJS.Chart("chartContainer-topReference",
            {
                exportEnabled: true,
                animationEnabled: true,

                legend: {
                    cursor: "pointer",
                    horizontalAlign: "right",
                    verticalAlign: "center",
                    fontSize: 16,
                    padding: {
                        top: 20,
                        bottom: 2,
                        right: 20,
                    },
                },
                data: [
                    {
                        type: "pie",
                        showInLegend: true,
                        legendText: "",
                        toolTipContent: "{name}: <strong>{#percent%} (#percent%)</strong>",
                        indexLabel: "#percent%",
                        indexLabelFontColor: "white",
                        indexLabelPlacement: "inside",
                        dataPoints: [
                                @foreach($referrals as $browser)
                                    {y:{{$browser->total_count}}, name: "{{$browser->referral}}"},
                                @endforeach
                        ]
                    }
                ]
            });
        chart1.render();

        var chart = new CanvasJS.Chart("chartContainer-os",
            {
                exportEnabled: true,
                animationEnabled: true,
                legend: {
                    cursor: "pointer",
                    horizontalAlign: "right",
                    verticalAlign: "center",
                    fontSize: 16,
                    padding: {
                        top: 20,
                        bottom: 2,
                        right: 20,
                    },
                },
                data: [
                    {
                        type: "pie",
                        showInLegend: true,
                        legendText: "",
                        toolTipContent: "{name}: <strong>{#percent%} (#percent%)</strong>",
                        indexLabel: "#percent%",
                        indexLabelFontColor: "white",
                        indexLabelPlacement: "inside",
                        dataPoints: [
                            @foreach($browsers as $browser)
                                {y:{{$browser->total_count}}, name: "{{$browser->referral}}"},
                            @endforeach
                        ]
                    }
                ]
            });
chart.render();

    })(jQuery);
    
</script>

@endsection
