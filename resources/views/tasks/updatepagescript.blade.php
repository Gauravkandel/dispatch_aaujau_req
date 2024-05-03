<script>
    var autocomplete = {};
    var countEdit = parseInt('{{$task->task->count()}}');
    var totalcountEdit = countEdit;
    var autocompletesWraps = [];
 editCount = 0;
$(document).ready(function(){
    
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


    $('#selectize-optgroups').selectize();
    $('#selectize-optgroup').selectize();

    for (var i = 1; i <= countEdit; i++) {
        autocompletesWraps.push('add'+i);
        loadMap(autocompletesWraps);
    }
    loadMap(autocompletesWraps);

    let phone_number_intltel = window.intlTelInput(document.querySelector("#taskFormHeader .phone_number"),{
        separateDialCode: true,
        initialCountry:"{{$task->customer->countrycode}}",
        hiddenInput: "full_number",
        utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
    });

    document.querySelector("#taskFormHeader .phone_number").addEventListener("countrychange", function() {
        $("#taskFormHeader #dialCode").val(phone_number_intltel.getSelectedCountryData().dialCode);
    });

    $('#taskFormHeader .edit-icon-float-right').on('click', function() {
        $('#taskFormHeader .meta_data_task_div').toggle();
        if($(this).find('i').hasClass('mdi mdi-chevron-down')){
            $(this).find('i').removeClass('mdi mdi-chevron-down');
            $(this).find('i').addClass('mdi mdi-chevron-up');
        }else{
            $(this).find('i').removeClass('mdi mdi-chevron-up');
            $(this).find('i').addClass('mdi mdi-chevron-down');
        }
    });
});
    $(document).ready(function() {
        $('.dropify').dropify();
        $(".newcustomer").hide();
        $(".addspan").hide();
        $(".tagspan").hide();
        $(".tagspan2").hide();
        $(".searchspan").hide();
        $(".appoint").hide();
        $(".datenow").hide();
        $(".oldhide").hide();
        $(".pickup-barcode-error").hide();
        $(".drop-barcode-error").hide();
        $(".appointment-barcode-error").hide();
        $("#AddressInput a").click(function() {
            $(".shows").show();
            $(".append").hide();
            $(".searchshow").hide();
            $('input[name=ids').val('');
            $('input[name=search').val('');
            autoWrap = ['add1'];
            countEdit = 1;

        });
        $("#Inputsearch a").click(function() {
            $(".shows").hide();
            $(".append").hide();
            $(".searchshow").show();
            autoWrap = ['add1'];
            countEdit = 1;

        });

        $("#nameInput").keyup(function() {
            $(".shows").hide();
            $(".oldhide").show();
            $(".append").hide();
            $('input[name=ids').val('');
            autoWrap = ['add1'];
            countEdit = 1;

        });
        
        $(document).on('click', ".span1", function() {
            var task_id = $(this).attr("data-taskid");
            if(task_id != undefined && task_id > 0){   
                $(this).closest(".copyin").remove();       // while update the task in dispatch 
                var status = TaskDeleteSingle(task_id);
            }else{
                $(this).closest(".copyin").remove();
            }
           
        });


        ///////////////////// ****************              delete single task ********************* //////////////////////////////

        function TaskDeleteSingle(task_id) {
            var CSRF_TOKEN = $("input[name=_token]").val();
            $.ajax({
                method: 'post',
                headers: {
                    Accept: "application/json"
                },
                url: "{{route('tasks.single.destroy')}}",
                data: {_token: CSRF_TOKEN,task_id:task_id},
                success: function(response) {
                    
                    if (response) {
                        if(response.count == 1)
                        window.location.href = response.url;
                        else
                        return 1;
                    
                    } else {
                        return 2;
                    }
                    //return response;
                },
                error: function(response) {
                    return 2;
                }
            });
        }


        //////////////////// ************************** end delete single task ************************* ///////////////////////////
        
        var a = totalcountEdit-1;
        var post_count = 1;
        $('#adds a').click(function() {
           
            countEdit = countEdit + 1;
            var abc = "{{ isset($maincount)?$maincount:0 }}";
            var newcount = $('#newcount').val();
            
            post_count = parseInt(newcount) + 1;
          
            a++;
           
                    var newids = null;
                    
                    var $div = $('div[class^="alTaskType copyin check-validation"]:last');
                    var newcheck = $div.find('.redio');
                    $.each(newcheck, function(index, elem){
                        var jElem = $(elem); // jQuery element
                        var name = jElem.prop('checked');
                        var id = jElem.prop('id');
                        if(name == true){
                          newids = id;
                        }
                    });
                   
                    var num = parseInt( $div.prop("id").match(/\d+/g), 10 ) +1;
                    var $clone = $div.clone().prop('class', 'alTaskType copyin check-validation');
                    $clone.insertAfter('[class^="alTaskType copyin check-validation"]:last');
                    // get all the inputs inside the clone
                    var inputs = $clone.find('.redio');

                    $clone.find('.mainaddress').prop('id', 'add' + countEdit);
                    $clone.find('.cust1_add').prop('id', 'add' + countEdit +'-input');
                    $clone.find('.cust1_btn').prop('num', 'add' + countEdit);
                    $clone.find('.cust1_btn').prop('id', 'add' + countEdit);
                    $clone.find('.cust1_latitude').prop('id', 'add' + countEdit +'-latitude');
                    $clone.find('.cust1_longitude').prop('id', 'add' + countEdit +'-longitude');
                    // for each input change its name/id appending the num value
                    var count0 = 1;

                    $('#add'+countEdit+' input[type="text"]').val('');

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
                        
                        jElem.prop('for', name);
                        count2++;
                    });
                    var spancheck = $clone.find('.onedelete');
                    $.each(spancheck, function(index, elem){
                           
                        var jElem = $(elem); // jQuery element
                        var name = jElem.prop('id');
                         name = name.replace(/\d+/g, '');
                        // remove the number
                        name = 'newspan';
                        jElem.prop('id', name);

                        var taskid = jElem.attr("data-taskid");
                        jElem.attr("data-taskid", 0);
                    });

                    var address1 = $clone.find('.address');
                    $.each(address1, function(index, elem){
                           
                        var jElem = $(elem); // jQuery element
                        jElem.prop('required', true);
                    });

                    var flat_no = $clone.find('.flat_no');
                    $.each(flat_no, function(index, elem){
                        var jElem = $(elem)
                        var name = jElem.prop('id');
                        name = name.replace(/\d+/g, '');
                        name = 'add'+post_count+'-flat_no';
                        jElem.prop('id', name);
                    });

                    var alcoholicItem = $clone.find('.alcoholic_item');
                    $.each(alcoholicItem, function(index, elem){
                        var jElem = $(elem)
                        var name = jElem.prop('id');
                        name = name.replace(/\d+/g, '');
                        name = 'add'+post_count+'-alcoholic_item';
                        jElem.prop('id', name);

                        var alcoholicItemLabel = $clone.find('.alcoholic_item_label');
                        $.each(alcoholicItemLabel, function(index, elem){
                            var jElem = $(elem);
                            var labelName = jElem.prop('for');
                            labelName = labelName.replace(/\d+/g, '');
                            labelName = 'add'+post_count+'-alcoholic_item';
                            jElem.prop('for', labelName);
                        });
                    });


                    var postcode1 = $clone.find('.postcode');
                    $.each(postcode1, function(index, elem){
                        var jElem = $(elem)
                        var name = jElem.prop('id');
                        name = name.replace(/\d+/g, '');
                        name = 'add'+post_count+'-postcode';
                        jElem.prop('id', name);
                        post_count++;
                    });


                    $('input[id='+newids+']').prop("checked",true);
                   

            autocompletesWraps.indexOf('add'+countEdit) === -1 ? autocompletesWraps.push('add'+countEdit) : console.log("exists");
            loadMap(autocompletesWraps);
            
        });

        $(document).on("click", ".submitUpdateTaskHeader", function(e) {
            e.preventDefault();
            var err = 0;
            
            $("input[name='address[]']").each(function(){
                var address = $(this).val();
                if(address == ''){
                    err = 1;
                    $(this).closest('.check-validation').find('.addspan').show();
                    return false;
                }
            });

            if(err == 1){
                return false;
            }else if(err == 0){
                $(".pickup-barcode-error").hide();
                $(".drop-barcode-error").hide();
                $(".appointment-barcode-error").hide();
                var id       = $("#order-id").val();
                var formData = new FormData(document.querySelector("#taskFormHeader"));
                updateTaskSubmit(formData, 'POST', '/updatetasks/tasks/'+id);
            }
        });

        function updateTaskSubmit(data, method, url) {
            for(var i=0; i < savedFileListArray.length; i++){
                data.append('savedFiles[]', savedFileListArray[i]);
            }
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
                            window.location.href = '/tasks';
                    } 
                },
                error: function(response) {
                    
                }
            });
        }

        $(document).on('change', ".selecttype", function() {
        
            
            if (this.value == 3){
               $span = $(this).closest(".firstclone1").find(".appoint").show();
            }   
            else{
                $(this).closest(".firstclone1").find(".appoint").hide();
            
            }
            
        });

        $(".callradio input").click(function() { 

            if ($(this).is(":checked")) { 
              $span = $(this).closest(".requried").find(".alladdress");
              $(this).parent().css("border", "2px solid black"); 
            }
        });
        $(document).on("click", "input[type='radio']", function () {
            $span = $(this).closest(".requried").find(".address").removeAttr("required");
        });

        $(".tags").hide();
        $(".drivers").hide();
        $("input[type='radio'].assignRadio").click(function() {
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

        var edit_tag = "{{$task->auto_alloction}}";
        switch(edit_tag) {
          case "a":
          $(".tags").show();
            break;
          case "m":
          $(".drivers").show();
            break;
          
        }
        var schudle = "{{$task->order_type}}";
        
        if(schudle == 'schedule'){
          $(".datenow").show();
        }

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

        $(document).on('click', '#clear-address', function(){
            $(this).closest('.check-validation').find("input:checked").prop('checked', false);
            $(this).closest('.check-validation').find("input[name='short_name[]']").val('');
            $(this).closest('.check-validation').find("input[name='address_email[]']").val('');
            $(this).closest('.check-validation').find("input[name='address[]']").val('');
            $(this).closest('.check-validation').find("input[name='address_phone_number[]']").val('');
            $(this).closest('.check-validation').find("input[name='post_code[]']").val('');
            $(this).closest('.check-validation').find("input[name='flat_no[]']").val('');
            $(this).closest('.check-validation').find("input[name='latitude[]']").val('');
            $(this).closest('.check-validation').find("input[name='longitude[]']").val('');
        });
        
        $('input[type="radio"]').each(function(){
            if($(this).is(':checked')) {
                var shortName = $(this).data("srtadd");
                if(shortName != undefined){
                    var address     = $(this).data("adr");
                    var latitude    = $(this).data("lat");
                    var longitude   = $(this).data("long");
                    var postCode    = $(this).data("pstcd");
                    var flat_no     = $(this).data("flat_no");
                    var email       = $(this).data("emil");
                    var phoneNumber = $(this).data("ph");
                    $(this).closest('.check-validation').find("input[name='short_name[]']").val(shortName);
                    $(this).closest('.check-validation').find("input[name='address_email[]']").val(email);
                    $(this).closest('.check-validation').find("input[name='address[]']").val(address);
                    $(this).closest('.check-validation').find("input[name='address_phone_number[]']").val(phoneNumber);
                    $(this).closest('.check-validation').find("input[name='post_code[]']").val(postCode);
                    $(this).closest('.check-validation').find("input[name='flat_no[]']").val(flat_no);
                    $(this).closest('.check-validation').find("input[name='latitude[]']").val(latitude);
                    $(this).closest('.check-validation').find("input[name='longitude[]']").val(longitude);
                }
            }
        });

        $(document).on('click', '.old-select-address', function(){
            var shortName   = $(this).data("srtadd");
            var address     = $(this).data("adr");
            var latitude    = $(this).data("lat");
            var longitude   = $(this).data("long");
            var postCode    = $(this).data("pstcd");
            var email       = $(this).data("emil");
            var phoneNumber = $(this).data("ph");
            var flat_no     = $(this).data("flat_no");
            
            $(this).closest('.check-validation').find("input[name='short_name[]']").val(shortName);
            $(this).closest('.check-validation').find("input[name='address_email[]']").val(email);
            $(this).closest('.check-validation').find("input[name='address[]']").val(address);
            $(this).closest('.check-validation').find("input[name='address_phone_number[]']").val(phoneNumber);
            $(this).closest('.check-validation').find("input[name='post_code[]']").val(postCode);
            $(this).closest('.check-validation').find("input[name='flat_no[]']").val(flat_no);
            $(this).closest('.check-validation').find("input[name='latitude[]']").val(latitude);
            $(this).closest('.check-validation').find("input[name='longitude[]']").val(longitude);
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

                    $('.editwithradio .append').remove();
                    jQuery.each(array, function(i, val) {
                        var countz = '';
                        var rand =  Math.random().toString(36).substring(7);
                        $(".editwithradio").each(function(){
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
                    $(".searchspan").hide();
                } else {
                    $(".searchspan").show();
                    return false;
                }
            }

            var selectedVal = "";
            var selected = $("#typeInputss input[type='radio']:checked");
            selectedVal = selected.val();
            
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
    });

function loadMap(autocompletesWraps){

    $.each(autocompletesWraps, function(index, name) {
        const geocoder = new google.maps.Geocoder; 

        if($('#'+name).length == 0) {
            return;
        }
        autocomplete[name] = new google.maps.places.Autocomplete(document.getElementById(name+'-input'), { types: ['geocode'] });
        google.maps.event.addListener(autocomplete[name], 'place_changed', function() {
            
            var place = autocomplete[name].getPlace();
            
            geocoder.geocode({'placeId': place.place_id}, function (results, status) {
                
                if (status === google.maps.GeocoderStatus.OK) {

                    
                    const lat = results[0].geometry.location.lat();
                    const lng = results[0].geometry.location.lng();
                    
                    document.getElementById(name + '-latitude').value = lat;
                    document.getElementById(name + '-longitude').value = lng;
                    const postCode = results[0].address_components.find(addr => addr.types[0] === "postal_code").short_name;
                    document.getElementById(name + '-postcode').value = postCode;
                }
            });
        });
    });

}

$(document).on('click', '.showMapTask', function(){
    var no = $(this).attr('id') ??  $(this).attr('num') ;
    
    var lats = document.getElementById(no+'-latitude').value;
    var lngs = document.getElementById(no+'-longitude').value;
    var address = document.getElementById(no+'-input').value;
    document.getElementById('map_for').value = no;

    if(lats == null || lats == '0'){
        lats = 51.508742;
    }
    if(lngs == null || lngs == '0'){
        lngs = -0.120850;
    }
    if(address==null){
            address= '';
    }
    var infowindow = new google.maps.InfoWindow();
    var geocoder = new google.maps.Geocoder();

    var myLatlng = new google.maps.LatLng(lats, lngs);
        var mapProp = {
            center:myLatlng,
            zoom:13,
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
        document.getElementById('addredd_map').value= address ; 
        // marker drag event
        {{-- google.maps.event.addListener(marker,'drag',function(event) {
            document.getElementById('lat_map').value = event.latLng.lat();
            document.getElementById('lng_map').value = event.latLng.lng();
        }); --}}
        google.maps.event.addListener(marker, 'dragend', function() {
                    geocoder.geocode({
                    'latLng': marker.getPosition()
                    }, function(results, status) {

                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                             document.getElementById('lat_map_header').value = marker.getPosition().lat();
                             document.getElementById('lng_map_header').value = marker.getPosition().lng();
                             document.getElementById('addredd_map').value= results[0].formatted_address; 
                        
                            infowindow.setContent(results[0].formatted_address);
                         
                            infowindow.open(map, marker);
                        }
                    }
                    });
                });

        //marker drag event end
        {{-- google.maps.event.addListener(marker,'dragend',function(event) {
            var zx =JSON.stringify(event);

            document.getElementById('lat_map').value = event.latLng.lat();
            document.getElementById('lng_map').value = event.latLng.lng();
           
        }); --}}
        $('#add-customer-modal').addClass('fadeIn');
    $('#show-map-modal').modal({
        keyboard: false
    });

});

$(document).on('click', '.selectMapLocation', function () {

    var mapLat = document.getElementById('lat_map').value;
    var mapLlng = document.getElementById('lng_map').value;
    var mapFor = document.getElementById('map_for').value;
    var addredd_map = document.getElementById('addredd_map').value;
    
    document.getElementById(mapFor + '-latitude').value = mapLat;
    document.getElementById(mapFor + '-longitude').value = mapLlng;
    document.getElementById(mapFor + '-input').value = addredd_map;


    $('#show-map-modal').modal('hide');
});

$('.onlynumber').keyup(function ()
    { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});



$(document).on('click', '.mdi-delete-single-task', function() {            
            var r = confirm("{{__('Are you sure?')}}");
            if (r == true) {
               var taskid = $(this).attr('taskid');
               $('form#taskdeletesingle'+taskid).submit();

            }
        });


</script>