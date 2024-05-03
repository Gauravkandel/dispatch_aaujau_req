@extends('layouts.vertical', ['title' => 'Pricing Rules'])

@section('css')
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />


    <!-- for File Upload -->

    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/css/intlTelInput.css'>
    <link href="{{ asset('assets/libs/nestable2/nestable2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        // workaround
        .intl-tel-input {
            display: table-cell;
        }

        .inner-div {
            width: 50%;
            float: left;
        }

        .intl-tel-input .selected-flag {
            z-index: 4;
        }

        .intl-tel-input .country-list {
            z-index: 5;
        }

        .input-group .intl-tel-input .form-control {
            border-top-left-radius: 4px;
            border-top-right-radius: 0;
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 0;
        }

    </style>
@endsection

@section('content')
    <!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{__("Pricing Rules")}}</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">

        {{-- <div class="col-md-2 col-12">
            <div class="card-box">
                <div class="">
                    <div class="" id="nestable_list_1" style="display: none">
                    </div>
                </div><!-- end col -->

                <div class="">
                    <h4 class="header-title mt-3 mt-md-0 small-mt-0">{{__('Set Priority')}}</h4>
                    <div class="custom-dd dd" id="nestable_list_2">
                        @if(!empty($priority))
                        <ol class="dd-list" id="priority">
                            <li class="dd-item" data-id="{{ $priority->first }}">
                                <div class="dd-handle">
                                    {{ $priority->first }}
                                </div>
                            </li>

                            <li class="dd-item" data-id="{{ $priority->second }}">
                                <div class="dd-handle">
                                    {{ $priority->second }}
                                </div>
                            </li>
                            <li class="dd-item" data-id="{{ $priority->third }}">
                                <div class="dd-handle">
                                    {{ $priority->third }}
                                </div>
                            </li>
                            <li class="dd-item" data-id="{{ $priority->fourth }}">
                                <div class="dd-handle">
                                    {{ $priority->fourth }}
                                </div>
                            </li>
                        </ol>
                        @endif
                    </div>
                </div> <!-- end col -->
                

            </div> <!-- end card-box -->
        </div> --}}
        
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <div class="text-sm-left">
                                @if (\Session::has('success'))
                                    <div class="alert alert-success">
                                        <span>{!! \Session::get('success') !!}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add Pricing Rules")}}</button>

                            <!--<a href="{{ route('pricing-rules.create') }}"
                                class="btn btn-blue waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>
                                Add Pricing Rules</a> -->
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100" id="pricing-datatable">
                            <thead>
                                <tr>
                                    <th>{{__("Name")}}</th>
                                    <th>{{__("Base Price")}}</th>
                                    <th>{{__("Base Duration")}}</th>
                                    <th>{{__("Base Distance")}}</th>
                                   {{--<th>{{__("Base Waiting")}}</th>--}}
                                    <th style="width: 85px;">{{__("Action")}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pricing as $price)
                                    <tr>
                                        <td>
                                            {{ $price->name }}
                                        </td>
                                        <td>
                                            {{ $price->base_price }}
                                        </td>
                                        <td>
                                            {{ $price->base_duration }}
                                        </td>
                                        <td>
                                            {{ $price->base_distance }}
                                        </td>
                                       {{-- <td>
                                            {{ $price->base_waiting }}
                                        </td>--}}
                                        

                                        <td>
                                            @if($price->is_default == 0 || Auth::user()->is_superadmin == 1)
                                                <div class="form-ul" style="width: 60px;">
                                                <div class="inner-div"> <a href="#" href1="{{ route('pricing-rules.edit', $price->id) }}" class="action-icon editIcon" priceId="{{$price->id}}"> <i class="mdi mdi-square-edit-outline"></i></a> 
                                                </div>
                                            @endif    
                                                @if($price->is_default == 0)
                                                <div class="inner-div">
                                                    <form method="POST" action="{{route('pricing-rules.destroy', $price->id)}}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary-outline action-icon" onclick="return confirm('{{__('Are you sure?')}}');"> <i class="mdi mdi-delete"></i></button>
    
                                                        </div>
                                                    </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->

    </div>
</div>

@include('pricing-rules.price-modal')

@endsection

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.js"></script>

<script src="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/clockpicker/clockpicker.min.js')}}"></script>
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script> 
<script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

<script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.js') }}"></script>

<script src="{{ asset('assets/js/storeAgent.js') }}"></script>
<script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/9.0.10/js/intlTelInput.js"></script>

<script src="{{asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js')}}"></script>
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-pickers.init.js') }}"></script>


