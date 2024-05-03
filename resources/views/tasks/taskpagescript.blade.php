<script>

    $(document).ready(function() {
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        hash = hashes[0].split('=');
        if(hash[0] == 'status'){
            $('#routes-listing-status').val(hash[1]);
        }else{
            $('#routes-listing-status').val('unassigned');
        }

        initializeRouteListing();
        function initializeRouteListing(){
            $('.agents-datatable').DataTable({
                "dom": '<"toolbar">Bfrtip',
                "destroy": true,
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "iDisplayLength": 20,
                "paging": true,
                "lengthChange" : true,
                "searching": true,
                language: {
                            search: "",
                            paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                            searchPlaceholder: "{{__('Search Routes')}}",
                            'loadingRecords': '&nbsp;',
                            //'sProcessing': '<div class="spinner" style="top: 90% !important;"></div>'
                            'sProcessing':function(){
                                spinnerJS.showSpinner();
                                spinnerJS.hideSpinner();
                            }
                },
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                },
                buttons: [{
                    className:'btn btn-success waves-effect waves-light',
                    text: '<span class="btn-label"><i class="mdi mdi-export-variant"></i></span>{{__("Export CSV")}}',
                    action: function ( e, dt, node, config ) {
                        window.location.href = "{{ route('task.export') }}";
                    }
                }],
                ajax: {
                    url: "{{url('task/filter')}}",
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    data: function (d) {
                        d.search = $('input[type="search"]').val();
                        d.routesListingType = $('#routes-listing-status').val();
                        d.imgproxyurl = '{{$imgproxyurl}}';
                    }
                },
                columns: dataTableColumn(),
            });
        }

        function dataTableColumnSort(){
            var routesListing = $('#routes-listing-status').val();
            if(routesListing == 'unassigned'){
                return [[ 10, "desc" ]];
            }else{
                return [[ 10, "desc" ]];
            }
        }

        function dataTableColumn(){
            var routesListing = $('#routes-listing-status').val();
            if(routesListing == 'unassigned'){
                return [
                    {data: 'id', name: 'id', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        return '<input type="checkbox" class="single_driver_check" name="driver_id[]" id="single_driver" value="'+full.id+'">';
                    }},
                    {data: 'order_number', name: 'order_number', orderable: true, searchable: false , "mRender": function ( data, type, full ) {
                        return full.order_number;
                    }},
                    {data: 'customer_id', name: 'customer_id', orderable: true, searchable: false},
                    {data: 'customer_name', name: 'customer_name', orderable: true, searchable: false},
                    {data: 'phone_number', name: 'phone_number', orderable: true, searchable: false},
                    {data: 'agent_name', name: 'agent_name', orderable: true, searchable: false, "mRender": function ( data, type, full ) {
                        if(full.status=='unassigned')
                        {
                            var selectbox= '<select name="agent_name_id" id="agent_name_id" data-id="'+full.id+'" class="form-control select_agent">';
                            selectbox+='<option value=""> Select {{__(getAgentNomenclature()) }} </option>';
                            @foreach ($agents as $item)
                            @php
                                $checkAgentActive = ($item->is_available == 1) ? ' ('.__('Online').')' : ' ('.__('Offline').')';
                            @endphp
                            selectbox+='<option value="{{$item->id}}">{{ ucfirst($item->name). $checkAgentActive}}</option>';
                            @endforeach
                            selectbox+='</select>';
                            return selectbox;

                        }else{
                            return full.order_number;
                        }
                    }},
                    {data: 'order_time', name: 'order_time', orderable: true, searchable: false, "mRender": function ( data, type, full ) {
                        
                        return '<div class="datetime_div"><i class="mdi mdi-av-timer"></i> '+full.order_time+'</div>';
                    }},
                    {data: 'short_name', name: 'short_name', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        var shortName = JSON.parse(full.short_name.replace(/&quot;/g,'"'));
                        var routes = '';
                        $.each(shortName, function(index, elem) {
                            routes += '<div class="address_box"><span class="'+elem.pickupClass+'">'+elem.taskType+'</span> <span class="short_name">'+elem.shortName+'</span> <label class="datatable-cust-routes" data-toggle="tooltip" data-placement="bottom" title="'+elem.toolTipAddress+'">'+elem.address+'</label></div>';
                        });
                        return routes;
                    }},
                    {data: 'order_cost', name: 'order_cost', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        return '<button class="showaccounting btn btn-primary-outline action-icon setcolor" value="'+full.id+'">'+full.order_cost+'</button>';
                    }},
                    {data: 'updated_at', name: 'updated_at', orderable: true, searchable: false},
                    {data: 'action', name: 'action', orderable: true, searchable: false}
                ];
            }else{
                return [
                    {data: 'order_number', name: 'order_number', orderable: true, searchable: false , "mRender": function ( data, type, full ) {
                        return full.order_number;
                    }},
                    {data: 'customer_id', name: 'customer_id', orderable: true, searchable: false},
                    {data: 'customer_name', name: 'customer_name', orderable: true, searchable: false},
                    {data: 'phone_number', name: 'phone_number', orderable: true, searchable: false},
                    {data: 'agent_name', name: 'agent_name', orderable: true, searchable: false},
                    {data: 'order_time', name: 'order_time', orderable: true, searchable: false},
                    {data: 'short_name', name: 'short_name', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        var shortName = JSON.parse(full.short_name.replace(/&quot;/g,'"'));
                        var routes = '';
                        $.each(shortName, function(index, elem) {
                            routes += '<div class="address_box"><span class="'+elem.pickupClass+'">'+elem.taskType+'</span> <span class="short_name">'+elem.shortName+'</span> <label data-toggle="tooltip" data-placement="bottom" title="'+elem.toolTipAddress+'">'+elem.address+'</label></div>';
                        });
                        return routes;
                    }},
                    
                    {data: 'order_cost', name: 'order_cost', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        return '<button class="showaccounting btn btn-primary-outline action-icon setcolor" value="'+full.id+'">'+full.order_cost+'</button>';
                    }},
                    {data: 'updated_at', name: 'updated_at', orderable: true, searchable: false},
                    {data: 'action', name: 'action', orderable: true, searchable: false}
                ]
            }
        }


    });

    function handleClick(myRadio) {
        $('#getTask').submit();
    }

    $(document).on('change', '.select_agent', function() {
        if($(this).val()!='')
        {
            var order_id = Array($(this).attr('data-id'));
            $.ajax({
                type: "POST",
                url: '{{route("assign.agent")}}',
                data: {_token: CSRF_TOKEN, orders_id: order_id, agent_id: $(this).val()},
                success: function( msg ) {
                    $.toast({ 
                    heading:"Success!",
                    text : "{{__(getAgentNomenclature()) }} assigned successfully.", 
                    showHideTransition : 'slide', 
                    bgColor : 'green',              
                    textColor : '#eee',            
                    allowToastClose : true,      
                    hideAfter : 5000,            
                    stack : 5,                   
                    textAlign : 'left',         
                    position : 'top-right'      
                    });
                    location.reload();
                },
                error: function(errors){
                    $.toast({ 
                    heading:"Error!",
                    text : "{{__(getAgentNomenclature()) }} can not be assigned.", 
                    showHideTransition : 'slide', 
                    bgColor : 'red',              
                    textColor : '#eee',            
                    allowToastClose : true,      
                    hideAfter : 5000,            
                    stack : 5,                   
                    textAlign : 'left',         
                    position : 'top-right'      
                    });
                    location.reload();
                }
            });
        }
    });

    //this is for task detail pop-up
    $(document).on('click', '.showtasks', function() {
        var CSRF_TOKEN = $("input[name=_token]").val();
        var tour_id = $(this).val();
        var basic = window.location.origin;
        var url = basic + "/tasks/list/" + tour_id;
        $.ajax({
            url: url,
            type: 'post',
            dataType: "json",
            data: {
                _token: CSRF_TOKEN,
                status: status
            },
            success: function(data) {
                console.log(data.task);
                $('.repet').remove();
                var taskname = '';
                $.each(data.task, function(index, elem) {


                    switch (elem.task_type_id) {
                        case 1:
                            taskname = 'Pickup task';
                            break;
                        case 2:
                            taskname = 'Drop Off task';
                            break;
                        case 3:
                            taskname = 'Appointment';
                            break;
                    }
                    var date = new Date(elem.order_time);
                    var options = {
                        hour12: true
                    };
                    var short_name = (elem.location.short_name) ??'';
                    $(document).find('.allin').before(
                        '<div class="repet"><div class="task-card p-3"><div class="p-2 assigned-block"><h5>' +
                        taskname +
                        '</h5><div class="wd-10"><img class="vt-top" src="{{ asset('demo/images/ic_location_blue_1.png') }}"></div><div class="wd-90"><h6>' +
                        elem.location.address + '</h6><span>' +short_name+
                        '</span><h5 class="mb-1"><span></span></h5><div class="row"><div class="col-md-6"></div><div class="col-md-6 text-right"><button class="assigned-btn">' +
                        data.status + '</button></div></div></div></div></div></div>');


                });

                $('#task-list-modal').modal('show');

            }

        });
    });

    //this is for accounting calculation  pop-up

    $(document).on('click', '.showaccounting', function() {
        // $('#task-accounting-modal').modal('show');
        //   return;
        var CSRF_TOKEN = $("input[name=_token]").val();
        var tour_id = $(this).val();
        var basic = window.location.origin;

        var url = basic + "/tasks/list/" + tour_id;
        $.ajax({
            url: url,
            type: 'post',
            dataType: "json",
            data: {
                _token: CSRF_TOKEN,
            },
            success: function(data) {

                $("#base_distance").text(round(data.base_distance));
                $("#actual_distance").text(data.actual_distance);
                $("#billing_distance").text(Math.max(0, round(data.actual_distance - data.base_distance, 2)));
                var sendDistance = (data.actual_distance - data.base_distance) * data.distance_fee;
                $("#distance_cost").text(Math.max(0,round(sendDistance, 2)));

                $("#base_duration").text(data.base_duration);
                $("#actual_duration").text(data.actual_time);
                $("#billing_duration").text(Math.max(0,data.actual_time - data.base_duration));
                var sendDuration = (data.actual_time - data.base_duration) * data.duration_price;
                $("#duration_cost").text(Math.max(0,sendDuration));

                $("#base_price").text(data.base_price);
                $("#duration_price").text(data.duration_price + ' (Per min)');
                $("#distance_fee").text(data.distance_fee + ' (' + data.distance_type + ')');
                $("#driver_type").text(data.driver_type);

                $("#order_cost").text(data.order_cost);
                $("#driver_cost").text(data.driver_cost ? data.driver_cost : 0.00 );

                $("#base_waiting").val(data.base_waiting);
                $("#distance_fee").val(data.distance_fee);
                $("#cancel_fee").val(data.cancel_fee);
                $("#agent_commission_percentage").text(data.agent_commission_percentage);
                $("#agent_commission_fixed").text(data.agent_commission_fixed);
                $("#freelancer_commission_percentage").text(data.freelancer_commission_percentage);
                $("#freelancer_commission_fixed").text(data.freelancer_commission_fixed);
                $('#task-accounting-modal').modal('show');

            }

        });
    });

     //this is for task proofs  pop-up

     $(document).on('click', '.showTaskProofs', function() {
        // $('#task-accounting-modal').modal('show');
        //   return;
        var CSRF_TOKEN = $("input[name=_token]").val();
        var tour_id = $(this).val();
        var basic = window.location.origin;
        $("#new-all-class").empty();
        var url = basic + "/tasks/list/" + tour_id;
        $.ajax({
            url: url,
            type: 'post',
            dataType: "json",
            data: {
                _token: CSRF_TOKEN,
            },
            success: function(data) {

                $.each(data.task, function(index, elem) {


                    switch (elem.task_type_id) {
                        case 1:
                            taskname = "{{__('Pickup task')}}";
                            break;
                        case 2:
                            taskname = "{{__('Drop Off task')}}";
                            break;
                        case 3:
                            taskname = "{{__('Appointment')}}";
                            break;
                    }
                    var date = new Date(elem.order_time);
                    var options = {
                        hour12: true
                    };

                    var note  = (elem.note != null && elem.note != '') ? 1 : 0;
                    var image_proof = (elem.proof_image) ? 1 : 0;
                    var sign_proof  = (elem.proof_signature) ? 1 :0;
                    var all = 0;
                    if(elem.note == null && elem.proof_image == null && elem.proof_signature == null){
                        var all = 1;
                    }

                    var html = '<div class="col-md-12 all-remove">'+
                                                '<div class="task-card">'+
                                                    '<div class="p-2 assigned-block bg-transparent""><h5>'+ taskname +'</h5>'+
                                                            '<div class="row">';
                    if (image_proof == 1) {
                    html  = html+'<div class="col-md-4">'+
                            '<label class="mb-3">'+"{{__('Image')}}"+'</label>'+
                            '<div class="status-wrap-block">'+
                                '<div class="image-wrap-sign">'+
                                    '<a data-fancybox="images" href="'+ elem.proof_image +'"><img src="https://imgproxy.royodispatch.com/insecure/fit/400/400/sm/0/plain/'+ elem.proof_image +'" alt=""></a>'+
                                '</div>'+
                            '</div>'+
                        '</div>' ;
                        //<a data-fancybox="images" href="https://royodelivery-assets.s3.us-west-2.amazonaws.com/'+ elem.proof_image +'"><img src="https://imgproxy.royodispatch.com/insecure/fit/400/400/sm/0/plain/https://royodelivery-assets.s3.us-west-2.amazonaws.com/'+ elem.proof_image +'" alt=""></a>
                    }

                    if (sign_proof == 1) {
                        html  = html+ '<div class="col-md-4">'+
                                      '<label class="mb-3">'+"{{__('Signature')}}"+'</label>'+
                                       '<div class="status-wrap-block">'+
                                        '<div class="image-wrap-sign">'+
                                      '<a data-fancybox="images" href="'+ elem.proof_signature +'"><img src="https://imgproxy.royodispatch.com/insecure/fit/200/200/sm/0/plain/'+ elem.proof_signature +'" alt=""></a>'+
                                      '</div>'+
                                      '</div>'+
                                      '</div>';

                     }

                    if (note == 1) {
                        html  = html+   '<div class="col-md-4">'+
                                        '<label class="mb-3">'+"{{__('Notes')}}"+'</label>'+
                                        '<div class="status-wrap-block">'+
                                        '<div class="note-wrap">'+
                                            '<span>'+elem.note+'</span>'+
                                             '</div>'+
                                            '</div>'+
                                          '</div>';
                    }

                    if(all == 1){
                        html  = html+   '<div class="col-12 text-center">'+
                                            '<h5>'+"{{__('No Proof Found')}}"+'</h5>'+
                                          '</div>';
                    }



                    html  = html+   '</div>'+
                                    '</div>'+
                                    '</div>'+
                                    '</div>';
                    // $('.all-remove').remove();
                    $(document).find('.new-proofs').append(html);



                });
                $('#task-proofs-modal').modal('show');

            }

        });
    });


    $(document).on('click', '.showaccounting', function() {
        $('#assign_agent').modal('show');
    });

    function round(value, exp) {
        if (typeof exp === 'undefined' || +exp === 0)
            return Math.round(value);

        value = +value;
        exp = +exp;

        if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
            return NaN;

        // Shift
        value = value.toString().split('e');
        value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

        // Shift back
        value = value.toString().split('e');
        return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
    }


       $(".all-driver_check").click(function() {
            if ($(this).is(':checked')) {
                 $(".assign-toggle").removeClass("assign-show");
                $('.single_driver_check').prop('checked', true);
            } else {
                $(".assign-toggle").addClass("assign-show");
                $('.single_driver_check').prop('checked', false);
            }
        });

        $(document).on('change', '.single_driver_check', function() {
            if ($('input:checkbox.single_driver_check:checked').length > 0)
            {
                $(".assign-toggle").removeClass("assign-show");
            }
            else
            {
                $('.all-driver_check').prop('checked', false);
                $(".assign-toggle").addClass("assign-show");
            }
        });

        $('#submit_assign_agent').on('submit', function(e) {
            e.preventDefault();
            var name = $('#name').val();
            var agent_id = $('#agent_id').val();
            var order_id = [];
        //     $.each($("input[name='driver_id']:checked"), function(){
        //         order_id.push($(this).val());
        //    });
            $('.single_driver_check:checked').each(function(i){
                order_id[i] = $(this).val();
            });
            if (order_id.length == 0) {

                $("#add-assgin-agent-model .close").click();
                return;
            }
            $.ajax({
                type: "POST",
                url: '{{route("assign.agent")}}',
                data: {_token: CSRF_TOKEN, orders_id: order_id, agent_id: agent_id},
                success: function( msg ) {
                    location.reload();
                },
                error: function(errors){
                    location.reload();
                }
            });
        });

        $('#submit_assign_date').on('submit', function(e) {
            e.preventDefault();
            var name = $('#name').val();
            var newdate = $('#datetime-datepicker').val();
            if(newdate!="")
            {
                var order_id = [];

                $('.single_driver_check:checked').each(function(i){
                    order_id[i] = $(this).val();
                });
                if (order_id.length == 0) {

                    $("#add-assgin-date-model .close").click();
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: '{{route("assign.date")}}',
                    data: {_token: CSRF_TOKEN, orders_id: order_id, newdate: newdate},
                    success: function( msg ) {
                        location.reload();
                    }
                });
            }
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

        $(document).on('click', '.mdi-delete', function() {
            var r = confirm("{{__('Are you sure?')}}");
            if (r == true) {
               var taskid = $(this).attr('taskid');
               $('form#taskdelete'+taskid).submit();

            }
        });

        function submitProductImportForm() {
            var form = document.getElementById('submit_bulk_upload_task');
            var formData = new FormData(form);
            var data_uri = "{{route('tasks.importCSV')}}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                headers: {
                    Accept: "application/json"
                },
                url: data_uri,
                data: formData,
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
                            $("#upload-bulk-tasks .close").click();
                            location.reload();
                    } else {
                        $("#upload-bulk-tasks .show_all_error.invalid-feedback").show();
                        $("#upload-bulk-tasks .show_all_error.invalid-feedback").text(response.message);
                    }
                },
                beforeSend: function() {

                    $(".loader_box").show();
                },
                complete: function() {
                    $(".loader_box").hide();
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                
                }
            });
        }

</script>

