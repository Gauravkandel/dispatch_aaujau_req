<script>
        var map = '';
        var alltask = {!! json_encode($task_locations) !!};
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