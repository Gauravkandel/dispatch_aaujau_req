@extends('layouts.vertical', ['title' => getAgentNomenclature() ])

@section('css')
<link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
<!-- for File Upload -->

<link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('telinput/css/intlTelInput.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('telinput/css/demo.css') }}" type="text/css">
<style>
    .cursors {
        cursor: move;
        margin-right: 0rem !important;
    }

    .table th,
    .table td,
    .table td {
        display: table-cell !important;
    }

    .footer {
        z-index: 3;
    }

    #agent-listing_processing {
        position: absolute !important;
        background: transparent !important;
        top: 60%;
        transform: translateY(-50%) !important;
        left: 0;
        right: 0;
        z-index: 1;
    }

    .dt-buttons.btn-group.flex-wrap {
        float: right;
        margin: 5px 0 10px 15px;
    }

    div#agent-listing_filter {
        padding-top: 5px;
    }

    .dataTables_filter label {
        width: 25%;
    }

    .dataTables_filter label .form-control {
        height: 37px;
        font-size: 16px;
    }

    .dt-buttons .btn.btn-secondary,
    .dt-buttons .btn.btn-secondary:focus,
    .dt-buttons .btn.btn-secondary:active {
        border-radius: 5px;
        background: #6658ddd6 !important;
    }

    .btn-label,
    .btn-label:focus,
    .btn-label:active {
        background-color: rgb(102 88 221) !important;
    }

    .dataTables_scrollHead thead th {
        cursor: pointer;
    }

    .nagtive-margin {
        margin-top: -57px;
    }
