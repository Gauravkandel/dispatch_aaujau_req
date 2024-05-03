@extends('layouts.vertical', ['title' => 'Tasks'])
@section('css')
    <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/mohithg-switchery/mohithg-switchery.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/multiselect/multiselect.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/selectize/selectize.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/clockpicker/clockpicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">



    <style>
        #adds {
            margin-bottom: 14px;
        }

        .shows {
            display: none;
        }

        .rec {
            margin-bottom: 7px;
        }

        .needsclick {

            margin-left: 27%;
        }

        .padd {
            padding-left: 9% !important;
        }

        .newchnage {
            margin-left: 27% !important;
        }

        .address {
            margin-bottom: 6px
        }

        .tags {
            display: none;
        }

        #typeInputss {
            overflow-y: auto;
            height: 168px;
        }

        .upload {
            margin-bottom: 20px;
            margin-top: 10px;

        }

        .span1 {
            color: #ff0000;
        }

        .check {
            margin-left: 116px !important;
        }

        .newcheck {
            margin-left: -54px;
        }

        .upside {
            margin-top: -10px;
        }

        .newgap {
            margin-top: 11px !important;
        }

        

        .append {
            margin-bottom: 15px;
        }

        .spanbold {
            font-weight: bolder;
        }

        .copyin {
            background-color: rgb(148 148 148 / 11%);
            margin-top: 10px;
            
 
        }
        /* .copyin1 {
            background-color: rgb(148 148 148 / 11%);
           
        } */
        hr.new3 {
         border-top: 1px dashed white;
         margin: 0 0 .5rem 0;
       }
       #spancheck{
           display: none;
       }
       .imagepri{
        min-width: 50px;
           height: 50px;
           width: 50px;
           border-style: groove;
           margin-left: 5px;
           margin-top: 5px;
       }
       .withradio{
       
        
       }
       .showsimage{
        margin-top: 31px;
        margin-left: 140px;
       }
       .showshadding{
        margin-left: 98px;
       }
       .newchnageimage{
           margin-left: 100px;
       }
       .showsimagegall{
        margin-left: 148px;
        margin-top: 21px;

       }
       .allset{
           margin-left: 9px !important;
           padding-top: 10px;
       }

.pac-container, .pac-container .pac-item { z-index: 99999 !important; }
    </style>


