<script>
    var autocomplete = {};
    var autocompletesWraps = ['add0'];
    var count = 1; editCount = 0;
    $('.openModal').click(function(){
        $('#add-task-modal').modal({
            //backdrop: 'static',
            keyboard: false
        });
        autocompletesWraps.push('add1');
        loadMap(autocompletesWraps);
    });

    var latitudes = []; 
    var longitude = [];

    function loadMap(autocompletesWraps){

       /* console.log(autocompletesWraps);
        $.each(autocompletesWraps, function(index, name) {
            const geocoder = new google.maps.Geocoder;
        
            if($('#'+name).length == 0) {
                return;
            }
            //autocomplete[name] = new google.maps.places.Autocomplete(('.form-control')[0], { types: ['geocode'] }); console.log('hello');
            autocomplete[name] = new google.maps.places.Autocomplete(document.getElementById(name+'-input'), { types: ['geocode'] });
                
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
        });*/

    }

    $(".addTaskModal").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
       
        $.ajax({
            type: "get",
            url: "<?php echo url('tasks'); ?>" + '/create',
            data: '',
            dataType: 'json',
            success: function (data) {

                $('.page-title1').html('Hello');
                console.log('data');

                $('#add-task-modal #addCardBox').html(data.html);
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
                                    searchRes();
                $('#add-task-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                editCount = data.addFieldsCount;
                for (var i = 1; i <= data.addFieldsCount; i++) {
                    autocompletesWraps.push('edit'+i);
                    loadMap(autocompletesWraps);
                }
            },
            error: function (data) {
                console.log('data2');
            }
        });
    });

    //var = 
    var CSRF_TOKEN = $("input[name=_token]").val();
    function searchRes(){ console.log('1');
        $("#add-task-modal #search").autocomplete({
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
                        console.log(data);
                    }
                });
            },
            select: function(event, ui) { console.log('2');
                // Set selection
                console.log(ui.item.label+' - '+ui.item.value);
                $('#add-task-modal #search').val(ui.item.label); // display the selected text
                $('#add-task-modal #cusid').val(ui.item.value); // save selected id to input
                console.log(ui.item.value);
                add_event(ui.item.value);
                $(".oldhide").hide();
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

    var a = 0; countZ = 1;
    $(document).on('click', ',subTaskAdd', function(){
        var grab = $('.forClone').clone();
        grab.removeClass('forClone');
        grab.replace("Microsoft", "W3Schools");
        //grab.#CURRENTMAP

    })
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

    //autocompletesWraps.indexOf('add'+countZ) === -1 ? autocompletesWraps.push('add'+countZ) : console.log("This item already exists");

        //console.log(autocompletesWraps);
        //loadMap(autocompletesWraps);
    });



    /*$(document).on('click', '.showMap', function(){
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

    });*/

    $('#show-map-modal').on('hide.bs.modal', function () {
         $('#add-customer-modal').removeClass('fadeIn');

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


    $(document).on('click', '.addField', function(){
        count = count + 1;

        $(document).find('#address-map-container').before('<div class="row address" id="add'+count+'"><div class="col-md-4"><div class="form-group" id=""><input type="text"  class="form-control" placeholder="Short Name" name="short_name[]"></div></div><div class="col-md-5"><div class="form-group input-group" id=""><input type="text" id="add'+count+'-input" name="address[]" class="autocomplete form-control" placeholder="Address"><div class="input-group-append"><button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="add'+count+'"> <i class="mdi mdi-map-marker-radius"></i></button></div><input type="hidden" name="latitude[]" id="add'+count+'-latitude" value="0" /><input type="hidden" name="longitude[]" id="add'+count+'-longitude" value="0" /></div></div><div class="col-md-3"><div class="form-group" id=""><input type="text"  class="form-control" placeholder="Post Code" name="post_code[]"></div></div></div>');

        autocompletesWraps.indexOf('add'+count) === -1 ? autocompletesWraps.push('add'+count) : console.log("This item already exists");
        
        //console.log(autocompletesWraps);
        loadMap(autocompletesWraps);

    });


    

    $(document).on('click', '.editInput', function(){
        editCount = editCount + 1;

        $(document).find('#editAddress-map-container').before('<div class="row address" id="edit'+editCount+'"><div class="col-md-4"><div class="form-group" id=""><input type="text"  class="form-control" placeholder="Short Name" name="short_name[]"></div></div><div class="col-md-5"><div class="form-group input-group" id=""><input type="text" id="edit'+editCount+'-input" name="address[]" class="autocomplete form-control" placeholder="Address"><div class="input-group-append"><button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="edit'+editCount+'"> <i class="mdi mdi-map-marker-radius"></i></button></div><input type="hidden" name="latitude[]" id="edit'+editCount+'-latitude" value="0" /><input type="hidden" name="longitude[]" id="edit'+editCount+'-longitude" value="0" /></div></div><div class="col-md-3"><div class="form-group" id=""><input type="text"  class="form-control" placeholder="Post Code" name="post_code[]"></div></div></div>');

        autocompletesWraps.indexOf('edit'+editCount) === -1 ? autocompletesWraps.push('edit'+editCount) : console.log("This item already exists");
        
        //console.log(autocompletesWraps);
        loadMap(autocompletesWraps);

    });

    /* add Team using ajax*/
    $("#add-customer-modal #add_customer").submit(function(e) {
            e.preventDefault();
    });
    $(document).on('click', '.submitCustomerForm', function() { 
        var form =  document.getElementById('add_customer');
        var formData = new FormData(form);
        var urls = "{{URL::route('customer.store')}}";
        saveCustomer(urls, formData, inp = '', modal = 'add-customer-modal');
    });

    $("#edit-customer-modal #edit_customer").submit(function(e) {
            e.preventDefault();
    });

    $(document).on('click', '.submitEditForm', function(e) {
        e.preventDefault();
        var form =  document.getElementById('edit_customer');
        var formData = new FormData(form);
        var urls =  document.getElementById('customer_id').getAttribute('url');
        saveCustomer(urls, formData, inp = 'Edit', modal = 'edit-customer-modal');
        console.log(urls);
    });

    function saveCustomer(urls, formData, inp = '', modal = ''){

         $.ajax({
            method: 'post',
            headers: {
                Accept: "application/json"
            },
            url: urls,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status == 'success') {
                    $("#" + modal + " .close").click();
                    location.reload(); 
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            error: function(response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $("#" + key + "Input" + inp + " input").addClass("is-invalid");
                        $("#" + key + "Input" + inp + " span.invalid-feedback").children("strong").text(errors[key][0]);
                        $("#" + key + "Input span.invalid-feedback").show();
                    });
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                }
                return response;
            }
        });

    }

    
</script>