@php
if( isset($_POST['name']) ){
echo $_POST['name'];
exit;
}

    $imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';

@endphp

@extends('layouts.vertical', ['title' => 'Geo Fence'])


@section('css')
    
    <style>
        #map-canvas {
            height: 90%;
            margin: 0px;
            padding: 0px;
            position: unset;
        }

        .serch {
            width: 100%;
            margin: 0px;
        }

        .imageagent {
            border-radius: 50%;
            height: 40px;
            width: 40px;
            margin-right: 15px;
        }

        .cornar {
            border: 1px solid #ddd;
            padding-top: 10px;
            height: 240px;
            overflow-y: auto;
            padding: 10px;
        }

        .teamshow {
            margin-left: 58px;

        }

        .display {
            height: 35px;
            width: 67px;
        }

        .new {
            vertical-align: initial !important;
            display: revert !important;
        }

        #new_show {
            display: none;
        }

        .agentcheck {}

        .search {
            background-color: #02f2cc;
        }
        .card-box {

            padding: 1.5rem;
            box-shadow: 0 0 8px 0 rgba(154, 161, 171, 0.15) !important;
            margin-bottom: 24px !important;
            border-radius: 0.25rem;
            /* margin-bottom: 10px; */
        }
        .select_all{
            position: relative;
            top: 8px;
        }


    </style>
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row align-items-center">
            <div class="col-5">
                <div class="page-title-box">
                    <h4 class="page-title"> <a href="{{ route('geo.fence.list') }}">
                            <h4 class="page-title">{{__("Back")}}</h4>
                        </a></h4>
                </div>
            </div>
            <div class="col-7">
                <div class="input-group p-4">

                    <input type="text" id="pac-input" class="form-control" placeholder="Search by name " aria-label="Recipient's username" aria-describedby="button-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" id="refresh">{{__("Edit Mode")}}</button>
                    </div>
                    
                  </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="text-sm-left">
                    @if (\Session::has('success'))
                    <div class="alert alert-success">
                        <span>{!! \Session::get('success') !!}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <form id="geo_form" class="add_new_geo" action="{{ route('geo-fence.store') }}" method="POST">
            @csrf
            <input type="hidden" name="latlongs" value="" id="latlongs" />
            <input type="hidden" name="zoom_level" value="13" id="zoom_level" />
            <div class="row">
                <div class="col-lg-5">
                    <div class="card-box card_outer mb-0">
                        <h4 class="header-title mb-3">{{__("Add Geofence")}}</h4>
                        <div class="top_items">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label for="name">{{__("Name")}}</label>
                                        <input type="text" name="name" id="name" placeholder="ABC Deliveries"
                                            class="form-control">
                                        @if ($errors->has('name'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label for="Description">{{__("Description (Optional)")}}</label>
                                        <textarea class="form-control" id="Description" name="description"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{__("Team")}}</label> <br />
                                        <select id="selectize-select" name="team_id">
                                            @if(Auth::user()->is_superadmin == 1 || Auth::user()->all_team_access == 1)
                                            <option value="0">{{__("All")}}</option>
                                            @endif
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox select_all" id="old_show">
                                                    <input type="checkbox" class="custom-control-input all" id="checkmeout0">
                                                    <label class="custom-control-label select_all" for="checkmeout0">{{__("Select All")}}
                                                        {{ getAgentNomenclature() }}</label>
                                                </div>
                                                <div class="custom-control custom-checkbox show_alls" id="new_show">
                                                    <input type="checkbox" class="custom-control-input" id="show_all">
                                                    <label class="custom-control-label" for="show_all">{{__("Show All")}}
                                                        {{ getAgentNomenclature() }}</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="search" placeholder={{__("Search")}}
                                                    class="form-control newsearch" id="search">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="geo_middle" id="scroll-bar">
                            <div class="row mb-2 cornar">

                                @foreach ($agents as $agent)
                                    <div
                                        class="col-xl-12 agent_boxes team_{{ $agent->team_id ?? 0 }} agent_{{ $agent->id }}">
                                        <div class="boxes card-box mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <input type="checkbox"
                                                            class="custom-control-input agent_checkbox team_checkbox_{{ $agent->team_id ?? 0 }}"
                                                            id="{{ $agent->id }}" name="agents[]" value="{{ $agent->id }}">
                                                        <label class="custom-control-label new" for="{{ $agent->id }}"></label>
                                                        <img class="imageagent"
                                                            src="{{isset($agent->profile_picture) ? $imgproxyurl.Storage::disk('s3')->url($agent->profile_picture) : '' }}"
                                                            alt="" style="border-radius:50%; ">
                                                    </div>
                                                    <div class="col-10">
                                                        <span class="spans">{{ $agent->name }}</span><br>
                                                        <span>{{ isset($agent->team->name) ? $agent->team->name : 'No Team Alloted' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="geo_bottom_btns">
                            <div class="row">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <button type="button"
                                        class="btn btn-block btn-outline-blue waves-effect waves-light mb-0">{{__("Cancel")}}</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-block btn-blue waves-effect waves-light">{{__("Save")}}</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-7">
                    
                    <div class="card-box mb-0 map-outer">

                        <div id="map-canvas"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @php
    $key = session('preferences.map_key_1') != null ? session('preferences.map_key_1'):'kdsjhfkjsdhfsf';
   @endphp
@endsection

@section('script')

    <!-- google maps api -->
    <script
        src="https://maps.google.com/maps/api/js?key={{$key}}&v=3.exp&libraries=drawing,places">
    </script>

    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
   
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        var map; // Global declaration of the map
        var iw = new google.maps.InfoWindow(); // Global declaration of the infowindow
        var lat_longs = new Array();
        var markers = new Array();
        var drawingManager;
        var no_parking_geofences_json = '{!!  json_encode($all_coordinates) !!}';
        var newlocation = '<?php echo json_encode($coninates); ?>';
        var first_location = JSON.parse(newlocation);
        var lat = parseFloat(first_location.lat);
        var lng = parseFloat(first_location.lng);


        // function gm_authFailure() {
                
        //         $('.excetion_keys').append('<span><i class="mdi mdi-block-helper mr-2"></i> <strong>Google Map</strong> key is not valid</span><br/>');
        //         $('.displaySettingsError').show();
        // };

        function deleteSelectedShape() {
            drawingManager.setMap(null);
        }
        // console.log(first_location);
        function initialize() {

            var myLatlng = new google.maps.LatLng(lat, lng);
            var myOptions = {
                zoom: 13,
                center: myLatlng,
                styles: themeType,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            // Bias the SearchBox results towards current map's viewport.

            map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
       

            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [google.maps.drawing.OverlayType.POLYGON]
                },
                
                polygonOptions: {

                    editable: true,
                    draggable: true,
                    strokeColor: '#bb3733',
                    fillColor: '#bb3733',
                }
            });

            drawingManager.setMap(map);

            



            google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
                var newShape = event.overlay;
                newShape.type = event.type;

            });

            google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
                overlayClickListener(event.overlay);
                var vertices_val = $('#latlongs').val();
                //var vertices_val = event.overlay.getPath().getArray();
                console.log(vertices_val);
                if (vertices_val == null || vertices_val === '') {
                    $('#latlongs').val(event.overlay.getPath().getArray());
                    console.log(map.getZoom());
                    $('#zoom_level').val(map.getZoom());
                } else {
                    alert('You can draw only one zone at a time');
                    event.overlay.setMap(null);
                }
            });

            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    const icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25),
                    };
                    // Create a marker for each place.
                    markers.push(
                        new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                        })
                    );

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }

        function overlayClickListener(overlay) {
            google.maps.event.addListener(overlay, "mouseup", function(event) {
                $('#latlongs').val(overlay.getPath().getArray());
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);

        google.maps.event.addDomListener(document.getElementById('refresh'), 'click', deleteSelectedShape);

        $(function() {
            $('#save').click(function() {
                //iterate polygon latlongs?
            });
        });


        // onteam change change the selected agents in the list //

        // $(function() {
        //     $('#checkmeout0').change(function() {
        //         if (this.checked) {
        //             $('.agent-selection select option').each(function() {
        //                 $(this).attr('selected', true);
        //             });
        //         } else {
        //             $('.agent-selection select option').each(function() {
        //                 $(this).attr('selected', false);
        //             });
        //         }
        //         $('#agents').trigger('change');
        //     });
        // });

        $(function() {
            $('#selectize-select').change(function() {
                var team_id = $(this).children("option:selected").val();
                var team_array = [];
                team_array.push(team_id);

                $('.agent-selection select option').each(function() {
                    $(this).attr('selected', false);
                });
                $('.agent-selection select option').each(function() {
                    if ($(this).attr('data-team-id') == team_array[0]) {
                        $(this).attr('selected', true);
                    }
                }, team_array);
                $('#agents').trigger('change');
            });
        });

        $("#geo_form").on("submit", function(e) {

            var lat = $('#latlongs').val();
            var trainindIdArray = lat.replace("[", "").replace("]", "").split(',');
            var length = trainindIdArray.length;
           
            if (length < 6) {
                
                Swal.fire(
                    'Select Location?',
                    'Please Drow a Location On Map first',
                    'question'
                )
                e.preventDefault();
            }


        })

        $(".all").click(function() {
            if ($(this).is(':checked')) {
                var select = $("#selectize-select option:selected").val();
                if (select == 0) {
                    $('.agent_checkbox').prop('checked', this.checked);
                    //$('.agent_checkbox').attr('checked', true);
                    $(".agent_boxes").addClass("selected");
                }
                $('.team_checkbox_' + select).prop('checked', this.checked);
                $(".team_" + select).addClass("selected");
            } else {
                $('.agent_checkbox').prop('checked', false);
                $(".agent_boxes").removeClass("selected");
            }
        });

        $("#show_all").click(function() {
            if ($(this).is(':checked')) {
                $('#search').val('');
                $('div.agent_boxes').show();
                $("#old_show").show();
                $("#new_show").hide();
                $('#show_all').prop('checked', false);
            }
        });

        // $('select').on('change', function(e) {
        //     var select = $("#selectize-select option:selected").val();
        // });

        $('select').on('change', function(e) {
            var select = $("#selectize-select option:selected").val();
            $('#checkmeout0').prop('checked', false);
            $('#show_all').prop('checked', false);
            $("#old_show").show();
            $("#new_show").hide();
            if (select == 0) {
                $('.agent_boxes').show();
            } else {
                $('.agent_boxes').hide();
                $('.selected').show();
                $('.team_' + select).show();
            }


        });

        $(".agent_checkbox").click(function() {
            var id = $(this).attr('id');
            var isChecked = $(this).prop('checked');
            console.log(id);
            console.log(isChecked);
            if (isChecked)
                $(".agent_" + id).addClass("selected");
            else
                $(".agent_" + id).removeClass("selected");
        });

        var searchRequest = null;

        $(function() {
            var minlength = 3;

            $("#search").keyup(function() {
                $("#old_show").show();
                $("#new_show").hide();
                value = $('.newsearch').val();
                $('div.agent_boxes').show();
                if (value.length >= minlength) {
                    $("#old_show").hide();
                    $("#new_show").show();
                    if (searchRequest != null)
                        var query = $.trim($.prevAll('.newsearch').val()).toLowerCase();
                    $('div.agent_boxes .spans').each(function() {
                        var $this = $(this);
                        if ($this.text().toLowerCase().indexOf(value = value.trim()
                                .toLowerCase()) === -1)
                            $this.closest('div.agent_boxes').removeClass('selected').hide()
                            .find('[type=checkbox]').prop('checked', false);
                        else $this.closest('div.agent_boxes').show();
                    });


                }
            });
        });

    </script>
@endsection