</style>
@endsection
@php
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp
@section('content')
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="text-sm-left">
                    @if (\Session::has('success'))
                    <div class="alert mt-2 mb-0 alert-success">
                        <span>{!! \Session::get('success') !!}</span>
                    </div>
                    @endif
                    @if (\Session::has('error'))
                    <div class="alert mt-2 mb-0 alert-danger">
                        <span>{!! \Session::get('error') !!}</span>
                    </div>
                    @endif
                    @if ( ($errors) && (count($errors) > 0) )
                    <div class="alert mt-2 mb-0 alert-danger">
                        <ul class="m-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right"></div>
                    <h4 class="page-title">{{ __("Payout Requests") }}</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <form method="POST" id="form_auto_payout" action="{{route('preference', Auth::user()->code)}}">
                                        @csrf
                                        <input type="hidden" name="autopay_submit" id="autopay_submit" value="submit"/>
                                    <!-- <div class="custom-control custom-switch mb-2">
                                        <input type="checkbox" class="custom-control-input auto_payout" id="customSwitch" name="auto_payout" {{ (isset($preference) && $preference->auto_payout =="1")? "checked" : "" }}>
                                        <label class="custom-control-label" for="customSwitch"></label>
                                    </div> -->
                                    <div class="mb-2">
                                        <input type="checkbox" data-plugin="switchery" id="auto_payout" name="auto_payout" class="switchery" data-color="#039cfd" {{ (isset($preferences) && $preferences->auto_payout =="1")? "checked" : "" }}/>
                                    </div>
                                    <p class="text-muted font-15 mb-0">{{__("Auto Payout")}}</p>
                                    </form>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="fas fa-money-check-alt text-primary"></i>
                                        <span data-plugin="counterup" id="total_earnings_by_agents">{{$total_order_value}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{ __('Total Order Value') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="fas fa-money-check-alt text-primary"></i>
                                        <span data-plugin="counterup" id="total_paid_payouts">{{$pending_payout_value}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0"> {{ __('Pending Payout Value') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="fas fa-money-check-alt text-primary"></i>
                                        <span data-plugin="counterup" id="total_pending_payouts">{{$completed_payout_value}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{ __('Completed Payout Value') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-lg-12 tab-product pt-0">
                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pending-payouts" data-toggle="tab" href="#pending_payouts" role="tab" aria-selected="false" data-rel="pending_payouts_datatable" data-status="0">
                            <i class="icofont icofont-man-in-glasses"></i>{{ __('Pending') }}<sup class="total-items" id="pending_payouts_count">({{$pending_payout_count}})</sup>
                        </a>
                        <div class="material-border"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="completed-payouts" data-toggle="tab" href="#completed_payouts" role="tab" aria-selected="true" data-rel="completed_payouts_datatble" data-status="1">
                            <i class="icofont icofont-ui-home"></i>{{ __('Completed') }}<sup class="total-items">({{$completed_payout_count}})</sup>
                        </a>
                        <div class="material-border"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="failed-payouts" data-toggle="tab" href="#failed_payouts" role="tab" aria-selected="true" data-rel="failed_payouts_datatble" data-status="2">
                            <i class="icofont icofont-ui-home"></i>{{ __('Failed') }}<sup class="total-items">({{$failed_payout_count}})</sup>
                        </a>
                        <div class="material-border"></div>
                    </li>
                </ul>

                {{-- <div class="col-sm-4 payout-toggle">
                    <button type="button" class="btn btn-info payout_all_agents" data-toggle="modal" data-target="#payout-all-agents-model" data-backdrop="static" data-keyboard="false">{{__("Payout")}}</button>
                </div> --}}

                <div class="tab-content nav-material pt-0" id="top-tabContent">
                    <div class="tab-pane fade past-order show active" id="pending_payouts" role="tabpanel" aria-labelledby="pending-payouts">
                        <div class="row">
                            <div class="col-12">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            {{-- <form name="saveOrder" id="saveOrder"> @csrf</form> --}}

                                            <table class="table table-centered table-nowrap table-striped" id="pending_payouts_datatable" width="100%">
                                                <thead>
                                                    <tr>
                                                        {{-- <th><input type="checkbox" class="all-agent_check" name="all_agent_id" id="all-agent_check"></th> --}}
                                                        <th>{{ __('Date') }}</th>
                                                        <th>{{ __('Agent') }}</th>
                                                        {{-- <th>{{ _('Requested By') }}</th> --}}
                                                        <th>{{ __('Amount') }}</th>
                                                        <th>{{ __('Payout Type') }}</th>
                                                        <th>{{ __('Bank Detials') }}</th>
                                                        <th class="text-center">{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="pending_payouts_list"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="completed_payouts" role="tabpanel" aria-labelledby="completed-payouts">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap table-striped" id="completed_payouts_datatble" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Date') }}</th>
                                                        <th>{{ __('Agent') }}</th>
                                                        <th>{{ __('Amount') }}</th>
                                                        <th>{{ __('Payout Type') }}</th>
                                                        <th>{{ __('Bank Detials') }}</th>
                                                        <th class="text-center">{{ __('Status') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="completed_payouts_list"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="failed_payouts" role="tabpanel" aria-labelledby="failed-payouts">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap table-striped" id="failed_payouts_datatble" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Date') }}</th>
                                                        <th>{{ __('Agent') }}</th>
                                                        <th>{{ __('Amount') }}</th>
                                                        <th>{{ __('Payout Type') }}</th>
                                                        <th>{{ __('Bank Detials') }}</th>
                                                        <th class="text-center">{{ __('Status') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="failed_payouts_list"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="payout-confirm-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="payout_form_final" method="POST" action="{{url('agent/payout/request/complete')}}">
                @csrf
                <div>
                    <input type="hidden" name="amount" id="payout_amount" value="">
                    <input type="hidden" name="payout_id" id="payout_id" value="">
                    <input type="hidden" name="payout_option_id" id="payout_method" value="">
                </div>
                <div class="modal-body px-3">
                    <div class="row">
                        <h4 class="modal-title">{{__('Are you sure you want to payout')}}
                            <span id="payout-agent"></span> for
                            <span id="payout-amount-final"></span>?
                        </h4>
                    </div>
                </div>
                <div class="modal-footer flex-nowrap justify-content-center align-items-center">
                    <button type="submit" class="btn btn-info waves-effect waves-light">{{__('Continue')}}</button>
                    <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="selected-payout-confirm-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="selectedPayoutConfirmLabel" style="display: none" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body px-3">
                <div class="row">
                    <h4 class="modal-title">{{__('Are you sure you want to payout for selected requests?')}}</h4>
                </div>
            </div>
            <div class="modal-footer flex-nowrap justify-content-center align-items-center">
                <button type="submit" class="btn btn-info waves-effect waves-light payout_all_agents">{{__('Continue')}}</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">{{__('Cancel')}}</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div id="agent-bank-details-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="agentBankDetialsModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">{{__("Bank Details")}} - <span id="agent_name"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body px-3 pt-0 pb-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="beneficiary_name" class="control-label">{{__("Account Holder Name")}}</label>
                            <input type="text" class="form-control" id="beneficiary_name" placeholder="{{__("Account Holder Name")}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="beneficiary_account_number" class="control-label">{{__("Bank Account Number")}}</label>
                            <input type="text" class="form-control" id="beneficiary_account_number" placeholder="{{__("Bank Account Number")}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="beneficiary_ifsc" class="control-label">{{__("IFSC Code")}}</label>
                            <input type="text" class="form-control" id="beneficiary_ifsc" placeholder="{{__("IFSC Code")}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="beneficiary_bank_name" class="control-label">{{__("Bank Name")}}</label>
                            <input type="text" class="form-control" id="beneficiary_bank_name" placeholder="{{__("Bank Name")}}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- @include('agent.modals')
@include('modals.pay-receive') --}}
@endsection

@section('script')

<!-- <script src="{{ asset('assets/js/jquery-ui.min.js') }}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}"> -->
<script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>
<script src="{{ asset('telinput/js/intlTelInput.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />
<script src="{{asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js')}}"></script>
<script>

    $(document).ready(function() {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
            elems.forEach(function(html) {
            var switchery =new Switchery(html);
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        setTimeout(function(){$('#pending-payouts').trigger('click');}, 200);
        $(document).on("click",".nav-link",function() {
            let rel= $(this).data('rel');
            let status= $(this).data('status');
            initDataTable(rel, status);
        });

        $(document).on("change","#auto_payout",function() {
            document.getElementById("form_auto_payout").submit();
        });

        // initDataTable();

        function initDataTable(table, status) {

            if (status == 0) {
                var domRef = '<"toolbar">Bfrtip';
                var btnObj = [{
                    className: 'btn btn-success waves-effect waves-light rounded-pill payout-toggle d-none',
                    text: '<span class="btn-label"><i class="fe-dollar-sign"></i></span>{{__("Payout")}}',
                    action: function(e, dt, node, config) {
                        $("#selected-payout-confirm-modal").modal("show");
                    }
                },
                {
                    className: 'btn btn-success waves-effect waves-light',
                    text: '<span class="btn-label"><i class="mdi mdi-export-variant"></i></span>{{__("Export CSV")}}',
                    action: function(e, dt, node, config) {
                        window.location.href = "{{ route('agents.payout.requests.export') }}";
                    }
                }];
            } else if (status == 1 || status == 2) {
                var domRef = '<"toolbar">Brtip';
                var btnObj = [];
            }

            $('#'+table).DataTable({
                "destroy": true,
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "iDisplayLength": 20,
                "dom": '<"toolbar">Btrip',
                // language: {
                //     search: "",
                //     paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                //     searchPlaceholder: "Search By "+search_text+" Name"
                // },
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                },
                buttons: btnObj,
                ajax: {
                  url: "{{route('agent.payout.requests.filter')}}",
                  data: function (d) {
                    d.status = status;
                //     d.search = $('input[type="search"]').val();
                //     d.date_filter = $('#range-datepicker').val();
                //     d.payment_option = $('#payment_option_select_box option:selected').val();
                  }
                },
                columns: dataTableColumn(status)
                // [
                //     {data: 'date', name: 'date', orderable: false, searchable: false},
                //     {data: 'agentName', name: 'agentName', orderable: false, searchable: false},
                //     // {data: 'requestedBy', name: 'requestedBy', orderable: false, searchable: false},
                //     {data: 'amount', class:'text-center', name: 'amount', orderable: false, searchable: false},
                //     {data: 'type', name: 'type', orderable: false, searchable: false},
                //     {data: 'status', class:'text-center', name: 'status', orderable: false, searchable: false, "mRender":function(data, type, full){
                //         if(full.status == 'Pending'){
                //             return "<button class='btn btn-sm btn-info payout_btn' data-id='"+full.id+"'>Payout</button>";
                //         }else{
                //             return full.status;
                //         }
                //     }},
                // ]
            });
        }

        function dataTableColumn(status){
            if(status == 0){
                return [
                    // {data: 'id', name: 'id', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                    //     return '<input type="checkbox" class="single_agent_check" name="agent_payout_id[]" value="'+full.id+'">';
                    // }},
                    {data: 'date', name: 'date', orderable: false, searchable: false},
                    {data: 'agentName', name: 'agentName', orderable: false, searchable: false},
                    // {data: 'requestedBy', name: 'requestedBy', orderable: false, searchable: false},
                    {data: 'amount', class:'text-center', name: 'amount', orderable: false, searchable: false},
                    {data: 'type', name: 'type', orderable: false, searchable: false},
                    {data: 'bank_account', class:'text-center', name: 'bank_account', orderable: false, searchable: false, "mRender":function(data, type, full){
                        if(full.bank_account != ''){
                            return '<a href="javascript:void(0)" class="view_agent_bank_details" data-id="'+full.bank_account+'">{{__("View")}}</a>';
                        }else{
                            return 'NA';
                        }
                    }},
                    {data: 'status', class:'text-center', name: 'status', orderable: false, searchable: false, "mRender":function(data, type, full){
                        if(full.status == 'Pending'){
                            return "<button class='btn btn-sm btn-info payout_btn' data-id='"+full.id+"' data-payout_method='"+full.payout_option_id+"' data-agent='"+full.aegnt_id+"'>{{__('Payout')}}</button>";
                        }else{
                            return full.status;
                        }
                    }},
                ];
            }else{
                return [
                    {data: 'date', name: 'date', orderable: false, searchable: false},
                    {data: 'agentName', name: 'agentName', orderable: false, searchable: false},
                    // {data: 'requestedBy', name: 'requestedBy', orderable: false, searchable: false},
                    {data: 'amount', class:'text-center', name: 'amount', orderable: false, searchable: false},
                    {data: 'type', name: 'type', orderable: false, searchable: false},
                    {data: 'bank_account', class:'text-center', name: 'bank_account', orderable: false, searchable: false, "mRender":function(data, type, full){
                        if(full.bank_account != ''){
                            return '<a href="javascript:void(0)" class="view_agent_bank_details" data-id="'+full.bank_account+'">{{__("View")}}</a>';
                        }else{
                            return 'NA';
                        }
                    }},
                    {data: 'status', class:'text-center', name: 'status', orderable: false, searchable: false, "mRender":function(data, type, full){
                        if(full.status == 'Pending'){
                            return "<button class='btn btn-sm btn-info payout_btn' data-id='"+full.id+"'>{{__('Payout')}}</button>";
                        }else{
                            return full.status;
                        }
                    }},
                ]
            }
        }

        $(document).delegate(".payout_btn", "click", function(){
            var agent = $(this).closest('tr').find('td:nth-child(2)').text();
            var amount = $(this).closest('tr').find('td:nth-child(3)').text();
            var dataid = $(this).attr('data-id');
            var agent_id = $(this).attr('data-agent');
            var payout_method = $(this).attr('data-payout_method');
            $("#payout-confirm-modal #payout-agent").html('<b>'+agent+'</b>');
            $("#payout-confirm-modal #payout-amount-final").text('{{$currency_symbol}}' + amount);
            $("#payout-confirm-modal #payout_amount").val(amount);
            $("#payout-confirm-modal #payout_id").val(dataid);
            $("#payout-confirm-modal #payout_method").val(payout_method);
            $("#payout-confirm-modal").modal('show');
        });

        // $(".all-agent_check").click(function() {
        //     if ($(this).is(':checked')) {
        //         $(".payout-toggle").removeClass('d-none');
        //         $('.single_agent_check').prop('checked', true);
        //     } else {
        //         $(".payout-toggle").addClass('d-none');
        //         $('.single_agent_check').prop('checked', false);
        //     }
        // });

        // $(document).on('change', '.single_agent_check', function() {
        //     if ($('input:checkbox.single_agent_check:checked').length > 0){
        //         $(".payout-toggle").removeClass('d-none');
        //     }
        //     else{
        //         $('.all-driver_check').prop('checked', false);
        //         $(".payout-toggle").addClass('d-none');
        //     }
        // });

        // $(document).on('click', '.payout_all_agents', function() {
        //     payoutAllAgents();
        // });

        // function payoutAllAgents() {
        //     var payout_id = [];
        //     $('.single_agent_check:checked').each(function(i){
        //         payout_id[i] = $(this).val();
        //     });
        //     if (payout_id.length == 0) {
        //         // $("#add-assgin-agent-model .close").click();
        //         alert('Please select any record');
        //         return;
        //     }
        //     $.ajax({
        //         type: "POST",
        //         url: '{{route("agent.payout.requests.complete.all")}}',
        //         data: {payout_ids: payout_id},
        //         success: function( response ) {
        //             $("#pay-receive-modal").modal('hide');
        //             if (response.status == 'Success') {
        //                 location.reload();
        //             } else {
        //                 alert(response.message);
        //                 // $("#pay-receive-modal .show_all_error.invalid-feedback").show();
        //                 // $("#pay-receive-modal .show_all_error.invalid-feedback").text(response.message);
        //             }
        //         }
        //     });
        // }

        $(document).delegate('.view_agent_bank_details', 'click', function(e) {
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'post',
                url: "{{ route('agent.payout.bank.details') }}",
                data: {'id': id},
                success: function(response) {
                    if(response.status == 'Success'){
                        var data = response.data;
                        $("#agent-bank-details-modal").modal('show');
                        $("#agent-bank-details-modal #agent_name").text(data.agent.name);
                        $("#agent-bank-details-modal #beneficiary_name").val(data.beneficiary_name);
                        $("#agent-bank-details-modal #beneficiary_account_number").val(data.beneficiary_account_number);
                        $("#agent-bank-details-modal #beneficiary_ifsc").val(data.beneficiary_ifsc);
                        $("#agent-bank-details-modal #beneficiary_bank_name").val(data.beneficiary_bank_name);
                    }
                    else{
                        alert(response.message);
                    }
                },
            });
        });
    });

</script>
{{-- @include('agent.pagescript') --}}
@endsection
