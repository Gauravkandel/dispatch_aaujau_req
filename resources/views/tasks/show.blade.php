@extends('layouts.vertical', ['title' => __('Route')])

@section('css')
@php
use Carbon\Carbon;
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp
<style>
    #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px;
        position: unset;
    }

    #agent_route_order_track {
        height: 400px;
        width: 100%;
        margin: 0px;
        padding: 0px
    }
</style>
@endsection

@section('content')
@include('modals.add-agent')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{__('Route')}} #{{$task->id}} 

                <a href="{{ route('tasks.index') }}" class="float-right">
                    <button type="button" class="btn btn-blue" title="Back To List" data-keyboard="false"><span><i class="mdi mdi-chevron-double-left mr-1"></i> Back</span></button>
                </a>
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-2 col-sm-6">
            <div class="card-box">
                <h4 class="header-title mb-2">{{__('Customer')}}</h4>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <p class="al_text_overflow"><i class="fa fa-user" aria-hidden="true"></i> {{ (isset($task->customer->name))?$task->customer->name:'' }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <p class="al_text_overflow"><i class="fa fa-phone" aria-hidden="true"></i> {{ (isset($task->customer->phone_number))?$task->customer->phone_number:'' }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <p class="al_text_overflow"><i class="fa fa-envelope" aria-hidden="true"></i> {{ (isset($task->customer->email))?$task->customer->email:'' }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <p class="al_text_overflow"><b>Friend Name: </b><br> {{ (isset($task->friend_name))?$task->friend_name:'' }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <p class="al_text_overflow"><b>Friend Phone Number: </b><br> {{ (isset($task->friend_phone_number))?$task->friend_phone_number:'' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-box">
                <h4 class="header-title mb-2">{{__(getAgentNomenclature())}}</h4>
                @if(empty($task->agent))
                {{__('Unassigned')}}
                @else
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row align-items-center mb-3">
                            <div class="col-3 pr-0 pic-left">
                                <img src="{{ !empty($task->agent->profile_picture) ? $imgproxyurl.Storage::disk('s3')->url($task->agent->profile_picture) : URL::to('/assets/images/user_dummy.jpg') }}" alt="{{__('contact-img')}}" title="{{__('contact-img')}}" class="rounded-circle avatar-sm">
                            </div>
                            <div class="col-9 pl-1">
                                <h5 class="m-0 font-weight-normal">{{ (isset($task->agent->name))?$task->agent->name:'' }}</h5>
                            </div>
                        </div>
                    </div>
                    @if(!empty($task->agent->phone_number))
                    <div class="col-sm-6 col-xl-12">
                        <div class="form-group">
                            <p><i class="fa fa-phone" aria-hidden="true"></i> {{ $task->agent->phone_number }}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($task->agent->type))
                    <div class="col-sm-6 col-xl-12">
                        <div class="form-group">
                            <p><i class="fa fa-text-width" aria-hidden="true"></i> {{ $task->agent->type }}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($task->agent->vehicle_type->name))
                    <div class="col-sm-6 col-xl-12">
                        <div class="form-group">
                            <p><i class="fa fa-truck" aria-hidden="true"></i> {{ ucfirst($task->agent->vehicle_type->name) }}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($task->agent->make_model))
                    <div class="col-sm-6 col-xl-12">
                        <div class="form-group">
                            <p><i class="fa fa-truck" aria-hidden="true"></i> {{ $task->agent->make_model }}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($task->agent->plate_number))
                    <div class="col-sm-6 col-xl-12">
                        <div class="form-group">
                            <p><i class="fa fa-truck" aria-hidden="true"></i> {{ $task->agent->plate_number }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card-box">
                <h4 class="header-title mb-2">{{__('Track Url')}}</h4>
                <div class="site_link position-relative">
                    <a href="{{url('/order/tracking/'.Auth::user()->code.'/'.$task->unique_id.'')}}" target="_blank"><span id="pwd_spn" class="password-span">{{url('/order/tracking/'.Auth::user()->code.'/'.$task->unique_id.'')}}</span></a>
                    <label class="copy_link float-right" id="cp_btn" title="copy">
                        <!-- <i class="far fa-copy"></i> -->
                        <img src="{{ URL::to('/assets/icons/domain_copy_icon.svg') }}" alt="">
                        <span class="copied_txt" id="show_copy_msg_on_click_copy" style="display:none;">{{__('Copied')}}</span>
                    </label>
                </div>
            </div>

            
        </div>

        <div class="col-xl-7">
            <div class="card-box">
                <h4 class="header-title mb-2">{{(($task->request_type=='D')?__('Delivery'):__('Pickup')).' '.__('Task List')}}</h4>
                <div class="al_new_address_box_outer position-relative">
                    @php
                    $tasksLocations = [];
                    @endphp
                    @foreach ($task->task as $singletask)
                    @php
                    if($singletask->task_type_id==1)
                    {
                    $tasktype = "Pickup";
                    $pickup_class = "yellow_";
                    }elseif($singletask->task_type_id==2)
                    {
                    $tasktype = "Dropoff";
                    $pickup_class = "green_";
                    }else{
                    $tasktype = "Appointment";
                    $pickup_class = "assign_";
                    }
                    $tasksLocations[] = ['task_type' => $tasktype, 'latitude' => isset($singletask->location->latitude) ? floatval($singletask->location->latitude):0.00, 'longitude' => isset($singletask->location->longitude) ? floatval($singletask->location->longitude): 0.00, 'address' => isset($singletask->location->address) ? $singletask->location->address : '', 'task_type_id' => $singletask->task_type_id, 'customer_name' => isset($task->customer->name)?$task->customer->name:'', 'customer_phone_number' => isset($task->customer->phone_number)?$task->customer->phone_number:'', 'task_status' => (int)$singletask->task_status];
                    @endphp

                        <div class="address_box mb-1 ">
                            <div class="al_new_address_box d-flex align-items-start mb-3">
                                <div class="al_new_address_box_pickup">
                                    <span class="{{ $pickup_class }} mb-0" > {{ $tasktype }}</span>
                                </div>
                                <div class="al_new_address_box_content pl-3">
                                    <div class="short_name">{{ (isset($singletask->location->short_name))?$singletask->location->short_name:'' }}</div>
                                    <label class="m-0" data-toggle="tooltip" data-placement="bottom" title="{{ (isset($singletask->location->address))?$singletask->location->address:'' }}">{{ (isset($singletask->location->address))?$singletask->location->address:'' }}</label>
                                    <div class="status p-1"> {{ $singletask->status }}</div>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>

            @if(!empty($task->agent))
            <div class="card-box p-2">
                <div id="agent_route_order_track"></div>
                <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d27442.65334027974!2d76.82252954940223!3d30.709075056260815!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1629116296414!5m2!1sen!2sin" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe> -->
            </div>
            @endif
        </div>
    </div>

    <!-- <div class="row">
        <div class="col-xl-6">
            <div class="card-box">
                <div class="row">

                    <div class="col-md-12">
                        <div class="">
                            <h4 class="header-title mb-3">Pay Details</h4>

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Base Price</label> <br>
                                        <span id="base_price">10.00</span>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Duration Price</label> <br>
                                        <span id="duration_price">10.00 (Per min)</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Distance Price</label> <br>
                                        <span id="distance_fee">20.00 (Km)</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label class="control-label">Agent Type</label> <br>

                                        <span id="driver_type"></span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Base Distance</label> <br>
                                        <span id="base_distance">1</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Actual Distance</label> <br>
                                        <span id="actual_distance">0.00</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Billing Distance</label> <br>
                                        <span id="billing_distance">0</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Distance Cost</label> <br>
                                        <span id="distance_cost">0</span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Base Duration</label> <br>
                                        <span id="base_duration">5</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Actual Duration</label> <br>
                                        <span id="actual_duration">0.00</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Billing Duration</label> <br>
                                        <span id="billing_duration">0</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Duration Cost</label> <br>
                                        <span id="duration_cost">0</span>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Order Cost</label>
                                        <h5 id="order_cost">10.00</h5>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Driver Cost</label>
                                        <h5 id="driver_cost">0.00</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Agent Commission %</label> <br>
                                        <span id="agent_commission_percentage">5</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Agent Commission Fixed</label> <br>
                                        <span id="agent_commission_fixed">8</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Freelancer Commission%</label> <br>
                                        <span id="freelancer_commission_percentage">6</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Freelancer Commission Fixed</label> <br>
                                        <span id="freelancer_commission_fixed">7</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>
@endsection

@section('script')
<script>
    $(document).on('click', '.copy_link', function() {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($('#pwd_spn').text()).select();
        document.execCommand("copy");
        $temp.remove();
        $("#show_copy_msg_on_click_copy").show();
        setTimeout(function() {
            $("#show_copy_msg_on_click_copy").hide();
        }, 1000);
    })
</script>
<script>
    var map;
    var directionsDisplay;
    var directionsService = new google.maps.DirectionsService();
    var locations = JSON.parse('{{ json_encode($driver_location_logs) }}');
    var tasksLocationsjson = {!! json_encode($tasksLocations) !!};

    function initialize() {
        directionsDisplay = new google.maps.DirectionsRenderer({
            suppressMarkers: true
        });
        var map = new google.maps.Map(document.getElementById('agent_route_order_track'), {
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        directionsDisplay.setMap(map);
        // var infowindow = new google.maps.InfoWindow();
        var marker, i;
        var request = {
            travelMode: google.maps.TravelMode.DRIVING
        };
        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][0], locations[i][1]),
            });

            // google.maps.event.addListener(marker, 'click', (function(marker, i) {
            //     return function() {
            //         infowindow.setContent(locations[i][0]);
            //         infowindow.open(map, marker);
            //     }
            // })(marker, i));

            if (i == 0) request.origin = marker.getPosition();
            else if (i == locations.length - 1) request.destination = marker.getPosition();
            else {
                if (!request.waypoints) request.waypoints = [];
                request.waypoints.push({
                    location: marker.getPosition(),
                    stopover: true
                });
            }

        }
        directionsService.route(request, function(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(result);
            }
        });

        for (let i = 0; i < tasksLocationsjson.length; i++) {

            checkdata = tasksLocationsjson[i];
            var info = []
            var urlnewcreate = '';
            if (checkdata['task_status'] == 0) {
                urlnewcreate = 'unassigned';
            } else if (checkdata['task_status'] == 1 || checkdata['task_status'] == 2) {
                urlnewcreate = 'assigned';
            } else if (checkdata['task_status'] == 3) {
                urlnewcreate = 'complete';
            } else {
                urlnewcreate = 'faild';
            }

            if (checkdata['task_type_id'] == 1) {
                urlnewcreate += '_P.png';
            } else if (checkdata['task_type_id'] == 2) {
                urlnewcreate += '_D.png';
            } else {
                urlnewcreate += '_A.png';
            }

            image = "{{ asset('assets/newicons/') }}" + "/" + urlnewcreate;

            send = null;
            type = 1;

            var contentString =
                '<div id="content">' +
                '<div id="siteNotice">' +
                "</div>" +
                '<h6 id="firstHeading" class="firstHeading">' + checkdata['task_type'] + '</h6>' +
                '<div id="bodyContent">' +
                "<p><b>Address :- </b> " + checkdata['address'] + " " +
                ".</p>" +
                '<p><b>Customer: ' + checkdata['customer_name'] + '</b>(' + checkdata['customer_phone_number'] + ') </p>' +
                "</div>" +
                "</div>";

            const marker1 = new google.maps.Marker({
                position: {
                    lat: parseFloat(checkdata['latitude']),
                    lng: parseFloat(checkdata['longitude'])
                },
                map: map,
                icon: image,
                animation: google.maps.Animation.DROP,
            });

            const infowindow = new google.maps.InfoWindow({
                content: contentString,
                minWidth: 250,
                minheight: 250,
            });
            google.maps.event.addListener(marker1, "click", () => {
                infowindow.open(map, marker1);
            });
        }
    }

    google.maps.event.addDomListener(window, "load", initialize);
</script>
@endsection
