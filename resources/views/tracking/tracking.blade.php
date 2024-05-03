<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name=”format-detection” content=”telephone=no”>
    {{-- <link rel="shortcut icon" type="image/x-icon" href="{{$favicon ?? asset('assets/images/favicon.ico') }}"> --}}
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="css/fontawesome.css"> -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('tracking/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('tracking/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('tracking/css/responsive.css') }}">
    <title> Order Tracking</title>
</head>
@php
$task_type_array = [__('Pickup'), __('Drop-Off'), __('Appointment')];
@endphp

<body>

    <!--location Area -->
    <section class="location_wrapper py-xl-5 position-relative d-lg-flex align-items-lg-center">
        <div class="container px-0">
            <div class="row no-gutters">
                <div class="col-12">
                    <div class="map_box">
                        <div style="width: 100%">
                            <div id="map_canvas"></div>
                        </div>
                        {{-- <iframe
                     src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d109782.78861272808!2d76.95784163044429!3d30.698374220997263!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1615288788406!5m2!1sen!2sin"
                     width="100%" style="border:0;" allowfullscreen="" loading="lazy"  scrolling="no"></iframe> --}}
                    </div>
                </div>
            </div>
            <div class="location_box px-3 position-relative get_div_height">
                <div class="row">
                    <div class="offset-lg-0 col-lg-12 offset-md-2 col-md-8">
                        <i class="fas fa-chevron-up detail_btn d-lg-none d-block show_attr_classes"></i>
                        <div class="row no-gutters align-items-center">
                            <div class="col-lg-4 padd-left mb-3">
                                <div class="left-icon">
                                    <img src="{{ 'https://imgproxy.royodispatch.com/insecure/fit/300/100/sm/0/plain/' . Storage::disk('s3')->url($order->profile_picture ?? 'assets/client_00000051/agents605b6deb82d1b.png/XY5GF0B3rXvZlucZMiRQjGBQaWSFhcaIpIM5Jzlv.jpg') }}"
                                        alt="" />
                                </div>
                                <h4>{{ isset($order->name) ? $order->name :__(getAgentNomenclature().' not assigned yet') }}</h4>
                                <p>{{ $order->phone_number }}</p>
                            </div>
                            <span class="col-lg-8 attrbute_classes">
                                <div class="row align-items-center">
                                    @foreach ($tasks as $item)
                                        <div class="col-lg-6 d-flex align-items-center address_box mb-3">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <div class="right_text">
                                                <h4>{{ $task_type_array[$item->task_type_id - 1] }}</h4>
                                                <p>{{ $item->address }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <div class="offset-sm-3 col-sm-6 btn_group d-flex align-items-center justify-content-between">
                    <a class="btn pink_btn" href="tel:{{ $order->phone_number }}"><i
                            class="fas fa-phone-alt position-absolute"></i><span>{{__('Call')}}</span></a>
                    <a class="btn pink_btn" href="sms:{{ $order->phone_number }}"><i
                            class="fas fa-comment position-absolute"></i><span>{{__('Message')}}</span></a>
                </div>
            </div>
        </div>
        </div>
    </section>


    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ asset('tracking/js/jquery-min.js') }}"></script>
    <script defer
        src="https://maps.googleapis.com/maps/api/js?key={{$mapkey}}&libraries=places,drawing,visualization&v=weekly">
    </script>
    <script src="{{ asset('tracking/js/common.js') }}"></script>
    <script src="{{ asset('tracking/js/bootstrap.min.js') }}"></script>

    <script>
        var map = '';
        var alltask = {!! json_encode($tasks) !!};
        var agent_location = {!! json_encode($agent_location) !!};
        var url = window.location.origin;
        var marker = '';
        var directionsService = '';
        var directionsRenderer='';

        if(alltask.length > 0){
            var maplat  = parseFloat(alltask[0]['latitude']);
            var maplong = parseFloat(alltask[0]['longitude']);
        }else{
            var maplat  = 30.7046;
            var maplong = 76.7179;
        }

        $(document).ready(function() {

            initMap();

        });

        themeType = [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [
                    { visibility: "off" }
                ]
            }
        ];



        function initMap() {
             directionsService = new google.maps.DirectionsService();
             directionsRenderer = new google.maps.DirectionsRenderer({suppressMarkers: true});
             map = new google.maps.Map(document.getElementById("map_canvas"), {
                zoom: 6,
                center: {
                    lat: maplat,
                    lng: maplong
                },
                styles: themeType,
            });
            directionsRenderer.setMap(map);
            calculateAndDisplayRoute(directionsService, directionsRenderer,map,agent_location);

            addMarker(agent_location,map);
        }

        function calculateAndDisplayRoute(directionsService, directionsRenderer,map,agent_location) {
            const waypts = [];
            const checkboxArray = document.getElementById("waypoints");

            for (let i = 0; i < alltask.length; i++) {
                if (i != alltask.length - 1 && alltask[i].task_status != 4 && alltask[i].task_status != 5 ) {

                    waypts.push({
                        location: new google.maps.LatLng(parseFloat(alltask[i].latitude), parseFloat(alltask[i]
                            .longitude)),
                        stopover: true,
                    });


                }
                var image = url+'/assets/newicons/'+alltask[i].task_type_id+'.png';

                makeMarker({lat: parseFloat(alltask[i].latitude),lng:  parseFloat(alltask[i]
                            .longitude)},image,map);
            }

            directionsService.route({
                    origin: new google.maps.LatLng(parseFloat(agent_location.lat), parseFloat(agent_location.long)),
                    destination: new google.maps.LatLng(parseFloat(alltask[alltask.length - 1].latitude),
                        parseFloat(alltask[alltask.length - 1].longitude)),
                    waypoints: waypts,
                    optimizeWaypoints: false,
                    travelMode: google.maps.TravelMode.DRIVING,
                },
                (response, status) => {
                    if (status === "OK" && response) {
                        directionsRenderer.setDirections(response);
                        const route = response.routes[0];
                        const summaryPanel = document.getElementById("directions-panel");
                        summaryPanel.innerHTML = "";

                        // For each route, display summary information.
                        for (let i = 0; i < route.legs.length; i++) {
                            const routeSegment = i + 1;
                            summaryPanel.innerHTML +=
                                "<b>Route Segment: " + routeSegment + "</b><br>";
                            summaryPanel.innerHTML += route.legs[i].start_address + " to ";
                            summaryPanel.innerHTML += route.legs[i].end_address + "<br>";
                            summaryPanel.innerHTML += route.legs[i].distance.text + "<br><br>";
                        }
                    } else {
                        //window.alert("Directions request failed due to " + status);
                    }
                }
            );
        }

        // Adds a marker to the map.
        function addMarker(agent_location,map) {

         // Add the marker at the clicked location, and add the next-available label
         // from the array of alphabetical characters.
         var image = {
         url: '{{asset("demo/images/location.png")}}', // url
         scaledSize: new google.maps.Size(50, 50), // scaled size
         origin: new google.maps.Point(0,0), // origin
         anchor: new google.maps.Point(22,22) // anchor
        };
        if (marker && marker.setMap) {
        marker.setMap(null);
        }
        marker = new google.maps.Marker({
            position: {lat: parseFloat(agent_location.lat),lng:  parseFloat(agent_location.long)},
            label: null,
            icon: image,
            map: map,

         });
         }

         function makeMarker( position,icon,map) {
            new google.maps.Marker({
            position: position,
            map: map,
            icon: icon,
            });
         }





         // traking hit api again and agian
         var dispatch_traking_url   = window.location.href;
         setInterval(function(){
            var new_dispatch_traking_url = dispatch_traking_url.replace('/order/','/order-details/');
                    getDriverDetails(new_dispatch_traking_url)
                },4000);


            function getDriverDetails(new_dispatch_traking_url) {
                $.ajax({
                    type:"GET",
                    dataType: "json",
                    url: new_dispatch_traking_url,
                    success: function( response ) {
                        var agent_location_live = response.agent_location;
                        if(agent_location_live != null){
                          // calculateAndDisplayRoute(directionsService, directionsRenderer,map,agent_location_live);
                           addMarker(agent_location_live,map);
                        }
                    }
                });
            }


            // Use the DOM setInterval() function to change the offset of the symbol
// at fixed intervals.
function animateCircle(line) {
  let count = 0;
  window.setInterval(() => {
    count = (count + 1) % 200;
    const icons = line.get("icons");
    icons[0].offset = count / 2 + "%";
    line.set("icons", icons);
  }, 20);
}



    </script>
</body>

</html>
