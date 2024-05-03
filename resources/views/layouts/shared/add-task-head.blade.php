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

        }

        #typeInputss {
            overflow-y: auto;
            overflow-x: hidden;
            height: 200px;
            /* display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column; */
        }

        .upload {
            margin-bottom: 20px;
            margin-top: 10px;

        }

        .span1 {
            color: #ff0000;
        }

        .check {

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
       }
       .showshadding{
        margin-left: 98px;
       }
       .newchnageimage{
       }
       .showsimagegall{
        margin-top: 20px;
       }
       .imagepri_wrap {
            position: relative;
        }
        button.close.imagepri_close {
            position: absolute;
            top: -7px;
            right: 1px;
            background-color: red;
            border-radius: 50%;
            padding: 0px 3px;
            font-size: 14px;
            color: white;
        }
       .allset{
           margin-left: 9px !important;
           margin-right: 9px !important;
           padding-top: 10px;
       }
       .inactiveLink {
            pointer-events: none;
            cursor: default;
        }
       .hidealloction{
           display: none;
       }
       .ui-menu.ui-autocomplete { z-index: 9000 !important; }

    .pac-container, .pac-container .pac-item { z-index: 9999 !important; }

    .alTaskType{background-color: #F4F3FD; border: 1px solid transparent;border-radius: 7px;}
    .alTaskType.active{background-color: #FEE9DB;border: 1px solid #FA6400;}
    .alTaskType input,
    .alTaskType textarea,
    .alTaskType select{background-color: transparent;padding: 6px 10px;border:1px solid #d1d1d1;border-radius: 5px;}

    .border-right{border-right: 1px dashed #d1d1d1 !important;}
    .alCol-12{-ms-flex: 0 0 100%;flex: 0 0 100%;max-width: 100%;}
    .alCol-6{-ms-flex: 0 0 50%;flex: 0 0 50%;max-width: 50%;}
    .alCol-12.mainaddress.col-8 .addressDetails .form-group.col-6{-ms-flex: 0 0 50%;flex: 0 0 50%;max-width: 50%;}
    .mainaddress.col-8 .addressDetails .form-group.col-6{-ms-flex: 0 0 100%;flex: 0 0 100%;max-width: 100%;}
    .addressDetails{-ms-flex: 0 0 50%;flex: 0 0 50%;max-width: 50%;}
    .alCol-12.mainaddress.col-8 .alContactOther .col-6{-ms-flex: 0 0 50%;flex: 0 0 50%;max-width: 50%;}
    .mainaddress.col-8 .alContactOther .col-6{-ms-flex: 0 0 100%;flex: 0 0 100%;max-width: 100%;}
    .mainaddress.col-8 .alContactOther .alRightBorder,
    .alCol-12.mainaddress.col-8 .alContactOther{border-right: 1px dashed transparent;}
    .alCol-12.mainaddress.col-8 .alContactOther .alRightBorder,
    .mainaddress.col-8 .alContactOther{border-right: 1px dashed #d1d1d1;}

    ::-webkit-scrollbar {
    width: 5px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
    background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
    background: #888;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
    background: #555;
    }
</style>
<div id="task-modal-header" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;font-size: 14px;">
    <div class="modal-dialog modal-dialog-top modal-xl">
        <div class="modal-content" style="">
            <div class="modal-header align-items-center border-0 mb-md-0">
                <h4 class="page-title m-1">{{__("Add Route")}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="taskFormHeader" method="post" enctype="multipart/form-data" action="{{ route('tasks.store') }}">
                @csrf
                <div class="modal-body p-0" id="addCardBox">

                </div>
                <span class="show_all_error invalid-feedback"></span>
                <div class="modal-footer justify-content-center p-0">
                     <a href="javascript: void(0);" class="btn btn-blue waves-effect waves-light submitTaskHeader"><span class="spinner-border spinner-border-sm submitTaskHeaderLoader" style="display:none;" role="status" aria-hidden="true"></span> <span id="submitTaskHeaderText">{{__("Submit")}}</span></a>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="show-map-Header" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header border-0">
                <h4 class="modal-title">{{__("Select Location")}}</h4>
                <button type="button" class="close remove-modal-open" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body px-3 py-0">

                <div class="row">
                    <form id="task_form_header" action="#" method="POST" style="width: 100%">
                        <div class="col-md-12">
                            <div id="googleMapHeader" style="height: 500px; min-width: 500px; width:100%"></div>
                            <input type="hidden" name="lat_input" id="lat_map_header" value="0" />
                            <input type="hidden" name="lng_input" id="lng_map_header" value="0" />
                            <input type="hidden" name="address_input" id="addredd_map_header" value="" />
                            <input type="hidden" name="for" id="map_for_header" value="" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-blue waves-effect waves-light selectMapOnHeader remove-modal-open">{{__("Ok")}}</button>
                <!--<button type="Cancel" class="btn btn-blue waves-effect waves-light cancelMapLocation">cancel</button>-->
            </div>
        </div>
    </div>
</div>
            @php
                $style = "";
                if(session('preferences.twilio_status') != 'invalid_key'){
                        $style = "display:none;";
                }
            @endphp
            {{-- <div class="row displaySettingsError" style="{{$style}}">
                <div class="col-12">
                    <div class="alert alert-danger excetion_keys" role="alert">
                        @if(session('preferences.twilio_status') == 'invalid_key')
                        <span><i class="mdi mdi-block-helper mr-2"></i> <strong>Twilio</strong> key is not valid</span> <br/>
                        @endif
                    </div>
                </div>


            </div> --}}

<div class="row address" id="addHeader0" style="display: none;">
    <input type="text" id="addHeader0-input" name="address" class="autocomplete form-control addHeader0-input" placeholder={{__("Address")}}>
    <input type="hidden" name="latitude[]" id="addHeader0-latitude" value="0" class="cust_latitude" />
    <input type="hidden" name="longitude[]" id="addHeader0-longitude" value="0" class="cust_longitude" />
</div>
@php
    $key    = session('preferences.map_key_1') != null ? session('preferences.map_key_1'):'kdsjhfkjsdhfsf';
    $theme  = \App\Model\ClientPreference::where(['id' => 1])->first('theme');


@endphp
<link href="{{ asset('assets/libs/selectize/selectize.min.css') }}" rel="stylesheet" type="text/css" />

<script src="{{ asset('assets/js/jquery-ui.min.js') }}" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
{{-- <script src='https://cdn.rawgit.com/pguso/jquery-plugin-circliful/master/js/jquery.circliful.min.js'></script> --}}
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB85kLYYOmuAhBUPd7odVmL6gnQsSGWU-4&libraries=places"></script>  --}}
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
@if(\Route::current()->getName() == "tasks.show")
<script src="https://maps.googleapis.com/maps/api/js?key={{Auth::user()->getPreference->map_key_1??''}}&libraries=places,drawing,geometry,visualization&v=weekly"></script>
@else
<script defer src="https://maps.googleapis.com/maps/api/js?key={{Auth::user()->getPreference->map_key_1??''}}&libraries=places,geometry,drawing,visualization&v=weekly"></script>
@endif
<script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
<script src="{{ asset('assets/libs/multiselect/multiselect.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/clockpicker/clockpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<!-- Page js-->

<script>
    var theme      = {!!json_encode($theme)!!};

    var maoArray = {};
    var autoWrap = ['addHeader0'];
    var count = 1; editCount = 0; var a = 0; countZ = 1;

    @if(Auth::user())
    $(document).on("change",".admin_panel_theme", function(){
        if($(this).prop('checked')){
            var theme = 'dark';
        }else{
            var theme = 'light';
        }
        $.ajax({
            url: "{{route('preference', Auth::user()->code)}}",
            type: "POST",
            data: {
                theme: theme,
                "_token": "{{ csrf_token() }}",
            },
            success: function(response) {
                location.reload();
            },
        });

    });
    @endif
    $(document).ready(function(){
      loadMapHeader(autoWrap);

    });
    function gm_authFailure() {
                console.log('ok');
                $('.excetion_keys').append('<span><i class="mdi mdi-block-helper mr-2"></i> <strong>Google Map</strong> key is not valid</span><br/>');
                $('.displaySettingsError').show();
    };
    function runPicker(){
        $('.datetime-datepicker').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
        });

        $('.selectpicker').selectpicker();
    }

    var latitudes = [];
    var longitude = [];
    $(".addTaskModalHeader").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        autoWrap.indexOf('addHeader1') === -1 ? autoWrap.push('addHeader1') : '' ;
        e.preventDefault();

        $.ajax({
            type: "get",
            url: "<?php echo url('tasks'); ?>" + '/create',
            data: '',
            dataType: 'json',
            success: function (data) {
                $('.submitTaskHeaderLoader').css('display', 'none');
                $('#submitTaskHeaderText').text('Submit');
                $('.submitTaskHeader').removeClass("inactiveLink");

                $('#task-modal-header #addCardBox').html(data.html);

                $('#task-modal-header').find('.selectizeInput').selectize();

                
                $('.dropify').dropify();
                $(".newcustomer").hide();
                $(".searchshow").show();
                $(".append").show();
                $('.copyin').remove();

                $(".addspan").hide();
                $(".tagspan").hide();
                $(".tagspan2").hide();
                $(".searchspan").hide();
                $(".datenow").hide();

                $(".pickup-barcode-error").hide();
                $(".drop-barcode-error").hide();
                $(".appointment-barcode-error").hide();

                $('.appoint').hide();

                loadMapHeader(autoWrap);
                searchRes();
                $('#task-modal-header').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                phoneInput();
                runPicker();

                $('#task-modal-header .edit-icon-float-right').on('click', function() {
                    $('#task-modal-header .meta_data_task_div').toggle();
                    if($(this).find('i').hasClass('mdi mdi-chevron-down')){
                        $(this).find('i').removeClass('mdi mdi-chevron-down');
                        $(this).find('i').addClass('mdi mdi-chevron-up');
                    }else{
                        $(this).find('i').removeClass('mdi mdi-chevron-up');
                        $(this).find('i').addClass('mdi mdi-chevron-down');
                    }
                });

                
            },
            error: function (data) {
            }
        });
    });

    function phoneInput() {
        //initialize intlTelInput
        let phone_number_intltel = window.intlTelInput(document.querySelector("#taskFormHeader .phone_number"),{
            separateDialCode: true,
            preferredCountries:["{{getCountryCode()}}"],
            initialCountry:"{{getCountryCode()}}",
            hiddenInput: "full_number",
            utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });

        document.querySelector("#taskFormHeader .phone_number").addEventListener("countrychange", function() {
            $("#taskFormHeader #dialCode").val(phone_number_intltel.getSelectedCountryData().dialCode);
        });
    }

    var CSRF_TOKEN = $("input[name=_token]").val();

    function searchRes(){

        $("#task-modal-header #searchCust").autocomplete({
            source: function(request, response) {
                // Fetch data
                $.ajax({
                    url: "{{route('search')}}",
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
                $('#task-modal-header #searchCust').val(ui.item.label); // display the selected text
                $('#task-modal-header #cusid').val(ui.item.value); // save selected id to input
                add_event(ui.item.value);
                $(".oldhide").hide();
                return false;
            }
        });

        $("#task-modal-header #searchDriver").autocomplete({
            source: function(request, response) {
                // Fetch data
                $.ajax({
                    url: "{{ route('agent.search') }}",
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
                $('#task-modal-header #searchDriver').val(ui.item.label); // display the selected text
                $('#task-modal-header #agentid').val(ui.item.value); // save selected id to input
                return false;
            }
        });
    }

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
                    var customerdata = data.customer;
                    var array = data.location;

                    var countrycode = customerdata.countrycode;
                    $("#taskFormHeader").find("input[name='phone_number']").val(customerdata.phone_number);
                    $("#taskFormHeader #dialCode").val(customerdata.dial_code);
                    
                    //getting instance of intlTelInput
                    var input = document.querySelector("#taskFormHeader .phone_number");
                    var iti = window.intlTelInputGlobals.getInstance(input);
                    iti.setCountry(countrycode);

                    $("#taskFormHeader").find("input[name='email']").val(customerdata.email);

                    $('.withradio .append').remove();
                    jQuery.each(array, function(i, val) {
                    var countz = '';
                    var rand =  Math.random().toString(36).substring(7);
                    $(".withradio").each(function(){
                        var count = parseInt(countz);if(isNaN(count)){count = 0;}
                        $(this).append(
                          '<div class="append"><div class="custom-control custom-radio count"><input type="radio" id="' + (rand + count) + '" name="old_address_id' + countz + '" value="' + val.id + '" class="custom-control-input redio old-select-address callradio" data-srtadd="'+ val.short_name +'" data-flat_no="'+ val.flat_no +'"  data-adr="'+ val.address +'" data-lat="'+ val.latitude +'" data-long="'+ val.longitude +'" data-pstcd="'+ val.post_code +'" data-emil="'+ val.email +'" data-ph="'+ val.phone_number +'"><label class="custom-control-label" for="' + (rand + count) + '"><span class="spanbold">' + val.short_name +
                          '</span>-' + val.address +
                          '</label></div></div>');
                        countz = count + 1;
                    });
                  });

              }
          });
      }
      var post_count = 2;
    $(document).on('click', '.subTaskHeader', function(){
        var cur = countZ;
        countZ = countZ + 1;
        var $clone = $('.cloningDiv').clone();
        $clone.removeClass('cloningDiv');
        $clone.removeClass('copyin1');
        $clone.addClass('copyin');
        $clone.addClass('repeated-block check-validation');

        $clone.find('.cust_add_div').prop('id', 'addHeader' + countZ);
        $clone.find('.cust_add').prop('id', 'addHeader' + countZ +'-input');
        $clone.find('.cust_btn').prop('id', 'addHeader' + countZ);
        $clone.find('.cust_btn').prop('num', 'addHeader' + countZ);

        $clone.find('.cust_latitude').prop('id', 'addHeader' + countZ +'-latitude');
        $clone.find('.cust_longitude').prop('id', 'addHeader' + countZ +'-longitude');

        var inputid = $clone.find('.redio');
        var rand =  Math.random().toString(36).substring(7);
        var count1 = 1;
        $.each(inputid, function(index, elem){

            var jElem = $(elem);
            jElem.prop('id', rand+count1);
            jElem.prop('name', 'old_address_id' + cur);
            jElem.prop('checked', false);
            count1++;
        });
        var count2 = 1;
        var labels = $clone.find('label');
        $.each(labels, function(index, elem){

            var jElem = $(elem); // jQuery element
            jElem.prop('for', rand+count2);
            count2++;
        });
        var spancheck = $clone.find('.delbtnhead');
          $.each(spancheck, function(index, elem){

              var jElem = $(elem); // jQuery element
              var name = jElem.prop('id');
              name = name.replace(/\d+/g, '');
              // remove the number
              name = 'newspan';
              jElem.prop('id', name);
              jElem.prop('class', 'span1 onedelete');
          });

          var address1 = $clone.find('.address');
          $.each(address1, function(index, elem){

              var jElem = $(elem); // jQuery element
          });

          var flatNo1 = $clone.find('.flat_no');
          $.each(flatNo1, function(index, elem){
            var jElem = $(elem)
            var name = jElem.prop('id');
            name = name.replace(/\d+/g, '');
            name = 'addHeader'+post_count+'-flat_no';
            jElem.prop('id', name);
          });

          var alcoholicItem = $clone.find('.alcoholic_item');
          $.each(alcoholicItem, function(index, elem){
            var jElem = $(elem)
            var name = jElem.prop('id');
            name = name.replace(/\d+/g, '');
            name = 'addHeader'+post_count+'-alcoholic_item';
            jElem.prop('id', name);

            var alcoholicItemLabel = $clone.find('.alcoholic_item_label');
            $.each(alcoholicItemLabel, function(index, elem){
                var jElem = $(elem);
                var labelName = jElem.prop('for');
                labelName = labelName.replace(/\d+/g, '');
                labelName = 'addHeader'+post_count+'-alcoholic_item';
                jElem.prop('for', labelName);
            });
          });

          var postcode1 = $clone.find('.postcode');
          $.each(postcode1, function(index, elem){
            var jElem = $(elem);
            var name = jElem.prop('id');
            name = name.replace(/\d+/g, '');
            name = 'addHeader'+post_count+'-postcode';
            jElem.prop('id', name);
              post_count++;
              console.log(post_count);
          });

        $clone.find('.appoint').hide();
        $(document).find('#addSubFields').before($clone);
        $clone.find('input[type="text"]').val('');
        autoWrap.indexOf('addHeader'+countZ) === -1 ? autoWrap.push('addHeader'+countZ) : console.log("exists");
          loadMapHeader(autoWrap);
          $clone.find('input').click();
          $clone.find('input[type="radio"]').prop('checked', false);
          $clone.find('input[type="text"]').val('');
    });

    function loadMapHeader(autoWrap){
        $.each(autoWrap, function(index, name) {
            const geocoder = new google.maps.Geocoder;

            if($('#'+name).length == 0) {
                return;
            }
            maoArray[name] = new google.maps.places.Autocomplete(document.getElementById(name+'-input'), { types: ['geocode'] });

            google.maps.event.addListener(maoArray[name], 'place_changed', function() {

                var place = maoArray[name].getPlace();

                geocoder.geocode({'placeId': place.place_id}, function (results, status) {

                    if (status === google.maps.GeocoderStatus.OK) {


                        const lat = results[0].geometry.location.lat();
                        const lng = results[0].geometry.location.lng();
                        const address = results[0].formatted_address;
                        document.getElementById(name + '-latitude').value = lat;
                        document.getElementById(name + '-longitude').value = lng;
                    }
                });
            });
        });
    }

    $(document).on('click', ".onedelete", function() {

        $(this).closest(".copyin").remove();
    });

    $(document).on("click", "input[type='radio'].checkcustomer", function() {

        var customerredio = $("#customerradio input[type='radio']:checked").val();
        if(customerredio == 'existingcustomer') {
            $(".newcustomer").hide();
            $(".searchshow").show();
            $(".append").show();
            
            autoWrap = ['addHeader0', 'addHeader1'];
            countZ = 1;
        }else{
            $(".newcustomer").show();
            $(".append").hide();
            $(".searchshow").hide();
            $('input[name=ids]').val('');
            $('#taskFormHeader .phone_number').val('');
            $('input[name=search]').val('');
            $('#taskFormHeader .email').val('');
            autoWrap = ['addHeader0', 'addHeader1'];
            countZ = 1;
        }
    });


    $(document).on("click", "input[type='radio'].checkschedule", function() {
        var dateredio = $("#dateredio input[type='radio']:checked").val();
        if (dateredio == 'schedule') {
        $(".datenow").show();
        }else{
            $(".datenow").hide();
        }
    });


      $(document).on('click', "#taskschedule", function() {

            var dateredio = $("#dateredio input[type='radio']:checked").val();
                if (dateredio == 'schedule') {
                    $(".opendatepicker").focus();
                }
        });

    $(document).on('click', '#clear-address', function(){
        $(this).closest('.check-validation').find("input:checked").prop('checked', false);
        $(this).closest('.check-validation').find("input[name='short_name[]']").val('');
        $(this).closest('.check-validation').find("input[name='address_email[]']").val('');
        $(this).closest('.check-validation').find("input[name='address[]']").val('');
        $(this).closest('.check-validation').find("input[name='address_phone_number[]']").val('');
        $(this).closest('.check-validation').find("input[name='post_code[]']").val('');
        $(this).closest('.check-validation').find("input[name='latitude[]']").val('');
        $(this).closest('.check-validation').find("input[name='longitude[]']").val('');
    });

    $(document).on('click', '.old-select-address', function(){
        var shortName   = $(this).data("srtadd");
        var address     = $(this).data("adr");
        var latitude    = $(this).data("lat");
        var longitude   = $(this).data("long");
        var postCode    = $(this).data("pstcd");
        var email       = $(this).data("emil");
        var phoneNumber = $(this).data("ph");
        var flat_no = $(this).data("flat_no");

        $(this).closest('.check-validation').find("input[name='short_name[]']").val(shortName);
        $(this).closest('.check-validation').find("input[name='address_email[]']").val(email);
        $(this).closest('.check-validation').find("input[name='address[]']").val(address);
        $(this).closest('.check-validation').find("input[name='address_phone_number[]']").val(phoneNumber);
        $(this).closest('.check-validation').find("input[name='post_code[]']").val(postCode);
        $(this).closest('.check-validation').find("input[name='latitude[]']").val(latitude);
        $(this).closest('.check-validation').find("input[name='longitude[]']").val(longitude);
        $(this).closest('.check-validation').find("input[name='flat_no[]']").val(flat_no);
    });
    $(document).on("click", ".submitTaskHeader", function(e) {
        e.preventDefault();
        var err = 0;
        $(".addspan").hide();
        $(".tagspan").hide();
        $(".tagspan2").hide();
        $(".searchspan").hide();

        var cus_id = $('#cusid').val();
        var name = $('#name_new').val();
        var email = $('#email_new').val();
        var phone_no = $('#phone_new').val();

        if (cus_id == '') {
            if (name != '' && email != '' && phone_no != '') {
                $(".searchspan").hide();
            } else {  err = 1;
                $(".searchspan").show();
                return false;
            }
        }else{
            $(".searchspan").hide();
        }
        var s_name = $("input[name='short_name[]']").val();
        var s_address = $("input[name='address[]']").val();
        if ((!$("input[name='old_address_id']:checked").val()) && (s_address=="") ) {
                err = 1;
                $(".addspan").show();
                return false;
        }


        $(".selecttype").each(function(){
            var taskselect              = $(this).val();
            var checkPickupBarcode      = $('#check-pickup-barcode').val();
            var checkDropBarcode        = $('#check-drop-barcode').val();
            var checkAppointmentBarcode = $('#check-appointment-barcode').val();
            var barcode                 = $(this).closest('.check-validation').find('.barcode').val();
            if(taskselect == 1 && checkPickupBarcode == 1 && barcode == ''){
                $(this).closest('.check-validation').find('.pickup-barcode-error').show();
                err = 1;
                return false;
            }else if(taskselect == 2 && checkDropBarcode == 1 && barcode == ''){
                $(this).closest('.check-validation').find('.drop-barcode-error').show();
                err = 1;
                return false;
            }else if(taskselect == 3 && checkAppointmentBarcode == 1 && barcode == ''){
                $(this).closest('.check-validation').find('.appointment-barcode-error').show();
                err = 1;
                return false;
            }
            
        });


        var selectedVal = "";
        var selected = $("#typeInputss input[type='radio']:checked");
        selectedVal = selected.val();

        if (typeof(selectedVal) == "undefined") {
            var short_name = $("#task-modal-header input[name='short_name[]']").val();
            var address = $("#task-modal-header input[name='address[]']").val();
            var post_code = $("#task-modal-header input[name='post_code[]']").val();
            var cash_to_be_collected = $("#task-modal-header input[name='cash_to_be_collected']").val();
            if (short_name != '' && address != '' && post_code != '') {

            } else {  err = 1;
                $(".addspan").show();
                return false;
            }
        }

        var autoval = "";
        var auto    = $("#rediodiv input[type='radio']:checked");
        autoval     = auto.val();

            if( err == 0){
                $('.submitTaskHeaderLoader').css('display', 'inline-block');
                $('.submitTaskHeader').addClass("inactiveLink");

                var formData = new FormData(document.querySelector("#taskFormHeader"));
                TaskSubmit(formData, 'POST', '/newtasks', '#task-modal-header');
            }
    });



    function TaskSubmit(data, method, url, modals) {
    $.ajax({
        method: method,
        headers: {
            Accept: "application/json"
        },
        url: url,
        data: data,
        contentType: false,
        processData: false,
        success: function(response) {
            if(response.status == 'Success')
            {var color = 'green';var heading="Success!";}else{var color = 'red';var heading="Error!";}
            $.toast({
            heading:heading,
            text : response.message,
            showHideTransition : 'slide',
            bgColor : color,
            textColor : '#eee',
            allowToastClose : true,
            hideAfter : 5000,
            stack : 5,
            textAlign : 'left',
            position : 'top-right'
            });
            if (response.status == 'Success') {
                    $("#task-modal-header .close").click();
                    location.reload();
            } else {
                $("#task-modal-header .show_all_error.invalid-feedback").show();
                $("#task-modal-header .show_all_error.invalid-feedback").text(response.message);
            }
        },
        error: function(response) {
            if (response.status === 422) {
                let errors = response.responseJSON.errors;
                Object.keys(errors).forEach(function (key) {
                    $("#" + key + "Input input").addClass("is-invalid");
                    $("#" + key + "Input span.invalid-feedback").children("strong").text(errors[key][0]);
                    $("#" + key + "Input span.invalid-feedback").show();
                });
            } else {
                $("#task-modal-header .show_all_error.invalid-feedback").show();
                $("#task-modal-header .show_all_error.invalid-feedback").text("Something went wrong, Please try Again.");
            }
        },
        complete: function(data){
            $('.submitTaskHeaderLoader').css('display', 'none');
            $('.submitTaskHeader').removeClass("inactiveLink");
        }
    });
}

    // show proofs initial check
    // $(document).ready(function() {
    //     $('.selecttype').val("1").click();
    // });
    $(document).on('keyup', ".onlynumber", function() {
    this.value = this.value.replace(/[^0-9\.]/g,'');
    });
    // $(document).on("change", "#file", function() {
    //    previewImages(this);
    // });

    //on select of task type

    $(document).on('change', ".selecttype", function() {

            // proof = task_proofs[this.value-1].barcode;

            // if(proof != 0){

            //  $(".barcode").show();

            // }else{
            //     $(".barcode").hide();
            // }

        if (this.value == 3){
           $span = $(this).closest(".firstclone1").find(".appoint").show();
           //console.log($span);
        }
        else{
            $(this).closest(".firstclone1").find(".appoint").hide();
        }
    });

    // $(document).on("click", "#file", function() {
    //   $('.showsimagegall').hide();
    //   $('.imagepri').remove();
    //    //readURL(this);
    // });

    function reArrangeFileWrapIndexes(img_wrap_class){
        $(img_wrap_class).each(function(index, elem){
            $(elem).attr('data-id', index);
        });
    }

    function insertArrayToFiles(routefileListArray){
        const dT = new ClipboardEvent('').clipboardData || new DataTransfer();
        for (let file of routefileListArray) {
            dT.items.add(file);
        }
        $('#file').prop("files",dT.files);
    }

    var routefileListArray = [];
    $(document).on("change", "#file", function() {
       previewImages(this);
    });

    function previewImages(input) { //console.log('1');
        // $('.imagepri_wrap').remove();
        var fileList = input.files;
        Array.prototype.push.apply(routefileListArray, Array.from(fileList));
        insertArrayToFiles(routefileListArray);

        // routefileListArray = Array.from(fileList);
        if(fileList.length){
            $(".showsimagegall").removeClass('d-block').addClass("d-none");
        }else{
            $(".showsimagegall").removeClass('d-none').addClass("d-block");
        }
        var anyWindow = window.URL || window.webkitURL; //console.log('2');

        for(var i = 0; i < fileList.length; i++){
            var objectUrl = anyWindow.createObjectURL(fileList[i]);
            $('#imagePreview').append('<div class="imagepri_wrap mb-2" data-id="'+i+'"><img src="' + objectUrl + '" class="imagepri mr-2" /><button type="button" class="close imagepri_close" aria-hidden="true">×</button></div>');
            window.URL.revokeObjectURL(fileList[i]);
        }

        reArrangeFileWrapIndexes();
    }

    $(document).on('click', '.imagepri_close', function(e){
        // console.log(savedFileListArray, 'before');
        var index = $(this).parents('.imagepri_wrap').attr('data-id');
        // console.log(index, 'index');
        if($(this).parents('.imagepri_wrap').hasClass("saved")){
            savedFileListArray.splice(index, 1);
            // console.log(savedFileListArray, 'after');
            $(this).parents('.imagepri_wrap').remove();
            reArrangeFileWrapIndexes('.imagepri_wrap.saved');
        }else{
            routefileListArray.splice(index, 1); // At position index, remove 1 file
            $(this).parents('.imagepri_wrap').remove();
            insertArrayToFiles(routefileListArray);
            reArrangeFileWrapIndexes('.imagepri_wrap');
        }
    });

    $(document).on('click', '.assignRadio', function () {

        var radioValue = $("#rediodiv input[type='radio']:checked").val();
        if (radioValue == 'a') {

            $( ".tags" ).removeClass("hidealloction");
            $( ".drivers" ).addClass("hidealloction");
        }
        if (radioValue == 'u') {
            $( ".tags" ).addClass("hidealloction");
            $( ".drivers" ).addClass("hidealloction");
        }
        if (radioValue == 'm') {
            $( ".drivers" ).removeClass("hidealloction");
            $( ".tags" ).addClass("hidealloction");
        }
    });

    $('#show-map-Header').on('hide.bs.modal', function () {
         $('#task-modal-header').removeClass('fadeIn');

    });

    $(document).on('click', '.showMapHeader', function(){
        //var no = $(this).attr('num');
        var no = $(this).attr('id') ?? $(this).attr('num');

        var lats = document.getElementById(no+'-latitude').value;
        var lngs = document.getElementById(no+'-longitude').value;
        var address = document.getElementById(no+'-input').value;

        document.getElementById('map_for_header').value = no;

        if(lats == null || lats == '0' || lats ==''){
            lats = 28.6862738;
        }
        if(lngs == null || lngs == '0' || lngs == ''){
            lngs = 77.2217831;
        }
         if(address==null){
            address= '';
        }
        var infowindow = new google.maps.InfoWindow();
        var geocoder = new google.maps.Geocoder();

        var myLatlng = new google.maps.LatLng(lats, lngs);
        var infowindow = new google.maps.InfoWindow();
        var geocoder = new google.maps.Geocoder();
            var mapProp = {
                center:myLatlng,
                zoom:13,
                mapTypeId:google.maps.MapTypeId.ROADMAP

            };
            var map=new google.maps.Map(document.getElementById("googleMapHeader"), mapProp);
                var marker = new google.maps.Marker({
                  position: myLatlng,
                  map: map,
                  title: 'Hello World!',
                  draggable:true
              });
            document.getElementById('lat_map_header').value= lats;
            document.getElementById('lng_map_header').value= lngs ;
            document.getElementById('addredd_map_header').value= address ;

            // marker drag event
            {{-- google.maps.event.addListener(marker,'drag',function(event) {
                console.log(event);
                document.getElementById('lat_map_header').value = event.latLng.lat();
                document.getElementById('lng_map_header').value = event.latLng.lng();
                //document.getElementById('addredd_map_header').value= event[].formatted_address;
            }); --}}

                google.maps.event.addListener(marker, 'dragend', function() {
                    geocoder.geocode({
                    'latLng': marker.getPosition()
                    }, function(results, status) {

                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                             document.getElementById('lat_map_header').value = marker.getPosition().lat();
                             document.getElementById('lng_map_header').value = marker.getPosition().lng();
                             document.getElementById('addredd_map_header').value= results[0].formatted_address;

                            infowindow.setContent(results[0].formatted_address);

                            infowindow.open(map, marker);
                        }
                    }
                    });
                });
            //marker drag event end
            {{-- google.maps.event.addListener(marker,'dragend',function(event) {
                var zx =JSON.stringify(event);
                //console.log(zx);


                document.getElementById('lat_map_header').value = event.latLng.lat();
                document.getElementById('lng_map_header').value = event.latLng.lng();
              //   document.getElementById('addredd_map_header').value= event.formatted_address;
                //alert("lat=>"+event.latLng.lat());map_for_header
                //alert("long=>"+event.latLng.lng());
            }); --}}
            $('#task-modal-header').addClass('fadeIn');
        $('#show-map-Header').modal({
            //backdrop: 'static',
            keyboard: false
        });

    });

    $(document).on('click', '.selectMapOnHeader', function () {

        var mapLat = document.getElementById('lat_map_header').value;
        var mapLlng = document.getElementById('lng_map_header').value;
        var mapFor = document.getElementById('map_for_header').value;
        var address = document.getElementById('addredd_map_header').value;
        
        document.getElementById(mapFor + '-latitude').value = mapLat;
        document.getElementById(mapFor + '-longitude').value = mapLlng;
        document.getElementById(mapFor + '-input').value = address;

        $('#show-map-Header').modal('hide');
    });


    $(document).on('click', '.alFormTaskType', function () {
        $(".alTaskType").removeClass("active");
        $(".mainaddress").addClass("alCol-12");
        $(".alsavedaddress").hide();
        $(this).closest('.alTaskType').find(".mainaddress").removeClass("alCol-12");
        $(this).closest('.alTaskType').addClass("active");
        $(this).closest('.alTaskType').find(".alsavedaddress").show();
    });

    if(theme['theme'] == 'dark'){

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

    $('.onlynumber').keyup(function ()
        {
        this.value = this.value.replace(/[^0-9\.]/g,'');
    });

</script>
