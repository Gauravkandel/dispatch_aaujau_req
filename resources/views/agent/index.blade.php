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
    .alFilterLocation button.btn.btn-blue.waves-effect.waves-light.openModal.mr-1 {
        width: auto;
        min-width: auto;
        max-width: inherit;
    }
    .table th,
    .table td,
    .table td {
        display: table-cell !important;
    }

    .footer {
        z-index: 3;
    }
    .table thead th {
    border-top: 0;
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
        display: block;
        overflow: hidden;
    }

    .dataTables_filter label {
        width: auto;
    }

    .dataTables_filter label .form-control {
        height: 37px;
        font-size: 16px;
        margin-left: auto;
        width: auto;
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
    .agent-listing tbody td{
        position: relative;
    }
    .nagtive-margin {
        margin-top: -45px;
    }
    .bootstrap-select .dropdown-menu > .inner{
        overflow-y: scroll!important;
    }
    .bootstrap-select .dropdown-menu.inner{
        overflow-y: hidden!important;
    }


/* agent page css here */
.edit-icon-div {
    position: relative;
}
.edit-icon-div:hover .child-icon.editIcon {
    display: block !important;
    right: -5px;
    position: absolute;
    top: 0;
    border-radius: 2px;
    background: #6658ddf0;
    color: #fff;
    font-size: 14px;
    padding: 0px 2px;
}
.edit-slot-agent {
    max-width: 380px !important;
}
.edit-slot-agent .custom-control-input:checked ~ .custom-control-label::before{
    border-color: #44cf9c !important;
    background-color: #44cf9c !important;
}
.edit-slot-agent .custom-control-input:checked ~ .custom-control-label::after{
    content: "";
    position: absolute;
    top: 6px;
    left: -18px;
    display: table;
    width: 4px;
    height: 8px;
    border: 2px solid #fff;
    border-top-width: 0;
    border-left-width: 0;
    transform: rotate(45deg);
}

p.custom-radio-design {
    display: inline-block;
    vertical-align: middle;
    margin-bottom: 0 !important;
    width: 49%;
}
p.custom-radio-design input {
    height: 20px;
}
p.custom-radio-design label {
    vertical-align: top;
    margin-bottom: 0 !important;
}
.slotForDiv, .weekDays {
    text-align: left;
}
.needs-validation label.control-label {
    text-align: left;
    width: 100%;
    font-size: 14px;
    color: #777;
}
/* 
.custom-radio-design [type="radio"]:checked,
.custom-radio-design [type="radio"]:not(:checked) {
    position: absolute;
    left: -9999px;
}
.custom-radio-design [type="radio"]:checked + label,
.custom-radio-design [type="radio"]:not(:checked) + label
{
    position: relative;
    padding-left: 28px;
    cursor: pointer;
    line-height: 20px;
    display: inline-block;
    color: #666;
}
.custom-radio-design [type="radio"]:checked + label:before,
.custom-radio-design [type="radio"]:not(:checked) + label:before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 18px;
    height: 18px;
    border: 1px solid #ddd;
    border-radius: 100%;
    background: #fff;
}
.custom-radio-design [type="radio"]:checked + label:after,
.custom-radio-design [type="radio"]:not(:checked) + label:after {
    content: '';
    width: 12px;
    height: 12px;
    background: #43bee1;
    position: absolute;
    top: 3px;
    left: 3px;
    border-radius: 100%;
    -webkit-transition: all 0.2s ease;
    transition: all 0.2s ease;
}
.custom-radio-design [type="radio"]:not(:checked) + label:after {
    opacity: 0;
    -webkit-transform: scale(0);
    transform: scale(0);
}
.custom-radio-design [type="radio"]:checked + label:after {
    opacity: 1;
    -webkit-transform: scale(1);
    transform: scale(1);
} */
p.custom-radio-design {
    position: relative;
    text-align: left;
}
.weekDays .checkbox label {
    padding-left: 22px;
}
.weekDays .checkbox input[type=checkbox] {
    position: absolute;
    left: 0;
}
.weekDays .checkbox label::before {
    margin-left: 0;
}
.memo label{
    text-align: left;
    width: 100%;
    font-size: 14px;
    color: #777;
}
.slot_type select{
    width:100%;
    border: 1px solid #ced4da;
    border-radius: 0.2rem;
}
.memo textarea{
    width:100%;
    border: 1px solid #ced4da;
    border-radius: 0.2rem;
    padding: 5px 5px;
    font-size: 0.875rem;
}
.memo textarea:focus{outline: none;}.memo select:focus{outline: none;}
.memo textarea::placeholder{
    font-size: 0.875rem;
    font-weight: 400;
    padding:2px 10px;
    color: #6c757d;
}
.memo select{
    font-size: 0.875rem;
    font-weight: 400;
    padding:2px 10px;
    color: #6c757d;
}
.weekDays .checkbox input[type=checkbox]:checked + label::after {
    left: 24px;
}
.checkbox.checkbox-success.form-check.pl-0.mb-1 {
    width: 49%;
    text-align: left;
    display: inline-block;
}
.swal2-styled{
    height: auto;
}
h2#swal2-title {
    font-size: 18px;
    font-weight: 400;
}
</style>
@endsection
@php
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp
@section('content')
<div class="container-fluid">
    @csrf
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ getAgentNomenclature() }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card widget-inline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-md mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-storefront text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_earnings_by_vendors">{{$agentsCount}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{__('Total')}} {{ __(getAgentNomenclature()) }}</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="fa fa-user-circle text-primary"></i>
                                    <span data-plugin="counterup" id="total_order_count">{{$freelancerCount}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{__('Freelancer')}}</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="fas fa-user text-primary"></i>
                                    <span data-plugin="counterup" id="total_cash_to_collected">{{$employeesCount}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{__('Employees')}}</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="fa fa-address-card text-primary"></i>
                                    <span data-plugin="counterup" id="total_delivery_fees">{{$agentIsApproved}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{__('Approved')}} {{ __(getAgentNomenclature()) }}</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="fa fa-user-times text-primary"></i>
                                    <span data-plugin="counterup" id="total_delivery_fees">{{$agentNotApproved}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{__('Unapproved')}} {{ __(getAgentNomenclature()) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="alFilterLocation">
                        <ul class="p-0 d-flex justify-content-end">
                                <li>
                                    <div class="text-sm-left">
                                        @if (\Session::has('success'))
                                        <div class="alert alert-success">
                                            <span>{!! \Session::get('success') !!}</span>
                                        </div>
                                        @endif
                                    </div>
                                </li>
                                <li>
                                    <select name="geo_filter" id="geo_filter" class="form-control">
                                        <option value="">{{__('Filter by location')}}</option>
                                        @foreach($geos as $geo)
                                            <option value="{{$geo->id}}">{{$geo->name}}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <li>
                                    <select name="tag_filter" id="tag_filter" class="form-control">
                                        <option value="">{{__('Filter by tags')}}</option>
                                        @foreach($tags as $tag)
                                            <option value="{{$tag->id}}">{{$tag->name}}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <li class="d-flex">
                                    <button type="button" class="btn btn-blue waves-effect waves-light openModal mr-1" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add")}} {{ getAgentNomenclature() }}</button>
                                    <button type="button" class="btn btn-success waves-effect waves-light saveaccounting" data-toggle="modal" data-target="#pay-receive-modal" data-backdrop="static" data-keyboard="false">{{__("Pay")}} / {{__("Receive")}}</button>
                                </li>
                            </ul>
                    </div>
                    <ul class="nav nav-tabs nav-material alNavTopMinus" id="top-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="active-vendor" data-toggle="tab" href="#active_vendor" role="tab" aria-selected="false" data-rel="agent-listing" data-status="1">
                                <i class="icofont icofont-man-in-glasses"></i>{{ __('Active') }}<sup class="total-items" id="active_vendor_count">({{$agentIsApproved}})</sup>
                            </a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="awaiting-vendor" data-toggle="tab" href="#awaiting_vendor" role="tab" aria-selected="true" data-rel="awaiting_approval_agent_datatable" data-status="0">
                                <i class="icofont icofont-ui-home"></i>{{ __('Awaiting Approval') }}<sup class="total-items" id="awaiting_vendor_count">({{$agentNotApproved}})</sup>
                            </a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="block-vendor" data-toggle="tab" href="#block_vendor" role="tab" aria-selected="false" data-rel="blocked_agent_datatable" data-status="2">
                                <i class="icofont icofont-man-in-glasses"></i>{{ __('Blocked') }}<sup class="total-items" id="blocked_vendor_count">({{$agentRejected}})</sup>
                            </a>
                            <div class="material-border"></div>
                        </li>
                    </ul>

                    <div class="tab-content nav-material pt-0" id="top-tabContent">
                        <div class="tab-pane fade past-order show active" id="active_vendor" role="tabpanel" aria-labelledby="active-vendor">

                            <div class="table-responsive nagtive-margin">
                                <table class="table table-striped dt-responsive nowrap w-100 all agent-listing" id="agent-listing">
                                    <thead>
                                        <tr>
                                            <th class="sort-icon">{{__(getAgentNomenclature()." ID")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Profile")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Name")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Phone")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Type")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Team")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Cash Collected")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Order Earning")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Total Paid to ".getAgentNomenclature())}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Total Receive from ".getAgentNomenclature())}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Final Balance")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Subscription Plan")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Subscription Expiry")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Requested At")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Approved At")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th>{{__("Action")}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="awaiting_vendor" role="tabpanel" aria-labelledby="awaiting-vendor">
                            <div class="row">
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped dt-responsive nowrap w-100 all agent-listing" id="awaiting_approval_agent_datatable">
                                    <thead>
                                        <tr>
                                            <th class="sort-icon">{{__(getAgentNomenclature()." ID")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            {{-- <th class="sort-icon">{{__("UID")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th> --}}
                                            <th class="sort-icon">{{__("Profile")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Name")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Phone")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Type")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Team")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            {{-- <th class="sort-icon">{{__("Vehicle")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th> --}}
                                            <th class="sort-icon">{{__("Cash Collected")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Order Earning")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Total Paid to ".getAgentNomenclature())}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Total Receive from ".getAgentNomenclature())}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Final Balance")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Subscription Plan")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Subscription Expiry")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Reuested At")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Approved At")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th>{{__("Action")}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade past-order" id="block_vendor" role="tabpanel" aria-labelledby="block-vendor">
                            <div class="row">
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped dt-responsive nowrap w-100 all agent-listing" id="blocked_agent_datatable">
                                    <thead>
                                        <tr>
                                            <th class="sort-icon">{{__(getAgentNomenclature()." ID")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            {{-- <th class="sort-icon">{{__("UID")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th> --}}
                                            <th class="sort-icon">{{__("Profile")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Name")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Phone")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Type")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Team")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            {{-- <th class="sort-icon">{{__("Vehicle")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th> --}}
                                            <th class="sort-icon">{{__("Cash Collected")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Order Earning")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Total Paid to ".getAgentNomenclature())}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Total Receive from ".getAgentNomenclature())}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Final Balance")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Subscription Plan")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Subscription Expiry")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Requested At")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Rejected At")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th>{{__("Action")}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>.</tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
                
            </div> <!-- end col -->
        </div>
    </div>
</div>

@include('agent.modals')
@include('modals.pay-receive')
@if(getClientPreferenceDetail()->is_driver_slot == 1)
    @include('agent.modal-popup.agentSlotTableRows')
    {{-- @include('agent.modal-popup.slotPopup') --}}
<script>
    var AddSlotHtml = `<form class="needs-validation" name="slot-form" id="slot-event" action="" method="post">
                            @csrf
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ __("Start/End Date") }}</label>

                                        <input id="blocktime" class="form-control" autofocus>
                                    </div>
                                </div>
                                
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __("Start Time(24 hours format)") }}</label>
                                            <input class="form-control" placeholder="Start Time" type="time" name="start_time" id="start_time" required />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __("End Time(24 hours format)") }}</label>
                                            <input class="form-control" placeholder="End Time" type="time" name="end_time" id="end_time" required />
                                        </div>
                                    </div>
                                
                            
                                </div>
                                <div class="row memo">
                                    <div class="col-md-6 slot_type">
                                        <label class="d-block">Slot Type</label>
                                            <select id="booking_type">
                                                <option selected value="working_hours">Working hours</option>
                                                <option value="blocked">Block</option>
                                            </select>
                                    </div>
                                    <div class="col-md-6 slotForDiv">
                                        {!! Form::label('title', 'Recurring',['class' => 'control-label']) !!}
                                    <div class="form-group">
                                      
                                        <ul class="list-inline">
                                            <li class="d-block pl-1 ml-3 mb-1 custom-radio-design">
                                                <input type="checkbox" class="custom-control-input check recurring" id="recurring" name="recurring">
                                                <label class="custom-control-label"  for="recurring">Yes</label>
                                            </li>
                                        </ul>
                                    </div>
                                    </div>
                                </div>

                                <div class="row memo">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="d-block pt-2">Memo</label>
                                            <textarea placeholder="" id="memo" class="form-control memo"></textarea>
                                         </div>
                                    </div>
                                </div>
                             
                            <div class="row mb-2 weekDays" style="display:none">
                                <div class="col-md-12">
                                    <div class="">
                                    {!! Form::label('title', __('Select days of week'),['class' => 'control-label']) !!}
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_1" value="1">
                                        <label for="day_1"> {{ __("Sunday") }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_2" value="2">
                                        <label for="day_2"> {{ __('Monday') }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_3" value="3">
                                        <label for="day_3"> {{ __("Tuesday") }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_4" value="4">
                                        <label for="day_4"> {{ __("Wednesday") }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_5" value="5">
                                        <label for="day_5"> {{ __('Thursday') }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_6" value="6">
                                        <label for="day_6"> {{ __('Friday') }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_7" value="7">
                                        <label for="day_7"> {{ __('Saturday') }} </label>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row forDate" style="display: none;">
                                <div class="col-md-12" >
                                    <div class="form-group">
                                        <label class="control-label">{{ __("Slot Date") }}</label>
                                        <input class="form-control date-datepicker" placeholder={{ __("Select Date") }} type="text" name="slot_date" id="slot_date" required />
                                    </div>
                                </div>
                        
                            </div>
                        
                        </form>`;

                    var EditSlotHtml = `<form class="needs-validation" name="slot-form" id="update-event" action="" method="post">
                            @csrf
                            <input type="hidden" name="slot_day_id" id="slot_day_id" value="" >
                            <input type="hidden" name="slot_id" id="edit_slot_id" value="" >
                            <input type="hidden" name="edit_booking_type_old" id="edit_booking_type_old" value="" >
                            <input type="hidden" name="old_slot_type" id="edit_slot_type_old" value="" >
                            <input type="hidden" name="slot_date" id="edit_slot_date_D" value="" >
                            <input type="hidden" name="blocktime" id="edit_blocktime" value="" >
                            <input type="hidden" name="blocktime" id="edit_recurring" value="" >
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ __("Start/End Date") }}</label>

                                        <input id="blocktime" class="form-control"  autofocus>
                                    </div>
                                </div>
                                
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __("Start Time(24 hours format)") }}</label>
                                            <input class="form-control" placeholder="Start Time" type="time" name="start_time" id="edit_start_time" required />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __("End Time(24 hours format)") }}</label>
                                            <input class="form-control" placeholder="End Time" type="time" name="end_time" id="edit_end_time" required />
                                        </div>
                                    </div>
                                
                            
                                </div>
                                <div class="row memo view_booking">
                                    <div class="col-md-6 slot_type">
                                        <label class="d-block">Slot Type</label>
                                            <select id="edit_booking_type">
                                                <option selected value="working_hours">Working hours</option>
                                                <option value="blocked">Block</option>
                                            </select>
                                    </div>
                                    <div class="col-md-6 slotForDiv">
                                        {!! Form::label('title', 'Recurring',['class' => 'control-label']) !!}
                                    <div class="form-group">
                                      
                                        <ul class="list-inline">
                                            <li class="d-block pl-1 ml-3 mb-1 custom-radio-design">
                                                <input type="checkbox" class="custom-control-input check edit_recurring recurring" id="recurring" name="recurring">
                                                <label class="custom-control-label"  for="recurring">Yes</label>
                                            </li>
                                        </ul>
                                    </div>
                                    </div>
                                </div>

                                <div class="row memo">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="d-block pt-2">Memo</label>
                                            <textarea placeholder="" id="edit_memo" class="form-control memo"></textarea>
                                         </div>
                                    </div>
                                </div>
                             
                            <div class="row mb-2 weekDays view_booking" style="display:none">
                                <div class="col-md-12">
                                    <div class="">
                                    {!! Form::label('title', __('Select days of week'),['class' => 'control-label']) !!}
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_1" value="1">
                                        <label for="day_1"> {{ __("Sunday") }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_2" value="2">
                                        <label for="day_2"> {{ __('Monday') }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_3" value="3">
                                        <label for="day_3"> {{ __("Tuesday") }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_4" value="4">
                                        <label for="day_4"> {{ __("Wednesday") }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_5" value="5">
                                        <label for="day_5"> {{ __('Thursday') }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_6" value="6">
                                        <label for="day_6"> {{ __('Friday') }} </label>
                                    </div>
                                    <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                        <input name="week_day[]" type="checkbox" id="day_7" value="7">
                                        <label for="day_7"> {{ __('Saturday') }} </label>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row forDate" style="display: none;">
                                <div class="col-md-12" >
                                    <div class="form-group">
                                        <label class="control-label">{{ __("Slot Date") }}</label>
                                        <input class="form-control date-datepicker" placeholder={{ __("Select Date") }} type="text" name="slot_date" id="edit_slot_date" required />
                                    </div>
                                </div>
                        
                            </div>
                            <input  name="edit_type" type="hidden" id="edit_type" value="">
                            <input  name="edit_day" type="hidden" id="edit_day" value="">
                            <input name="edit_type_id" type="hidden" id="edit_type_id" value="">
                            <div class="row mt-2 view_booking">
                                <div class="col-12 mb-2">
                                    <button type="button" class="btn btn-danger w-100" id="deleteSlotBtn">{{ __("Delete Slot") }}</button>
                                </div>
                            </div>
                            <div class="row mt-2 view_orderDetails" style="display: none;">
                                <div class="col-12 mb-2">
                                    <a  class="btn btn-info w-100" target="_blank" id="viewOrder">{{ __("View Order") }}</a>
                                </div>
                            </div>
                        
                        </form>`;
</script>
@endif
@endsection

@section('script')

<!-- <script src="{{ asset('assets/js/jquery-ui.min.js') }}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}"> -->
<script src="{{ asset('assets/js/storeAgent.js') }}"></script>
<script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>
<script src="{{ asset('telinput/js/intlTelInput.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />
@if(getClientPreferenceDetail()->is_driver_slot == 1)
<script src="{{ asset('assets/js/AgentSlot/slot.js') }}"></script>
@endif

<script>
    $('#selectAgent').on('change', function(e) {

        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        $.ajax({
            type: 'get',
            url: "{{ url('/agent/paydetails') }}/" + valueSelected,
            data: '',
            success: function(data) {
                var order = round(data.order_cost, 2);
                var driver_cost = round(data.driver_cost, 2);
                var credit = round(data.credit, 2);
                var debit = round(data.debit, 2);
                var cash = round(data.cash_to_be_collected, 2);
                var final = round(cash - driver_cost, 2);
                var new_final = round(data.final_balance, 2);
                $("#order_earning").text(driver_cost);
                $("#cash_collected").text(cash);
                $("#final_balance").text(new_final);
            },
        });

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

    $("#submitpayreceive").submit(function(stay) {
        var formdata = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: "{{ route('pay.receive') }}",
            data: formdata,
            success: function(response) {
                if (response.status == 'Success') {
                    $("#pay-receive-modal .close").click();
                    location.reload();
                } else {
                    $("#pay-receive-modal .show_all_error.invalid-feedback").show();
                    $("#pay-receive-modal .show_all_error.invalid-feedback").text(response.message);
                }
            },
            error: function(response){
                let errors = response.responseJSON;
                $("#pay-receive-modal .show_all_error.invalid-feedback").show();
                $("#pay-receive-modal .show_all_error.invalid-feedback").text(errors.message);
            }
        });
        stay.preventDefault();
    });
</script>
@include('agent.pagescript')
@endsection