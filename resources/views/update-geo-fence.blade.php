@extends('layouts.vertical', ['title' => 'Geo Fence'])

@section('css')
    <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/mohithg-switchery/mohithg-switchery.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/multiselect/multiselect.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/selectize/selectize.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet"
        type="text/css" />


    <!-- for File Upload -->

    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        #map-canvas {
            height: 100%;
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
            border: solid thin;
            padding-top: 10px;
            height: 240px;
            width: 103%;
            overflow-y: auto
        }

        .teamshow {
            margin-left: 58px;

        }

        .display {
            height: 35px;
            width: 67px;
        }

        .boxes {
            margin-bottom: 10px;
        }

        .new {
            vertical-align: initial !important;
            display: revert !important;
        }

        .agentcheck {}
        .search{
            background-color: #02f2cc;
        }
        #new_show {
            display: none;
        }

    </style>
@endsection
@php
    $imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-6">
                <div class="page-title-box">
                    <h4 class="page-title">Edit Geofence</h4>
                </div>
            </div>
            <div class="col-6">
                <div class="page-title-box text-right">
                    <a href="{{ route('geo.fence.list') }}">
                    <button type="button" class="btn btn-blue" title="Back To List" data-keyboard="false"><span><i class="mdi mdi-chevron-double-left mr-1"></i> Back</span></button>
                    </a>
                </div>
            </div>
        </div>

        <form id="" method="post" action="{{ route('geo-fence.update', $geo->id) }}">
            @method('PUT')
            @csrf
            <input type="hidden" name="latlongs" value="" id="latlongs" />
            <input type="hidden" name="zoom_level" value="13" id="zoom_level" />
            <div class="row">
                <div class="col-lg-5">
                    <div class="card-box mb-0">
                        <div class="top_items">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label for="name">Name</label>
                                        <input type="text"  id="name" value="{{ old('name', $geo->name ?? '') }}"
                                            placeholder="ABC Deliveries" class="form-control" name="name" @if(Auth::user()->is_superadmin == 0) readonly @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label for="Description">Description (Optional)</label>
                                        <textarea class="form-control" id="Description"
                                        @if(Auth::user()->is_superadmin == 1) name="description" @else  readonly @endif >{{ old('description', $geo->description ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label>Team</label> <br />
                                        <select id="selectize-select" name="team_id">
                                            @if(count($teams) == 1)
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                                            @endforeach
                                            @else
                                            <option value="0">All</option>
                                            @foreach ($teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                                            @endforeach
                                            @endif
                                            
                                        </select>

                                    </div>
                                    <div class="form-group mb-0 mt-2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox select_all" id="old_show">
                                                    <input type="checkbox" class="custom-control-input all" id="checkmeout0">
                                                    <label class="custom-control-label select_all" for="checkmeout0">Select All
                                                        {{ getAgentNomenclature() }}</label>
                                                </div>
                                                <div class="custom-control custom-checkbox show_alls" id="new_show">
                                                    <input type="checkbox" class="custom-control-input" id="show_all">
                                                    <label class="custom-control-label" for="show_all">Show All
                                                        {{ getAgentNomenclature() }}</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="search" placeholder="Search"
                                                    class="form-control newsearch" id="search">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="geo_middle" id="scroll-bar">
                            <div class="row mb-2 cornar">

                                @foreach ($agents as $key => $agent)
                                    @php
                                    $val = '';
                                    foreach ($geo->agents as $item) {
                                    if($item->id == $agent->id){
                                    $val = 'checked';
                                    }

                                    }
                                    @endphp

                                    <div
                                        class="col-xl-12 agent_boxes team_{{ $agent->team_id ?? 0 }} agent_{{ $agent->id }} {{ $val == 'checked' ? 'selected' : '' }}">
                                        <div class="boxes card-box mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <input type="checkbox"
                                                            class="custom-control-input agent_checkbox team_checkbox_{{ $agent->team_id ?? 0 }}"
                                                            id="{{ $agent->id }}" name="agents[]" value="{{ $agent->id }}"
                                                            {{ $val == 'checked' ? 'checked' : '' }}>
                                                        <label class="custom-control-label new" for="{{ $agent->id }}"></label>
                                                        <img class="imageagent"
                                                            src="{{$imgproxyurl.Storage::disk('s3')->url($agent->profile_picture)}}"
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
                                {{--<div class="col-md-6 mb-2 mb-md-0">
                                    <button type="button"
                                        class="btn btn-block btn-outline-primary waves-effect waves-light mb-0">Cancel</button>
                                </div>--}}
                                <div class="col-md-6">
                                    <button type="submit"
                                        class="btn btn-block btn-primary waves-effect waves-light">Save</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card-box mb-0 map-outer">
                        <!-- <div id="gmaps-basic" class="gmaps"></div> -->
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
    <script src="https://maps.google.com/maps/api/js?key={{$key}}&v=3.exp&libraries=drawing">
    </script>

    <!-- Plugins js-->
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script src="{{ asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/multiselect/multiselect.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/libs/devbridge-autocomplete/devbridge-autocomplete.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/libs/jquery-mockjax/jquery-mockjax.min.js') }}">
    </script> --}}

    <!-- Plugins js-->
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <!-- Page js-->
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>

    <script>
        // function gm_authFailure() {
                
        //         $('.excetion_keys').append('<span><i class="mdi mdi-block-helper mr-2"></i> <strong>Google Map</strong> key is not valid</span><br/>');
        //         $('.displaySettingsError').show();
        // };
        var map; // Global declaration of the map
        var is_superadmin = "{{Auth::user()->is_superadmin}}";
        if(is_superadmin == 1)
        map_editable = true;
        else
        map_editable = false;

        function initialize() {
            var zoomLevel = '{{ $geo->zoom_level }}';
            var coordinate = '{{ $geo->geo_array }}';
            coordinate = coordinate.split('(');
            coordinate = coordinate.join('[');
            coordinate = coordinate.split(')');
            coordinate = coordinate.join(']');
            coordinate = "[" + coordinate;
            coordinate = coordinate + "]";
            coordinate = JSON.parse(coordinate);

            var triangleCoords = [];
            const lat1 = coordinate[0][0];
            const long1 = coordinate[0][1];

            var max_x = lat1;
            var min_x = lat1;
            var max_y = long1;
            var min_y = long1;

            $.each(coordinate, function(key, value) {

                if (value[0] > max_x) {
                    max_x = value[0];
                }
                if (value[0] < min_x) {
                    min_x = value[0];
                }
                if (value[1] > max_y) {
                    max_y = value[1];
                }
                if (value[1] < min_y) {
                    min_y = value[1];
                }

                triangleCoords.push(new google.maps.LatLng(value[0], value[1]));
            });

            var myLatlng = new google.maps.LatLng((min_x + ((max_x - min_x) / 2)), (min_y + ((max_y - min_y) / 2)));
            var myOptions = {
                zoom: parseInt(zoomLevel),
                center: myLatlng,
                styles: themeType,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
            myPolygon = new google.maps.Polygon({
                paths: triangleCoords,
                draggable: map_editable, // turn off if it gets annoying
                editable: map_editable,
                strokeColor: '#bb3733',
                //strokeOpacity: 0.8,
                //strokeWeight: 2,
                fillColor: '#bb3733',
                //fillOpacity: 0.35
            });
            myPolygon.setMap(map);

            google.maps.event.addListener(myPolygon, "mouseup", function(event) {
                $('#latlongs').val(myPolygon.getPath().getArray());
            });

        }
        google.maps.event.addDomListener(window, 'load', initialize);

        

        // onteam change change the selected agents in the list //

        $(function() {
            $('#checkmeout0').change(function() {
                if (this.checked) {
                    $('.agent-selection select option').each(function() {
                        $(this).attr('selected', true);
                    });
                } else {
                    $('.agent-selection select option').each(function() {
                        $(this).attr('selected', false);
                    });
                }
                $('#agents').trigger('change');
            });
        });

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
                $('#show_all').prop('checked',false);
            } 
        });

        // $('select').on('change', function(e) {
        //     var select = $("#selectize-select option:selected").val();
        // });

        $('select').on('change', function(e) {
            var select = $("#selectize-select option:selected").val();
            $('#checkmeout0').prop('checked', false);
            $('#show_all').prop('checked',false);
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
                            $this.closest('div.agent_boxes').removeClass('selected').hide().find('[type=checkbox]').prop('checked', false);
                        else $this.closest('div.agent_boxes').show();
                    });


                }
            });
        });

    </script>
@endsection