@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-box">
                    <h4 class="page-title">Add Route</h4>
                </div>
            </div>
        </div>
        <!-- start page title -->

        <!-- end page title -->
        <form id="task_form" action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                <div class="col-md-12 col-lg-9 col-xl-7">
                    <div class="card-box">
                        @csrf
                        <div class="row d-flex align-items-center" id="dateredio">
                            
                            <div class="col-md-3">
                                <h4 class="header-title mb-3">Customer</h4>
                            </div>
                            <div class="col-md-5 text-right">
                                <div class="login-form">
                                    <ul class="list-inline">
                                        <li class="d-inline-block mr-2">
                                            <input type="radio" class="custom-control-input check" id="tasknow"
                                            name="task_type" value="now" checked>
                                            <label class="custom-control-label" for="tasknow">Now</label>
                                        </li>
                                        <li class="d-inline-block">
                                            <input type="radio" class="custom-control-input check" id="taskschedule"
                                            name="task_type" value="schedule" >
                                            <label class="custom-control-label" for="taskschedule">Schedule</label>
                                        </li>
                                      </ul>
                                    </div>
                            </div>
                            <div class="col-md-4 datenow">
                                <input type="text" id='datetime-datepicker' name="schedule_time"
                                    class="form-control upside" placeholder="Date Time">
                            </div>
                        </div>

                        <span class="span1 searchspan">Please search a customer or add a customer</span>
                        <div class="row searchshow">
                            <div class="col-md-8">
                                <div class="form-group" id="nameInput">

                                    {!! Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'Search Customer', 'id' => 'search']) !!}
                                    <input type="hidden" id='cusid' name="ids" readonly>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" id="AddressInput">
                                    <a href="#" class="add-sub-task-btn">New Customer</a>

                                </div>
                            </div>

                        </div>

                        <div class="row newcus shows">
                            <div class="col-md-3">
                                <div class="form-group" id="">
                                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" id="">
                                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" id="">
                                    {!! Form::text('phone_number', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Phone Number',
                                    ]) !!}
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" id="Inputsearch">
                                    <a href="#" class="add-sub-task-btn">Previous</a>

                                </div>

                            </div>
                        </div>

                        <div class="taskrepet" id="newadd">
                            <div class="copyin1" id="copyin1">
                              <div class="requried allset">
                                <div class="row firstclone1">
                                    
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <select class="form-control selecttype mt-1 taskselect" id="task_type"  name="task_type_id[]" required>
                                                <option value="1">Pickup Task</option>
                                                <option value="2">Drop Off Task</option>
                                                <option value="3">Appointment</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group appoint mt-1">
                                            {!! Form::text('appointment_date[]', null, ['class' => 'form-control
                                            appointment_date', 'placeholder' => 'Duration (In Min)']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>


                                    </div>
                                    <div class="col-md-1 text-center pt-2" >
                                        
                                    <span class="span1 onedelete" id="spancheck"><img style="filter: grayscale(.5);" src="{{asset('assets/images/ic_delete.png')}}"  alt=""></span>


                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="header-title mb-2">Address</h4>
                                    </div>
                                    <div class="col-md-6">
                                        {{-- <h4 class="header-title mb-2">Saved Addresses</h4> --}}
                                    </div>
                                </div>
                                
                                <span class="span1 addspan">Please select a address or create new</span>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group alladdress" id="typeInput">
                                            {!! Form::text('short_name[]', null, ['class' => 'form-control address',
                                            'placeholder' => 'Address Short Name','required' => 'required']) !!}

                                            <input type="text" id="add1-input" name="address[]" class="form-control address cust_add" placeholder="Address">
                                            <div class="input-group-append">
                                                <button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="add1"> <i class="mdi mdi-map-marker-radius"></i></button>
                                            </div>
                                            <input type="hidden" name="latitude[]" id="add1-latitude" value="0" class="cust_latitude" />
                                            <input type="hidden" name="longitude[]" id="add1-longitude" value="0" class="cust_longitude" />
                                            {!! Form::text('post_code[]', null, [
                                            'class' => 'form-control address',
                                            'placeholder' => 'Post Code',
                                            'required' => 'required']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>

                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group withradio" id="typeInputss">
                                            
                                            <div class="oldhide">
                                               
                                                <img class="showsimage" src="{{url('assets/images/ic_location_placeholder.png')}}" alt="">
                                            </div>
                                            

                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-md-12" id="adds">
                                <a href="#" class="add-sub-task-btn waves-effect waves-light">Add Sub
                                    Task</a>
                            </div>
                        </div>

                        <!-- end row -->

                        <!-- container -->
                        <h4 class="header-title mb-2">Meta Data</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" id="make_modelInput">
                                    {!! Form::text('recipient_phone', null, ['class' => 'form-control rec', 'placeholder' =>
                                    'Recipient Phone']) !!}
                                    {!! Form::email('recipient_email', null, ['class' => 'form-control rec', 'placeholder'
                                    => 'Recipient Email']) !!}
                                        {!! Form::textarea('task_description', null, ['class' => 'form-control',
                                        'placeholder' => 'Task_description', 'rows' => 2, 'cols' => 40]) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                   
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                </div>

                            </div>
                            <div class="col-md-6">
                               
                                <div class="form-group" id="colorInput">
                                    <label class="btn btn-info width-lg waves-effect waves-light newchnageimage upload-img-btn">
                                        <span><i class="fas fa-image mr-2"></i>Upload Image</span>
                                        <input id="file" type="file" name="file[]" multiple style="display: none"/>
                                    </label>
                                    <img class="showsimagegall" src="{{url('assets/images/ic_image_placeholder.png')}}" alt="">
                                    <div class="allimages">
                                      <div id="imagePreview" class="privewcheck"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        

                        <h4 class="header-title mb-3">Allocation</h4>
                        <div class="row my-3" id="rediodiv">
                            <div class="col-md-12">
                                <div class="login-form">
                                    <ul class="list-inline">
                                        <li class="d-inline-block mr-2">
                                            <input type="radio" class="custom-control-input check" id="customRadio"
                                            name="allocation_type" value="u" checked>
                                        <label class="custom-control-label" for="customRadio">Unassigned</label>
                                        </li>
                                        <li class="d-inline-block mr-2">
                                            <input type="radio" class="custom-control-input check" id="customRadio22"
                                            name="allocation_type" value="a">
                                        <label class="custom-control-label" for="customRadio22">Auto Allocation</label>
                                        </li>
                                        <li class="d-inline-block">
                                            <input type="radio" class="custom-control-input check" id="customRadio33"
                                            name="allocation_type" value="m">
                                        <label class="custom-control-label" for="customRadio33">Manual</label>
                                        </li>
                                      </ul>
                                    </div>
                            </div>
                            {{-- <div class="col-md-4 padd">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input check" id="customRadio"
                                        name="allocation_type" value="u" checked>
                                    <label class="custom-control-label" for="customRadio">Un-Assigned</label>
                                </div>
                            </div>
                            <div class="col-md-4 padd">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input check" id="customRadio22"
                                        name="allocation_type" value="a">
                                    <label class="custom-control-label" for="customRadio22">Auto Allocation</label>
                                </div>
                            </div>
                            <div class="col-md-4 padd">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input check" id="customRadio33"
                                        name="allocation_type" value="m">
                                    <label class="custom-control-label" for="customRadio33">Manual</label>
                                </div>
                            </div> --}}
                        </div>
                        <span class="span1 tagspan">Please select atlest one tag for {{getAgentNomenclature()}} and {{getAgentNomenclature()}}</span>
                        <div class="row tags">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Team Tag</label>
                                    <select name="team_tag[]" id="selectize-optgroups" multiple placeholder="Select tag...">
                                        <option value="">Select Tag...</option>
                                        @foreach ($teamTag as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>{{getAgentNomenclature()}} Tag</label>
                                    <select name="agent_tag[]" id="selectize-optgroup" multiple placeholder="Select tag...">
                                        <option value="">Select Tag...</option>
                                        @foreach ($agentTag as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row drivers">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label>{{getAgentNomenclature()}}s</label>
                                    <select class="form-control" name="agent" id="driverselect">
                                        @foreach ($agents as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- <div class="col-md-5">

                            </div> --}}
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-block btn-lg btn-blue waves-effect waves-light">Submit</button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </form>
     


    </div>

<div id="show-map-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header border-0">
                <h4 class="modal-title">Select Location</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body px-3 py-0">
                
                <div class="row">
                    <form id="task_form" action="#" method="POST" style="width: 100%">
                        <div class="col-md-12">
                            <div id="googleMap" style="height: 500px; min-width: 500px; width:100%"></div>
                            <input type="hidden" name="lat_input" id="lat_map" value="0" />
                            <input type="hidden" name="lng_input" id="lng_map" value="0" />
                            <input type="hidden" name="for" id="map_for" value="" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-blue waves-effect waves-light selectMapLocation">Ok</button>
                <!--<button type="Cancel" class="btn btn-blue waves-effect waves-light cancelMapLocation">cancel</button>-->
            </div>
        </div>
    </div>
</div>
<div class="row address" id="add0" style="display: none;">
    <input type="text" id="add0-input" name="test" class="autocomplete form-control add0-input" placeholder="Address">
</div>
@endsection


@section('script')
    <!-- google maps api -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB85kLYYOmuAhBUPd7odVmL6gnQsSGWU-4&libraries=places"></script> 
    <!-- Plugins js-->
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script src="{{ asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/multiselect/multiselect.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/libs/devbridge-autocomplete/devbridge-autocomplete.min.js') }}"></script>
 
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Plugins js-->
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <!-- Page js-->
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced2.init.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/libs/clockpicker/clockpicker.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/js/pages/form-pickers.init.js') }}"></script>
    <script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
    <!-- Page js-->


    <script type="text/javascript">
        
    /*$('.openModal').click(function(){
        $('#add-customer-modal').modal({
            //backdrop: 'static',
            keyboard: false
        });
        loadMap(autocompletesWraps);
    });*/

    var autocomplete = {};
    var autocompletesWraps = ['add0'];
    var count = 1; editCount = 0;
    autocompletesWraps.push('add1');
    $(document).ready(function(){
        //autocompletesWraps.push('add1');
        loadMap(autocompletesWraps);
    })

        function loadMap(autocompletesWraps){

        
            $.each(autocompletesWraps, function(index, name) {
                const geocoder = new google.maps.Geocoder;

                //console.log(name+'--');
                if($('#'+name).length == 0) {
                    return;
                }
                //autocomplete[name] = new google.maps.places.Autocomplete(('.form-control')[0], { types: ['geocode'] }); console.log('hello');
                autocomplete[name] = new google.maps.places.Autocomplete(document.getElementById(name+'-input'), { types: ['geocode'] });
                    console.log('hello');
                google.maps.event.addListener(autocomplete[name], 'place_changed', function() {
                    
                    var place = autocomplete[name].getPlace();

                    geocoder.geocode({'placeId': place.place_id}, function (results, status) {
                        
                        if (status === google.maps.GeocoderStatus.OK) {
                            const lat = results[0].geometry.location.lat();
                            const lng = results[0].geometry.location.lng();
                            console.log(latitudes);
                            document.getElementById(name + '-latitude').value = lat;
                            document.getElementById(name + '-longitude').value = lng;
                        }
                    });
                });
            });

        }

        loadMap(autocompletesWraps);

        $(document).on('click', '.showMap', function(){
            var no = $(this).attr('num');
            var lats = document.getElementById(no+'-latitude').value;
            var lngs = document.getElementById(no+'-longitude').value;

            document.getElementById('map_for').value = no;

            if(lats == null || lats == '0'){
                lats = 51.508742;
            }
            if(lngs == null || lngs == '0'){
                lngs = -0.120850;
            }

            var myLatlng = new google.maps.LatLng(lats, lngs);
                var mapProp = {
                    center:myLatlng,
                    zoom:5,
                    mapTypeId:google.maps.MapTypeId.ROADMAP
                  
                };
                var map=new google.maps.Map(document.getElementById("googleMap"), mapProp);
                    var marker = new google.maps.Marker({
                      position: myLatlng,
                      map: map,
                      title: 'Hello World!',
                      draggable:true  
                  });
                document.getElementById('lat_map').value= lats;
                document.getElementById('lng_map').value= lngs ; 
                // marker drag event
                google.maps.event.addListener(marker,'drag',function(event) {
                    document.getElementById('lat_map').value = event.latLng.lat();
                    document.getElementById('lng_map').value = event.latLng.lng();
                });

                //marker drag event end
                google.maps.event.addListener(marker,'dragend',function(event) {
                    var zx =JSON.stringify(event);
                    console.log(zx);


                    document.getElementById('lat_map').value = event.latLng.lat();
                    document.getElementById('lng_map').value = event.latLng.lng();
                    //alert("lat=>"+event.latLng.lat());
                    //alert("long=>"+event.latLng.lng());
                });
                $('#add-customer-modal').addClass('fadeIn');
            $('#show-map-modal').modal({
                //backdrop: 'static',
                keyboard: false
            });

        });

        $(document).on('click', '.selectMapLocation', function () {

            var mapLat = document.getElementById('lat_map').value;
            var mapLlng = document.getElementById('lng_map').value;
            var mapFor = document.getElementById('map_for').value;
            console.log(mapLat+'-'+mapLlng+'-'+mapFor);
            document.getElementById(mapFor + '-latitude').value = mapLat;
            document.getElementById(mapFor + '-longitude').value = mapLlng;


            $('#show-map-modal').modal('hide');
        });
    </script>

    <script>

        
        $(document).ready(function() {
            $(".shows").hide();
            $(".addspan").hide();
            $(".tagspan").hide();
            $(".tagspan2").hide();
            $(".searchspan").hide();
            $(".appoint").hide();
            $(".datenow").hide();
            $("#AddressInput a").click(function() {
                $(".shows").show();
                $(".append").hide();
                $(".searchshow").hide();
                $('input[name=ids').val('');
                $('input[name=search').val('');
                $('.copyin').remove();
            });
            $("#Inputsearch a").click(function() {
                $(".shows").hide();
                $(".append").hide();
                $(".searchshow").show();
                $('.copyin').remove();
            });

            $("#nameInput").keyup(function() {
                $(".shows").hide();
                $(".oldhide").show();
                $(".append").hide();
                $('input[name=ids').val('');
                $('.copyin').remove();
            });

            $("#file").click(function() {
                $('.showsimagegall').hide();
                $('.imagepri').remove();
                
            });
            
            
            $(document).on('click', ".span1", function() {
                
                $(this).closest(".copyin").remove();
            });
            // $('#adds a').click(function() {
            //     var regex = /^(.+?)(\d+)$/i;
            //     var cloneIndex = $(".copyin").length;
            //     var $div = $('div[id^="copyin1"]:first');
            //     console.log($div);
            //     $('#copyin1').clone().appendTo('.taskrepet')
            //       .attr("id", "copyin" +  cloneIndex)
            //       .find("*")
            //       .each(function() {
            //          var id = this.id || "";
            //          var match = id.match(regex) || [];
            //          if (match.length == 3) {
            //             this.id = match[1] + (cloneIndex);
            //         }
            //       })
            //       .on('click', '.onedelete', remove);
            //     cloneIndex++;
            //     // var button = $('.firstclone').clone();
            //     // console.log()
            //     //$('.taskrepet').html($button);
            //     // var firstDivContent = document.getElementById('typeInputss');
            //     // var secondDivContent = document.getElementById('mydiv2');
            //     // secondDivContent.innerHTML = firstDivContent.innerHTML;

            // });
            var a = 0; countZ = 1;
            $('#adds a').click(function() {
                a = a +1;
                countZ = countZ + 1;

                // var direction = this.defaultValue < this.value
                // this.defaultValue = this.value;
                // if (direction)
                // {
                        var newids = null;
                        var $div = $('div[class^="copyin"]:last');
                        var newcheck = $div.find('.redio');
                        $.each(newcheck, function(index, elem){
                            var jElem = $(elem); // jQuery element
                            var name = jElem.prop('checked');
                            var id = jElem.prop('id');
                            if(name == true){
                              newids = id;
                            }
                            
                            
                            // remove the number
                            //name = name.replace(/\d+/g, '');
                            //name += a;
                            //jElem.prop('name', name);
                            //count0++;
                        });

                        var num = parseInt( $div.prop("id").match(/\d+/g), 10 ) +1;
                        var $clone = $div.clone().prop('class', 'copyin')
                        $clone.insertAfter('[class^="copyin"]:last');
                        // get all the inputs inside the clone
                        var inputs = $clone.find('.redio');
                        console.log(inputs);
                        // for each input change its name/id appending the num value
                        var count0 = 1;
                        $.each(inputs, function(index, elem){
                            var jElem = $(elem); // jQuery element
                            var name = jElem.prop('name');
                            // remove the number
                            name = name.replace(/\d+/g, '');
                            name += a;
                            jElem.prop('name', name);
                            count0++;
                        });
                       
                        var inputid = $clone.find('.redio');
                        var rand =  Math.random().toString(36).substring(7);
                        var count1 = 1;
                        $.each(inputid, function(index, elem){
                               
                            var jElem = $(elem); // jQuery element
                            var name = jElem.prop('id');
                            
                            // remove the number
                            name = rand;
                            
                            name += count1;
                            console.log(name);
                            jElem.prop('id', name);
                            jElem.prop('checked', false);
                            count1++;
                        });
                        var count2 = 1;
                        var labels = $clone.find('label');
                        $.each(labels, function(index, elem){
                               
                            var jElem = $(elem); // jQuery element
                            var name = jElem.prop('for');
                            
                            // remove the number
                            name = rand;
                            
                            name += count2;
                            console.log(name);
                            jElem.prop('for', name);
                            count2++;
                        });

                        $clone.find('.cust_add').prop('id', 'add'+countZ+'-input');
                        $clone.find('.showMap').prop('id', 'add'+countZ);
                        $clone.find('.cust_latitude').prop('id', 'add'+countZ+'-latitude');
                        $clone.find('.cust_longitude').prop('id', 'add'+countZ+'-longitude');



                        var spancheck = $clone.find('.onedelete');
                        $.each(spancheck, function(index, elem){
                               
                            var jElem = $(elem); // jQuery element
                            var name = jElem.prop('id');
                            name = name.replace(/\d+/g, '');
                            // remove the number
                            name = 'newspan';
                            jElem.prop('id', name);
                        });

                        var address1 = $clone.find('.address');
                        $.each(address1, function(index, elem){
                               
                            var jElem = $(elem); // jQuery element
                            jElem.prop('required', true);
                        });


                        // $(".taskrepet").fadeOut();
                        // $(".taskrepet").fadeIn();
                // }
                // else $('[id^="newadd"]:last').remove();
                $('input[id='+newids+']').prop("checked",true);

            autocompletesWraps.indexOf('add'+countZ) === -1 ? autocompletesWraps.push('add'+countZ) : console.log("This item already exists");
        
        console.log(autocompletesWraps);
            loadMap(autocompletesWraps);
            });

            //$("#myselect").val();
            $(document).on('change', ".selecttype", function() {
            
                
                if (this.value == 3){
                   $span = $(this).closest(".firstclone1").find(".appoint").show();
                   console.log($span); 
                }   
                else{
                    $(this).closest(".firstclone1").find(".appoint").hide();
                
                }
                
            });

            $(".callradio input").click(function() { 

                if ($(this).is(":checked")) { 
                  $span = $(this).closest(".requried").find(".alladdress");
                  console.log($span);
                  $(this).parent().css("border", "2px solid black"); 
                }
            });
            $(document).on("click", "input[type='radio']", function () {
            // var element = $(this);
            // alert(element.closest("div").find("img").attr("src"));
            $span = $(this).closest(".requried").find(".address").removeAttr("required");
            // $('#edit-submitted-first-name').removeAttr('required');
            });

            $(".tags").hide();
            $(".drivers").hide();
            $("input[type='radio'].check").click(function() {
                var radioValue = $("#rediodiv input[type='radio']:checked").val();
                if (radioValue == 'a') {
                    $(".tags").show();
                    $(".drivers").hide();
                }
                if (radioValue == 'u') {
                    $(".tags").hide();
                    $(".drivers").hide();
                }
                if (radioValue == 'm') {
                    $(".drivers").show();
                    $(".tags").hide();
                }
            });

            $("input[type='radio'].check").click(function() {
                var dateredio = $("#dateredio input[type='radio']:checked").val();
                if (dateredio == 'schedule') {
                    $(".datenow").show();
                }else{
                    $(".datenow").hide();
                }
                
            });

            var CSRF_TOKEN = $("input[name=_token]").val();


            $("#search").autocomplete({
                source: function(request, response) {
                    // Fetch data
                    $.ajax({
                        url: "{{ route('search') }}",
                        type: 'post',
                        dataType: "json",
                        data: {
                            _token: CSRF_TOKEN,
                            search: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    // Set selection
                    $('#search').val(ui.item.label); // display the selected text
                    $('#cusid').val(ui.item.value); // save selected id to input
                    add_event(ui.item.value);
                    $(".oldhide").hide();
                    return false;
                }
            });

            function add_event(ids) {

                $.ajax({
                    url: "{{ route('search') }}",
                    type: 'post',
                    dataType: "json",
                    data: {
                        _token: CSRF_TOKEN,
                        id: ids
                    },
                    success: function(data) {
                        var array = data;
                        
                        jQuery.each(array, function(i, val) {
                            $(".withradio").append(
                                '<div class="append"><div class="custom-control custom-radio count"><input type="radio" id="' +
                                val.id + '" name="old_address_id" value="' + val
                                .id +
                                '" class="custom-control-input redio callradio"><label class="custom-control-label" for="' +
                                val.id + '"><span class="spanbold">' + val.short_name +
                                '</span>-' + val.address +
                                '</label></div></div>');
                        });

                    }
                });
            }

            $("#task_form").bind("submit", function() {
                $(".addspan").hide();
                $(".tagspan").hide();
                $(".tagspan2").hide();
                $(".searchspan").hide();

                var cus_id = $("input[name=ids]").val();
                var name = $("input[name=name]").val();
                var email = $("input[name=email]").val();
                var phone_no = $("input[name=phone_number]").val();

                if (cus_id == '') {
                    if (name != '' && email != '' && phone_no != '') {

                    } else {
                        $(".searchspan").show();
                        return false;
                    }
                }

                var selectedVal = "";
                var selected = $("#typeInputss input[type='radio']:checked");
                selectedVal = selected.val();
                console.log(selectedVal);
                if (typeof(selectedVal) == "undefined") {
                    var short_name = $("input[name=short_name]").val();
                    var address = $("input[name=address]").val();
                    var post_code = $("input[name=post_code]").val();
                    if (short_name != '' && address != '' && post_code != '') {

                    } else {
                        $(".addspan").show();
                        return false;
                    }
                }

                var autoval = "";
                var auto = $("#rediodiv input[type='radio']:checked");
                autoval = auto.val();
                if (autoval == 'auto') {
                    var value = $("#selectize-optgroups option:selected").text();
                    var value2 = $("#selectize-optgroup option:selected").text();
                    if (value == '') {
                        $(".tagspan").show();
                        return false;
                    }
                    if (value2 == '') {
                        $(".tagspan").show();
                        return false;
                    }
                }



            });

            var inputLocalFont = document.getElementById("file");
             inputLocalFont.addEventListener("change",previewImages,false);

             function previewImages(){
              var fileList = this.files;
              if(fileList.length == 0){
                $('.showsimagegall').show();
              }
    
              var anyWindow = window.URL || window.webkitURL;

              for(var i = 0; i < fileList.length; i++){
               var objectUrl = anyWindow.createObjectURL(fileList[i]);
               $('#imagePreview').append('<img src="' + objectUrl + '" class="imagepri" />');
               window.URL.revokeObjectURL(fileList[i]);
               }
    
    
            }


        });

    </script>

    
@endsection
