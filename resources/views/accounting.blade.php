@extends('layouts.vertical', ['title' => 'Analytics','demo'=>'creative'])

@section('css')
    <!-- Plugins css -->
    <link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/selectize/selectize.min.css')}}" rel="stylesheet" type="text/css" />

    <style>
        #map {
        height: 485px;
        /* The height is 400 pixels */
        width: 100%;
        /* The width is the width of the web page */
        }

        .stv-radio-buttons-wrapper {
        clear: both;
        display: inline-block;
        }

        .stv-radio-button {
        position: absolute;
        left: -9999em;
        top: -9999em;
        }
        .stv-radio-button + label {
    float: left;
    padding: 0.3em .8em;
    cursor: pointer;
    border: 1px solid #697480;
    margin-right: -1px;
    color: #fff;
    background-color: #697480;
    font-size: 12px;
}
        .stv-radio-button + label:first-of-type {
        border-radius: 0.7em 0 0 0.7em;
        }
        .stv-radio-button + label:last-of-type {
        border-radius: 0 0.7em 0.7em 0;
        }
        .stv-radio-button:checked + label {
    background-color: #3c4854;
    border: 1px solid #3c4854;
}
    </style>

@endsection
@php
    $imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <form class="form-inline" name="reset" id="resetaccunting" method="get" action="{{route('accounting')}}">
                                <div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control border" id="dash-daterange" name="date">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-blue border-blue text-white">
                                                <i class="fe-search" onclick="handleClick(this);" ></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <a href="javascript: void(0);" class="btn btn-blue btn-sm ml-2">
                                    <i class="mdi mdi-autorenew"></i>
                                </a>
                                <a href="javascript: void(0);" class="btn btn-blue btn-sm ml-1">
                                    <i class="mdi mdi-filter-variant"></i>
                                </a>
                            </form>
                        </div>
                        {{-- <h4 class="page-title">Tasks</h4> --}}
                    </div>
                </div>
            </div>


        <div class="row">

            <div class="col-md-12">
                <h3 class="page-title">{{__("Analytics")}}</h3>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="widget-rounded-circle card-box">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                               <i class="fe-heart font-22 avatar-title text-primary"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-right">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{$totalearning}}</span></h3>
                                <p class="text-muted mb-1 text-truncate">{{__("Platform Earning")}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="widget-rounded-circle card-box">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                <i class="fe-shopping-cart font-22 avatar-title text-success"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-right">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{$totalagentearning}}</span></h3>
                                <p class="text-muted mb-1 text-truncate">{{__(getAgentNomenclature()."s's Earning")}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="widget-rounded-circle card-box">
                    <div class="row">
                        <div class="col-6">

                            <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                <i class="fe-bar-chart-line- font-22 avatar-title text-info"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-right">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{$totalorders}}</span></h3>
                                <p class="text-muted mb-1 text-truncate">{{__("Orders")}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="widget-rounded-circle card-box">
                    <div class="row">
                        <div class="col-6">

                            <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                <i class="fe-eye font-22 avatar-title text-warning"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-right">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{$totalagents}}</span></h3>
                                <p class="text-muted mb-1 text-truncate">{{__(getAgentNomenclature()."s")}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>


        <div class="row">
            <div class="col-lg-4">
                <div class="card-box pb-0 h-100">
                    <div >
                        <div id="map"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card-box pb-2 h-100">
                    <div class="row d-flex align-items-center">
                        <div class="col-md-6">
                            <h4 class="header-title mb-3">{{__("Analytics")}}</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <form class="" name="chatreset" id="chatreset" method="get" action="{{route('accounting')}}">
                                <div class="float-right d-none d-md-inline-block">
                                    <div class="stv-radio-buttons-wrapper">
                                        <input type="radio" class="stv-radio-button" name="type" onclick="handleChat(this);" value="1" id="button1" {{isset($type) && $type == 1 ? 'checked':''}}/>
                                        <label for="button1">{{__("Today")}}</label>
                                        <input type="radio" class="stv-radio-button" name="type" onclick="handleChat(this);" value="2" id="button2" {{isset($type) && $type == 2 ? 'checked':''}}/>
                                        <label for="button2">{{__("Weekly")}}</label>
                                        <input type="radio" class="stv-radio-button" name="type" onclick="handleChat(this);" value="3" id="button3" {{$type == 3 ? 'checked':''}}/>
                                        <label for="button3">{{__("Monthly")}}</label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>



                    <div dir="ltr">
                        <div id="sales-analytics" class="mt-4" data-colors="#1abc9c,#4a81d4"></div>
                    </div>
                </div>
            </div>
        </div>



        <br>

        <div class="row">
            <div class="col-xl-6">
                <div class="card-box">
                    {{-- <div class="dropdown float-right">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">

                            <a href="javascript:void(0);" class="dropdown-item">Edit Report</a>

                            <a href="javascript:void(0);" class="dropdown-item">Export Report</a>

                            <a href="javascript:void(0);" class="dropdown-item">Action</a>
                        </div>
                    </div> --}}

                    <h4 class="header-title mb-3">{{__(getAgentNomenclature()."s")}}</h4>

                    <div class="table-responsive">
                        <table class="table table-borderless table-hover table-nowrap table-centered m-0">

                            <thead class="thead-light">
                                <tr>
                                    <th colspan="2">{{__("Profile")}}</th>

                                    <th>{{__("Cash at hand")}}</th>
                                    <th>{{__("Phone Number")}}</th>
                                    <th>{{__("Type")}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agents as $agent)
                                <tr>
                                    <td style="width: 36px;">
                                        <img src="{{$imgproxyurl.Storage::disk('s3')->url($agent->profile_picture)}}" alt="contact-img" title="contact-img" class="rounded-circle avatar-sm" />
                                    </td>

                                    <td>
                                        <h5 class="m-0 font-weight-normal">{{$agent->name}}</h5>
                                        <p class="mb-0 text-muted"><small>{{__("Member Since")}} {{ \Carbon\Carbon::parse($agent->created_at)->format('Y')}}</small></p>
                                    </td>


                                    <td>
                                        {{round($agent->cash_at_hand)}}
                                    </td>

                                    <td>
                                        {{$agent->phone_number}}
                                    </td>

                                    <td>
                                        @if ($agent->type == 'Employee')
                                        <span class="badge bg-soft-success text-success">{{__($agent->type)}}</span>
                                        @else
                                        <span class="badge bg-soft-danger text-danger">{{__($agent->type)}}</span>
                                        @endif

                                    </td>

                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card-box">


                    <h4 class="header-title mb-3">{{__("Customers")}}</h4>

                    <div class="table-responsive">
                        <table class="table table-borderless table-hover table-nowrap table-centered m-0">

                            <thead class="thead-light">
                                <tr>
                                    <th >{{__("Name")}}</th>
                                    <th>{{__("Total Spent")}}</th>
                                    <th>{{__("Phone Number")}}</th>
                                    <th>{{__("Total Orders")}}</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                  <tr>
                                    <td>
                                        <h5 class="m-0 font-weight-normal">{{$customer->name}}</h5>
                                        <p class="mb-0 text-muted"><small>{{__("Member Since")}} {{ \Carbon\Carbon::parse($customer->created_at)->format('Y')}}</small></p>
                                    </td>

                                    <td>
                                        {{ $customer->orders->sum('order_cost') }}
                                    </td>

                                    <td>
                                        {{$customer->phone_number}}
                                    </td>

                                    <td>
                                        {{$customer->orders_count}}
                                    </td>


                                  </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>




    </div> <!-- container -->

@endsection

@section('script')
    <!-- Plugins js-->


    {{-- <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB85kLYYOmuAhBUPd7odVmL6gnQsSGWU-4&callback=initMap&libraries=visualization&v=weekly"
      defer
    ></script> --}}

    <script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>
    <script src="{{asset('assets/libs/selectize/selectize.min.js')}}"></script>

    <!-- Dashboar 1 init js-->
    {{-- <script src="{{asset('assets/js/pages/dashboard-1.init.js')}}"></script> --}}
    <script>
        $(document).ready(function() {
            initMap();

        });
        let map, heatmap;
        var heatLatLog  = {!!json_encode($heatLatLog)!!};

        if(heatLatLog.length > 0){
            var lat  = parseFloat(heatLatLog[0]['latitude']);
            var long = parseFloat(heatLatLog[0]['longitude']);
        }else{
            // var lat  = 37.775;
            // var long = -122.434;
            var lat  = 30.7046;
            var long = 76.7179;
        }
        function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 10,
            styles: themeType,
            center: { lat: lat, lng: long },
            mapTypeId: "roadmap",
        });
        heatmap = new google.maps.visualization.HeatmapLayer({
            data: getPoints(),
            map: map,
        });
        }

        function toggleHeatmap() {
        heatmap.setMap(heatmap.getMap() ? null : map);
        }

        function changeGradient() {
            const gradient = [
                "rgba(0, 255, 255, 0)",
                "rgba(0, 255, 255, 1)",
                "rgba(0, 191, 255, 1)",
                "rgba(0, 127, 255, 1)",
                "rgba(0, 63, 255, 1)",
                "rgba(0, 0, 255, 1)",
                "rgba(0, 0, 223, 1)",
                "rgba(0, 0, 191, 1)",
                "rgba(0, 0, 159, 1)",
                "rgba(0, 0, 127, 1)",
                "rgba(63, 0, 91, 1)",
                "rgba(127, 0, 63, 1)",
                "rgba(191, 0, 31, 1)",
                "rgba(255, 0, 0, 1)",
            ];
                heatmap.set("gradient", heatmap.get("gradient") ? null : gradient);
        }

        function changeRadius() {
            heatmap.set("radius", heatmap.get("radius") ? null : 20);
        }

        function changeOpacity() {
            heatmap.set("opacity", heatmap.get("opacity") ? null : 0.2);
        }

        // Heatmap data: 500 Points
        function getPoints() {
            var data = [];
            for (let i = 0; i < heatLatLog.length; i++) {
                checkdata = heatLatLog[i];

                data.push(new google.maps.LatLng(checkdata['latitude'],checkdata['longitude']));

            }
            return data;
        }

        //chart code goes here
        var countOrders  = {!!json_encode($countOrders)!!};
        var sumOrders    = {!!json_encode($sumOrders)!!};
        var dates        = {!!json_encode($dates)!!};

        var colors = ['#f1556c'];
        var dataColors = $("#total-revenue").data('colors');
        if (dataColors) {
            colors = dataColors.split(",");
        }
        var options = {
            series: [68],
            chart: {
                height: 220,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '65%',
                    }
                },
            },
            colors: colors,
            labels: ['Revenue'],
        };

        var chart = new ApexCharts(document.querySelector("#total-revenue"), options);
        chart.render();


        //
        // Sales Analytics
        //
        var colors = ['#1abc9c', '#4a81d4'];
        var dataColors = $("#sales-analytics").data('colors');
        if (dataColors) {
            colors = dataColors.split(",");
        }

        var options = {
            series: [{
                name: 'Earning',
                type: 'column',
                data: sumOrders
            }, {
                name: 'Orders',
                type: 'line',
                data: countOrders
            }],
            chart: {
                height: 378,
                type: 'line',
            },
            stroke: {
                width: [2, 3]
            },
            plotOptions: {
                bar: {
                    columnWidth: '50%'
                }
            },
            colors: colors,
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1]
            },
            labels: dates,
            xaxis: {
                type: 'datetime'
            },
            legend: {
                offsetY: 7,
            },
            grid: {
                padding: {
                bottom: 20
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: "horizontal",
                    shadeIntensity: 0.25,
                    gradientToColors: undefined,
                    inverseColors: true,
                    opacityFrom: 0.75,
                    opacityTo: 0.75,
                    stops: [0, 0, 0]
                },
            },
            yaxis: [{
                title: {
                    text: 'Net Earning',
                },

            }, {
                opposite: true,
                title: {
                    text: 'Number of Orders'
                }
            }]
        };

    var chart = new ApexCharts(document.querySelector("#sales-analytics"), options);
    chart.render();

    // Datepicker
    $('#dash-daterange').flatpickr({
        altInput: true,
        mode: "range",
        altFormat: "F j, y",
        defaultDate: 'today'
    });

    function handleClick(myRadio) {
        $('#resetaccunting').submit();
    }

    function handleChat(myRadio) {
        $('#chatreset').submit();
    }

    </script>

@endsection