<script>

    /* $(document).on('click', '.selectpicker', function( event ) {
        event.stopPropagation();
    });  */


    $(document).ready(function() {
        clockUpdate();
        setInterval(clockUpdate, 1000);

        $('#pricing-datatable').DataTable({
            language: {
                    search: "",
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    },
                    searchPlaceholder: "{{__('Search')}}",
                    'loadingRecords': '&nbsp;',
                    //'processing': '<div class="spinner"></div>'
                    'processing':function(){
                        spinnerJS.showSpinner();
                        spinnerJS.hideSpinner();
                    }
                },
            });
    });

    function clockUpdate() {
        @if($client->getPreference->time_format == 12)
            var date = new Date().toLocaleString('en-US', { timeZone: '{{$timezone}}', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true});
        @else
            var date = new Date().toLocaleString('en-US', { timeZone: '{{$timezone}}', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: false});
        @endif
        $('.digital-clock1').text("Current Time: "+date) 
    }

    function runPicker1(){
        $('#geo_id, #team_id, #team_tag_id, #driver_tag_id, #geo_id_edit, #team_id_edit, #team_tag_id_edit, #driver_tag_id_edit').select2({
            placeholder: "Select an option",
            allowClear: true
        });
        
        @if($client->getPreference->time_format == 12)
            $("[id^='price_starttime_'], [id^='price_endtime_'], [id^='edit_price_starttime_'], [id^='edit_price_endtime_']").flatpickr({enableTime:!0,noCalendar:!0,dateFormat:"H:i", static: true});
        @else
            $("[id^='price_starttime_'], [id^='price_endtime_'], [id^='edit_price_starttime_'], [id^='edit_price_endtime_']").flatpickr({enableTime:!0,noCalendar:!0,dateFormat:"H:i",time_24hr:!0, static: true});
        @endif

        // select all for add form
        $(document).on('click', '#select_geo_all', function(){
            $("#geo_id").find('option').prop("selected",true);
            $("#geo_id").trigger('change');
        });

        $(document).on('click', '#select_team_all', function(){
            $("#team_id").find('option').prop("selected",true);
            $("#team_id").trigger('change');
        });

        $(document).on('click', '#select_team_tag_all', function(){
            $("#team_tag_id").find('option').prop("selected",true);
            $("#team_tag_id").trigger('change');
        });

        $(document).on('click', '#select_driver_tag_all', function(){
            $("#driver_tag_id").find('option').prop("selected",true);
            $("#driver_tag_id").trigger('change');
        });

        // select all for edit form
        $(document).on('click', '#select_geo_edit_all', function(){
            $("#geo_id_edit").find('option').prop("selected",true);
            $("#geo_id_edit").trigger('change');
        });

        $(document).on('click', '#select_team_edit_all', function(){
            $("#team_id_edit").find('option').prop("selected",true);
            $("#team_id_edit").trigger('change');
        });

        $(document).on('click', '#select_team_tag_edit_all', function(){
            $("#team_tag_id_edit").find('option').prop("selected",true);
            $("#team_tag_id_edit").trigger('change');
        });

        $(document).on('click', '#select_driver_tag_edit_all', function(){
            $("#driver_tag_id_edit").find('option').prop("selected",true);
            $("#driver_tag_id_edit").trigger('change');
        });

        $("#add-pricing-modal .apply_timetable").on("change", function() {
            if($(this).is(":checked")){
                $("#add-pricing-modal .timetable_div").show();
            }else{
                $("#add-pricing-modal .timetable_div").hide();
            }
        });

        $("#editCardBox .apply_timetable1").on("change", function() {
            if($(this).is(":checked")){
                $("#editCardBox .timetable_div").show();
            }else{
                $("#editCardBox .timetable_div").hide();
            }
        });

        
        $("#edit-pricing-modal .apply_timetable").change();
        $("#editCardBox .apply_timetable1").change();
    }

    $('.openModal').click(function(){
        $('#add-pricing-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
        runPicker1();
    });

    // click event of add new time frame button
    $(document).on('click', '.add_sub_pricing_row', function(){
        var rowid = $(this).attr("data-id");
        var id = $("#no_of_time_"+rowid).val();
        if($("#price_starttime_"+rowid+"_"+id).val()!='' && $("#price_endtime_"+rowid+"_"+id).val()!='')
        {
            id = parseInt(id) + 1;
            //new time frame row creation below day row
            $("#timeframe_tbody_"+rowid).append('<tr id="timeframe_row_'+rowid+'_'+id+'"><td></td><td><input id="price_starttime_'+rowid+'_'+id+'" class="form-control" autocomplete="off" placeholder="00:00" name="price_starttime_'+rowid+'_'+id+'" type="text" readonly="readonly"></td><td><input id="price_endtime_'+rowid+'_'+id+'" class="form-control" autocomplete="off" placeholder="00:00" name="price_endtime_'+rowid+'_'+id+'" type="text" readonly="readonly"></td><td style="text-align:center;"><span data-id="pricruledelspan_'+rowid+'_'+id+'" class="del_pricrule_span"><img style="filter: grayscale(.5);" src="{{asset("assets/images/ic_delete.png")}}"  alt=""></span></td></tr>');
            $("#no_of_time_"+rowid).val(id);
            @if($client->getPreference->time_format == 12)
                $("#price_starttime_"+rowid+"_"+id+", #price_endtime_"+rowid+"_"+id).flatpickr({enableTime:!0,noCalendar:!0,dateFormat:"H:i", static: true});
            @else
                $("#price_starttime_"+rowid+"_"+id+", #price_endtime_"+rowid+"_"+id).flatpickr({enableTime:!0,noCalendar:!0,dateFormat:"H:i",time_24hr:!0, static: true});
            @endif
        }else{
            //empty previous row fields warning
            var color = 'orange';var heading="Warning!";
            $.toast({ 
            heading:heading,
            text : "Please fill previous data first.", 
            showHideTransition : 'slide', 
            bgColor : color,              
            textColor : '#eee',            
            allowToastClose : true,      
            hideAfter : 5000,            
            stack : 5,                   
            textAlign : 'left',         
            position : 'top-right'      
            });
        }
    });

    

    $(document).on('click', '.del_pricrule_span', function(){
        Swal.fire({
                title: "Are you sure?",
                text:"You want to delete this row ?.",
                showCancelButton: true,
                confirmButtonText: 'Ok',
            }).then((result) => {
                if(result.value)
                {
                    $(this).closest("tr").remove();
                }
                
            });
    });

    $(".editIcon").click(function (e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
       
        var pid = $(this).attr('priceId');

        $.ajax({
            type: "get",
            url: "<?php echo url('pricing-rules'); ?>" + '/' + pid + '/edit',
            data: '',
            dataType: 'json',
            success: function (data) {

                console.log('data');

                $('#edit-price-modal #editCardBox').html(data.html);
                $('#edit-price-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                
                var elems = Array.prototype.slice.call(document.querySelectorAll('.apply_timetable1'));
                    elems.forEach(function(html) {
                    var switchery =new Switchery(html);
                });
                runPicker1();

                $(document).on('click', '.add_edit_sub_pricing_row', function(){
                    var rowid = $(this).attr("data-id");
                    var id = $("#edit_no_of_time_"+rowid).val();
                    if($("#edit_price_starttime_"+rowid+"_"+id).val()!='' && $("#edit_price_endtime_"+rowid+"_"+id).val()!='')
                    {
                        id = parseInt(id) + 1;
                        //new time frame row creation below day row
                        $("#timeframe_edit_tbody_"+rowid).append('<tr id="timeframe_edit_row_'+rowid+'_'+id+'"><td></td><td><input id="edit_price_starttime_'+rowid+'_'+id+'" class="form-control" autocomplete="off" placeholder="00:00" name="edit_price_starttime_'+rowid+'_'+id+'" type="text" readonly="readonly"></td><td><input id="edit_price_endtime_'+rowid+'_'+id+'" class="form-control" autocomplete="off" placeholder="00:00" name="edit_price_endtime_'+rowid+'_'+id+'" type="text" readonly="readonly"></td><td style="text-align:center;"><span data-id="pricruledelspan_'+rowid+'_'+id+'" class="del_edit_pricrule_span"><img style="filter: grayscale(.5);" src="{{asset("assets/images/ic_delete.png")}}"  alt=""></span></td></tr>');
                        $("#edit_no_of_time_"+rowid).val(id);
                        @if($client->getPreference->time_format == 12)
                            $("#edit_price_starttime_"+rowid+"_"+id+", #edit_price_endtime_"+rowid+"_"+id).flatpickr({enableTime:!0,noCalendar:!0,dateFormat:"H:i", static: true});
                        @else
                            $("#edit_price_starttime_"+rowid+"_"+id+", #edit_price_endtime_"+rowid+"_"+id).flatpickr({enableTime:!0,noCalendar:!0,dateFormat:"H:i",time_24hr:!0, static: true});
                        @endif
                    }else{
                        //empty previous row fields warning
                        var color = 'orange';var heading="Warning!";
                        $.toast({ 
                        heading:heading,
                        text : "Please fill previous data first.", 
                        showHideTransition : 'slide', 
                        bgColor : color,              
                        textColor : '#eee',            
                        allowToastClose : true,      
                        hideAfter : 5000,            
                        stack : 5,                   
                        textAlign : 'left',         
                        position : 'top-right'      
                        });
                    }
                });

                $(document).on('click', '.del_edit_pricrule_span', function(){
                    Swal.fire({
                            title: "Are you sure?",
                            text:"You want to delete this row ?.",
                            showCancelButton: true,
                            confirmButtonText: 'Ok',
                        }).then((result) => {
                            if(result.value)
                            {
                                $(this).closest("tr").remove();
                            }
                            
                        });
                });

            },
            error: function (data) {
                console.log('data2');
            }
        });
    });

    $(document).on('click', '.submitEditForm', function(){ console.log('ad');
        document.getElementById("edit_price").submit();
    });

</script>

@endsection
