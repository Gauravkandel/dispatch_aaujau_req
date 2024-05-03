<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", ".nav-link", function() {
            let rel = $(this).data('rel');
            // console.log(rel);
            let status = $(this).data('status');
            initDataTable(rel, status);
            setTimeout(function() {
                $('#' + rel).DataTable().ajax.reload();
            }, 500);
        });

        // initDataTable();
        setTimeout(function() {
            $('#active-vendor').trigger('click');
        }, 200);

        $(document).on("change", "#geo_filter", function() {
            let rel = $('.nav-link.active').data('rel');
            let status = $('.nav-link.active').data('status');
            initDataTable(rel, status);
            setTimeout(function() {
                $('#' + rel).DataTable().ajax.reload();
            }, 500);
        });

        $(document).on("change", "#tag_filter", function() {
            let rel = $('.nav-link.active').data('rel');
            let status = $('.nav-link.active').data('status');
            initDataTable(rel, status);
            setTimeout(function() {
                $('#' + rel).DataTable().ajax.reload();
            }, 500);
        });

        function padDigits(number, digits) {
            return Array(Math.max(digits - String(number).length + 1, 0)).join(0) + number;
        }

        function initDataTable(table, status) {
            var edit_agent_route = "{{ route('agent.edit', ':id') }}";
            var geo_filter = $("#geo_filter").val();
            var tag_filter = $("#tag_filter").val();
            var columnsDynamic =   [{
                    data: 'id',
                    name: 'id',
                    orderable: true,
                    searchable: true,
                    "mRender": function(data, type, full) {
                        return padDigits(full.id, 4);
                    }
                },
                // {
                //     data: 'uid',
                //     name: 'uid',
                //     orderable: true,
                //     searchable: true
                // },
                {
                    data: 'profile_picture',
                    name: 'profile_picture',
                    orderable: true,
                    searchable: false,
                    "mRender": function(data, type, full) {
                        var is_available_icon = (full.is_available == 1) ? '<i class="fa fa-circle agent-status" aria-hidden="true" style="color:green"></i>' : '<i class="fa fa-circle agent-status" aria-hidden="true" style="color:red"></i>'
                        return is_available_icon + '<img alt="' + full.id + '" src="' + full.profile_picture + '" width="40">';
                    }
                },
                {
                    data: 'name',
                    name: 'name',
                    orderable: true,
                    searchable: false,
                    "mRender": function(data, type, full) {
                        return '<div class="edit-icon-div"><a href="'+edit_agent_route.replace(":id", full.id)+'" class="child-name editIcon" agentId="'+full.id+'">'+full.name+'</a><a href="'+edit_agent_route.replace(":id", full.id)+'" class="child-icon editIcon d-none"  agentId="'+full.id+'"> <i class="mdi mdi-square-edit-outline"></i></a></div>';
                    }
                },
                {
                    data: 'phone_number',
                    name: 'phone_number',
                    orderable: true,
                    searchable: false
                },
                {
                    data: 'type',
                    name: 'type',
                    orderable: true,
                    searchable: false
                },
                {
                    data: 'team',
                    name: 'team',
                    orderable: true,
                    searchable: false
                },
                // {
                //     data: 'vehicle_type_id',
                //     name: 'vehicle_type_id',
                //     orderable: true,
                //     searchable: false,
                //     "mRender": function(data, type, full) {
                //         return '<img alt="" style="width: 80px;" src="' + full.vehicle_type_id + '">';
                //     }
                // },
                {
                    data: 'cash_to_be_collected',
                    name: 'cash_to_be_collected',
                    orderable: true,
                    searchable: false
                },
                {
                    data: 'driver_cost',
                    name: 'driver_cost',
                    orderable: true,
                    searchable: false
                },
                {
                    data: 'cr',
                    name: 'cr',
                    orderable: true,
                    searchable: false
                },
                {
                    data: 'dr',
                    name: 'dr',
                    orderable: true,
                    searchable: false
                },
                {
                    data: 'pay_to_driver',
                    name: 'pay_to_driver',
                    orderable: true,
                    searchable: false
                },
                {
                    data: 'subscription_plan',
                    name: 'subscription_plan',
                    orderable: true,
                    searchable: false
                },
                {
                    data: 'subscription_expiry',
                    name: 'subscription_expiry',
                    orderable: true,
                    searchable: false
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'updated_at',
                    name: 'updated_at',
                    orderable: true,
                    searchable: true
                },
                // {
                //     data: 'is_approved',
                //     name: 'is_approved',
                //     orderable: false,
                //     searchable: false,
                //     "mRender": function(data, type, full) {
                //         var check = (full.is_approved == 1) ? 'checked' : '';
                //         return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input agent_approval_switch" ' + check + ' id="customSwitch_' + full.id + '" data-id="' + full.id + '"><label class="custom-control-label" for="customSwitch_' + full.id + '"></label></div>';
                //     }
                // },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ];
            if (status == 1) {
                var domRef = '<"toolbar">Bfrtip';
                var btnObj = [{
                    className: 'btn btn-success waves-effect waves-light',
                    text: '<span class="btn-label"><i class="mdi mdi-export-variant"></i></span>{{__("Export CSV")}}',
                    action: function(e, dt, node, config) {
                        window.location.href = "{{ route('agents.export') }}";
                    }
                }];
            } else if (status == 0 || status == 2) {
                var domRef = '<"toolbar">Brtip';
                var btnObj = [];
            }
            var table_dy = $('#' + table).DataTable({
                "dom": domRef,
                "scrollX": true,
                "destroy": true,
                "serverSide": true,
                "responsive": true,
                "processing": true,
                "iDisplayLength": 20,
                language: {
                    search: "",
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    },
                    searchPlaceholder: "{{__('Search '.getAgentNomenclature())}}",
                    'loadingRecords': '&nbsp;',
                    //'processing': '<div class="spinner"></div>'
                    'processing':function(){
                        spinnerJS.showSpinner();
                        spinnerJS.hideSpinner();
                    }
                },
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                },
                buttons: btnObj,
                ajax: {
                    url: "{{url('agent/filter')}}",
                    data: function(d) {
                        d.search = $('input[type="search"]').val();
                        d.imgproxyurl = '{{$imgproxyurl}}';
                        d.geo_filter = geo_filter;
                        d.tag_filter = tag_filter;
                        d.status = status;
                    }
                },
                columns:columnsDynamic
            });
            if(status ==2 || status ==0){
                table_dy.column(-2).visible(false);
            }


        }

        var tagList = "{{$showTag}}";
        tagList = tagList.split(',');

        function makeTag() {
            $('.myTag1').tagsInput({
                'autocomplete': {
                    source: tagList
                }
            });
        }
        var mobile_number = '';

        $(document).on("change", "#add-agent-modal .xyz", function(e) {
            var phonevalue = $('.xyz').val();
            $("#countryCode").val(mobile_number.getSelectedCountryData().dialCode);
        });

        function phoneInput() {
            var input = document.querySelector(".xyz");

            var mobile_number_input = document.querySelector(".xyz");
            mobile_number = window.intlTelInput(mobile_number_input, {
                separateDialCode: true,
                hiddenInput: "full_number",
                initialCountry: '{{$selectedCountryCode}}',
                utilsScript: "{{ asset('telinput/js/utils.js') }}",
            });

        }

        $(document).on("click", ".openModal", function(e) {
            $('#add-agent-modal').modal({
                backdrop: 'static',
                keyboard: false
            });
            makeTag();

            phoneInput();
        });

        jQuery('#onfoot').click();

        $(document).on('click', '.click', function() {
            var radi = $(this).find('input[type="radio"]');
            radi.prop('checked', true);
            var check = radi.val();
            var act = radi.attr('act');
            switch (check) {
                case "1":
                    $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk_blue.png') }}");
                    $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle.png') }}");
                    $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike.png') }}");
                    $("#cars_" + act).attr("src", "{{ asset('assets/icons/car.png') }}");
                    $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck.png') }}");
                    $("#autos_" + act).attr("src", "{{ asset('assets/icons/auto.png') }}");
                    break;
                case "2":
                    $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk.png') }}");
                    $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle_blue.png') }}");
                    $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike.png') }}");
                    $("#cars_" + act).attr("src", "{{ asset('assets/icons/car.png') }}");
                    $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck.png') }}");
                    $("#autos_" + act).attr("src", "{{ asset('assets/icons/auto.png') }}");
                    break;
                case "3":
                    $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk.png') }}");
                    $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle.png') }}");
                    $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike_blue.png') }}");
                    $("#cars_" + act).attr("src", "{{ asset('assets/icons/car.png') }}");
                    $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck.png') }}");
                    $("#autos_" + act).attr("src", "{{ asset('assets/icons/auto.png') }}");
                    break;
                case "4":
                    $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk.png') }}");
                    $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle.png') }}");
                    $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike.png') }}");
                    $("#cars_" + act).attr("src", "{{ asset('assets/icons/car_blue.png') }}");
                    $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck.png') }}");
                    $("#autos_" + act).attr("src", "{{ asset('assets/icons/auto.png') }}");
                    break;
                case "5":
                    $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk.png') }}");
                    $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle.png') }}");
                    $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike.png') }}");
                    $("#cars_" + act).attr("src", "{{ asset('assets/icons/car.png') }}");
                    $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck_blue.png') }}");
                    $("#autos_" + act).attr("src", "{{ asset('assets/icons/auto.png') }}");
                    break;
                case "6":
                    $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk.png') }}");
                    $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle.png') }}");
                    $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike.png') }}");
                    $("#cars_" + act).attr("src", "{{ asset('assets/icons/car.png') }}");
                    $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck.png') }}");
                    $("#autos_" + act).attr("src", "{{ asset('assets/icons/auto_blue.png') }}");
                    break;
            }
        });

        /* Get agent by ajax */
        $(document).on("click", ".editIcon", function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();

            var uid = $(this).attr('agentId');

            $.ajax({
                type: "get",
                url: "<?php echo url('agent'); ?>" + '/' + uid + '/edit',
                data: '',
                dataType: 'json',
                success: function(data) {
                    $('#edit-agent-modal #editCardBox').html(data.html);
                    $('#edit-agent-modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    makeTag();
                    phoneInput();
                    $('.dropify').dropify();
                    var imgs = $('#profilePic').attr('showImg');

                    $('#profilePic').attr("data-default-file", imgs);
                    $('#profilePic').dropify();
                    $('').dropify();
                },
                error: function(data) {
                    // console.log('data2');
                }
            });
        });

        $(document).on("click", ".viewIcon", function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();

            var uid = $(this).attr('agentId');

            $.ajax({
                type: "get",
                url: "<?php echo url('agent'); ?>" + '/' + uid + '/show',
                data: '',
                dataType: 'json',
                success: function(data) {
                    $('#view-agent-modal #viewCardBox').html(data.html);
                    $('#view-agent-modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    makeTag();
                    phoneInput();
                    //$('.dropify').dropify();
                    // var imgs = $('#profilePic').attr('showImg');

                    // $('#profilePic').attr("data-default-file", imgs);
                    // $('#profilePic').dropify();
                    // $('').dropify();
                },
                error: function(data) {
                    // console.log('data2');
                }
            });
        });

        /* add Team using ajax*/
        
        $(document).on("submit", "#submitAgent", function(e) {
            e.preventDefault();
            // $(document).on('click', '.submitAgentForm', function() {
            var form = document.getElementById('submitAgent');
            var formData = new FormData(form);
            var urls = "{{URL::route('agent.store')}}";
            saveTeam(urls, formData, inp = '', modal = 'add-agent-modal');
        });

        /* edit Team using ajax*/
        // $(document).on("submit", "#edit-agent-modal #UpdateAgent", function(e) {
        //     e.preventDefault();
        // });


        $(document).on("submit", '#UpdateAgent', function(e) {
            e.preventDefault();
            var form = document.getElementById('UpdateAgent');
            var formData = new FormData(form);
            var urls = document.getElementById('agent_id').getAttribute('url');
            saveTeam(urls, formData, inp = 'Edit', modal = 'edit-agent-modal');
            // console.log(urls);
        });

        function saveTeam(urls, formData, inp = '', modal = '') {

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


        /* Get agent by ajax */
        $(document).on("click", ".submitpayreceive", function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();


            $.ajax({
                type: "post",
                url: "",
                data: '',
                dataType: 'json',
                success: function(data) {
                    $('#edit-agent-modal #editCardBox').html(data.html);
                    $('#edit-agent-modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    makeTag();
                    //$('.dropify').dropify();
                    var imgs = $('#profilePic').attr('showImg');

                    $('#profilePic').attr("data-default-file", imgs);
                    $('#profilePic').dropify();
                    $('').dropify();
                },
                error: function(data) {
                    // console.log('data2');
                }
            });
        });

        $(document).on("change", ".agent_approval_switch", function(e) {
            var is_approved = $(this).prop('checked') == true ? 1 : 0;
            var agent_id = $(this).data('id');

            $.ajax({
                type: "Post",
                dataType: "json",
                url: "{{ route('agent/approval_status')}}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'is_approved': is_approved,
                    'id': agent_id
                },
                success: function(data) {
                    if (data.status == 1) {
                        $.NotificationApp.send("", data.message, "top-right", "#5ba035", "success");
                    }
                }
            });
        });

        $(document).on("click", ".agent_approval_button", function(e) {
            if (confirm('Are you sure?')) {
                var agent_id = $(this).data('agent_id');
                var status = $(this).data('status');
                var activeTabDetail = $("#top-tab li a.active").data('rel');
                $.ajax({
                    type: "Post",
                    dataType: "json",
                    url: "{{ route('agent/change_approval_status')}}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'status': status,
                        'id': agent_id
                    },
                    success: function(data) {
                        if (data.status == 1) {
                            $('#active_vendor_count').text('('+data.agentIsApproved+')');
                            $('#awaiting_vendor_count').text('('+data.agentNotApproved+')');
                            $('#blocked_vendor_count').text('('+data.agentRejected+')');
                            $.NotificationApp.send("", data.message, "top-right", "#5ba035", "success");
                            setTimeout(function() {
                                $('#' + activeTabDetail).DataTable().ajax.reload();
                            }, 100);
                            
                        }
                    }
                });
            }
        });

    });


    $(document).on('click', '.mdi-delete', function(e) {
      e.preventDefault();
            var r = confirm("Are you sure?");
            if (r == true) {
               var agentid = $(this).attr('agentid');
               $('form#agentdelete'+agentid).submit();
            }
    });
</script>
