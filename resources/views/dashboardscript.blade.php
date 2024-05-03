<script>
    
    
    
    
    function gm_authFailure() {
    
    $('.excetion_keys').append('<span><i class="mdi mdi-block-helper mr-2"></i> <strong>Google Map</strong> key is not valid</span><br/>');
    $('.displaySettingsError').show();
    };
    
    // var marker;
    var show = [0];
    let map;
    let markers = [];
    
    var url = window.location.origin;
    
    var olddata  = {!!json_encode($newmarker)!!};
    var allagent = {!!json_encode($agents)!!};
    var theme    = {!!json_encode($theme->theme)!!};
    if(theme == 'dark'){
    var themeType = [
            { elementType: "geometry", stylers: [{ color: "#242f3e" }] },
            {
            elementType: "labels.text.stroke",
            stylers: [{ color: "#242f3e" }],
            },
            {
            elementType: "labels.text.fill",
            stylers: [{ color: "#746855" }],
            },
            {
            featureType: "administrative.locality",
            elementType: "labels.text.fill",
            stylers: [{ color: "#d59563" }],
            },
            {
            featureType: "poi",
            elementType: "labels.text.fill",
            stylers: [{ color: "#d59563" }],
            },
            {
            featureType: "poi.park",
            elementType: "geometry",
            stylers: [{ color: "#263c3f" }],
            },
            {
            featureType: "poi.park",
            elementType: "labels.text.fill",
            stylers: [{ color: "#6b9a76" }],
            },
            {
            featureType: "road",
            elementType: "geometry",
            stylers: [{ color: "#38414e" }],
            },
            {
            featureType: "road",
            elementType: "geometry.stroke",
            stylers: [{ color: "#212a37" }],
            },
            {
            featureType: "road",
            elementType: "labels.text.fill",
            stylers: [{ color: "#9ca5b3" }],
            },
            {
            featureType: "road.highway",
            elementType: "geometry",
            stylers: [{ color: "#746855" }],
            },
            {
            featureType: "road.highway",
            elementType: "geometry.stroke",
            stylers: [{ color: "#1f2835" }],
            },
            {
            featureType: "road.highway",
            elementType: "labels.text.fill",
            stylers: [{ color: "#f3d19c" }],
            },
            {
            featureType: "transit",
            elementType: "geometry",
            stylers: [{ color: "#2f3948" }],
            },
            {
            featureType: "transit.station",
            elementType: "labels.text.fill",
            stylers: [{ color: "#d59563" }],
            },
            {
            featureType: "water",
            elementType: "geometry",
            stylers: [{ color: "#17263c" }],
            },
            {
            featureType: "water",
            elementType: "labels.text.fill",
            stylers: [{ color: "#515c6d" }],
            },
            {
            featureType: "water",
            elementType: "labels.text.stroke",
            stylers: [{ color: "#17263c" }],
            },
            {
            featureType: "poi",
            elementType: "labels",
            stylers: [
                { visibility: "off" }
            ]
           },
        ];
    }else{
    themeType = [
        {
            featureType: "poi",
            elementType: "labels",
            stylers: [
                { visibility: "off" }
            ]
        }
    ];
    }
    // var teamdata = {!!json_encode($teams)!!};
    // var cars    = [0];
    
    $('.newchecks').click(function() {
    var val = [];
    $('.newchecks:checkbox:checked').each(function(i) {
        val[i] = parseInt($(this).val());
    });
    setMapOnAll(null);
    $(".taskchecks").prop('checked', false);
    $(".agentdisplay").prop('checked', false);
    //   if (!$(this).is(':checked')) {
    //    return confirm("Are you sure?");
    //   }
    //console.log(val);
    for (let i = 0; i < olddata.length; i++) {
        checkdata = olddata[i];
        var info = []
        //alert(val);
        // addMarker({ lat: checkdata[3], lng: checkdata[4] });
        if ($.inArray(checkdata['team_id'], val) != -1 || $.inArray(-1, val) != -1) {
            
            var urlnewcreate = '';
                if(checkdata['task_status'] == 0){
                    urlnewcreate = 'unassigned';
                }else if(checkdata['task_status'] == 1 || checkdata['task_status'] == 2){
                    urlnewcreate = 'assigned';
                }else if(checkdata['task_status'] == 3){
                    urlnewcreate = 'complete';
                }else{
                    urlnewcreate = 'faild';
                }
                
                if(checkdata['task_type_id'] == 1){
                        urlnewcreate += '_P.png';
                }else if(checkdata['task_type_id'] == 2){
                        urlnewcreate +='_D.png';
                }else{
                        urlnewcreate +='_A.png';
                }    
            
            image = '{{ asset('assets/newicons/') }}'+'/'+urlnewcreate;
    
            send = null;
            type = 1;
    
            addMarker({
                lat: checkdata['latitude'],
                lng: checkdata['longitude']
            }, send, image,checkdata,type);
        }
    }
    
    
    
    });
    
    $('.taskchecks').click(function() {
    var taskval = [];
    $('.taskchecks:checkbox:checked').each(function(i) {
        taskval[i] = parseInt($(this).val());
    
    });
    
    
    setMapOnAll(null);
    $(".newchecks").prop('checked', false);
    $(".agentdisplay").prop('checked', false); 
    //$('.taskchecks:checkbox').removeAttr('checked');
    //   if (!$(this).is(':checked')) {
    //    return confirm("Are you sure?");
    //   }
    for (let i = 0; i < olddata.length; i++) {
        checkdata = olddata[i];
        console.log(checkdata);
        //console.log(checkdata[5]);
        // addMarker({ lat: checkdata[3], lng: checkdata[4] });
        //alert(checkdata['task_status']);
        if($.inArray(checkdata['task_status'], taskval) !== -1 || $.inArray(5, taskval) != -1) {
            
            var urlnewcreate = '';
            if(checkdata['task_status'] == 0){
                urlnewcreate = 'unassigned';
            }else if(checkdata['task_status'] == 1 || checkdata['task_status'] == 2){
                urlnewcreate = 'assigned';
            }else if(checkdata['task_status'] == 3){
                urlnewcreate = 'complete';
            }else{
                urlnewcreate = 'faild';
            }
            
                if(checkdata['task_type_id'] == 1){
                    urlnewcreate += '_P.png';
                }else if(checkdata['task_type_id'] == 2){
                    urlnewcreate +='_D.png';
                }else{
                    urlnewcreate +='_A.png';
                }
                
                image = '{{ asset('assets/newicons/') }}'+'/'+urlnewcreate;
                
                send = null;
                type = 1;
            addMarker({lat:checkdata['latitude'],lng:checkdata['longitude']}, send,image,checkdata,type);
        }
    }
    
    
    });
    
    
    $('.agentdisplay').click(function() {
    var agentval = [];
    $('.agentdisplay:checkbox:checked').each(function(i) {
        agentval[i] = parseInt($(this).val());
    });
    setMapOnAll(null);
    $(".taskchecks").prop('checked', false);
    $(".newchecks").prop('checked', false);
    //   if (!$(this).is(':checked')) {
    //    return confirm("Are you sure?");
    //   }
    //console.log(agentval);
    
    
    for (let i = 0; i < allagent.length; i++) {
        checkdata = allagent[i];
        //console.log(checkdata);
        // addMarker({ lat: checkdata[3], lng: checkdata[4] });
        if ($.inArray(checkdata['is_available'], agentval) != -1 || $.inArray(2, agentval) != -1) {
            
            if (checkdata['is_available'] == 1) {
                images = url+'/demo/images/location.png';
            }else {
                images = url+'/demo/images/location_grey.png';
            }
            var image = {
             url: images, // url
             scaledSize: new google.maps.Size(50, 50), // scaled size
             origin: new google.maps.Point(0,0), // origin
             anchor: new google.maps.Point(0, 0) // anchor
            };
            send = null;
            type = 2;
           addMarker({lat: parseFloat(checkdata.agentlog['lat']),lng:  parseFloat(checkdata.agentlog['long'])}, send, image,checkdata,type);
        }
    }
    
    
    
    });
    
    
    
    function initMap() {
    
        const haightAshbury = {
            lat: 30.7046,
            lng: 76.7179
        };
    
        map = new google.maps.Map(document.getElementById("map_canvas"), {
            zoom: 12,
            center: haightAshbury,
            mapTypeId: "roadmap",
            styles: themeType,
        });
    // This event listener will call addMarker() when the map is clicked.
    // map.addListener("click", (event) => {
    //   addMarker(event.latLng);
    // });
    // Adds a marker at the center of the map.
        for (let i = 0; i < olddata.length; i++) {
            checkdata = olddata[i];
        // console.log(checkdata);
        var urlnewcreate = '';
            if(checkdata['task_status'] == 0){
                urlnewcreate = 'unassigned';
            }else if(checkdata['task_status'] == 1 || checkdata['task_status'] == 2){
                urlnewcreate = 'assigned';
            }else if(checkdata['task_status'] == 3){
                urlnewcreate = 'complete';
            }else{
                urlnewcreate = 'faild';
            }
            
                if(checkdata['task_type_id'] == 1){
                    urlnewcreate += '_P.png';
                }else if(checkdata['task_type_id'] == 2){
                    urlnewcreate +='_D.png';
                }else{
                    urlnewcreate +='_A.png';
                }
                
                img = '{{ asset('assets/newicons/') }}'+'/'+urlnewcreate;
               
                send = null;
                type = 1;
            addMarker({
                lat: checkdata['latitude'],
                lng: checkdata['longitude']
            }, send, img,checkdata,type);
        }
    }
    
    
    // Adds a marker to the map and push to the array.
    function addMarker(location, lables, images,data,type) {
    var contentString = '';
    if(type == 1){
        contentString =
        '<div id="content">' +
        '<div id="siteNotice">' +
        "</div>" +
        '<h5 id="firstHeading" class="firstHeading">'+data['driver_name']+'</h5>' +
        '<h6 id="firstHeading" class="firstHeading">'+data['task_type']+'</h6>' +
        '<div id="bodyContent">' +
        "<p><b>Address :- </b> " +data['address']+ " " +
        ".</p>" +
        '<p><b>Customer: '+data['customer_name']+'</b>('+data['customer_phone_number']+') </p>' +
        "</div>" +
        "</div>";
    }else{
        img = data['image_url'];
        contentString =
        '<div style="float:left">'+
        '<img src="{{\Phumbor::url(\Storage::disk("s3")->url("assets/client_00000125/agents5fc76c71abdb3.png/A9B2zHkr5thbcyTKHivaYm4kNYrSXOiov6USdFpV.png"))->fitIn(90,50)}}">'+
        "</div>"+
        '<div style="float:right; padding: 10px;"><b>'+data['name']+'</b><br/><br/>'+data['phone_number']+'</div>';
    }
    
    
    
    const infowindow = new google.maps.InfoWindow({
        content: contentString,
        maxWidth: 250,
        maxheight: 250,
    });
    
    
    
    
    
    
    
    
    const marker = new google.maps.Marker({
        position: location,
        label: lables,
        icon: images,
        map: map,
        animation: google.maps.Animation.DROP,
    });
    markers.push(marker);
    
    marker.addListener("click", () => {
       infowindow.open(map, marker);
    });
    
    }
    
    // Sets the map on all markers in the array.
    function setMapOnAll(map) {
    
    for (let i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    }
    
    // Removes the markers from the map, but keeps them in the array.
    function clearMarkers() {
    setMapOnAll(null);
    }
    
    // Shows any markers currently in the array.
    function showMarkers() {
    setMapOnAll(map);
    }
    
    // Deletes all markers in the array by removing references to them.
    function deleteMarkers() {
    clearMarkers();
    markers = [];
    }
    
    
    $(".datetime").on('change', function postinput(){
    
    
    var matchvalue = $(this).val(); // this.value
    newabc =  url+'?date='+matchvalue;
    
    
    window.location.href = newabc;
    
    });
    
    
    </script>