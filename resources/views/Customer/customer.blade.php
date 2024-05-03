@extends('layouts.vertical', ['title' => 'Customers'])

@section('css')
<style>
    .table th,.table td, .table td {
        display: table-cell !important;
    }

    .footer{
        z-index: 3;
    }
    #pricing-datatable_processing {
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
    div#pricing-datatable_filter {
        padding-top: 5px;
    }
    .dataTables_filter label {
        width: 25%;
    }
    .dataTables_filter label .form-control {
        height: 37px;
        font-size: 16px;
    }
    .dt-buttons .btn.btn-secondary,.dt-buttons .btn.btn-secondary:focus,.dt-buttons .btn.btn-secondary:active {
        border-radius: 5px;
        background: #6658ddd6 !important;
    }
    .btn-label,.btn-label:focus,.btn-label:active {
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
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title page-title1">{{ __("Customers") }}</h4>
            </div>
        </div>
    </div>
  
    <div class="row">
        <div class="col-12">
            <div class="card widget-inline">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-storefront text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_earnings_by_vendors">{{$customersCount}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{ __('Total Customers') }}</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-store-24-hour text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_order_count">{{$activeCustomers}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{__('Active')}}</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="fas fa-money-check-alt text-primary"></i>
                                    <span data-plugin="counterup" id="total_cash_to_collected">{{$inActiveCustomers}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{__('In-Active')}}</p>
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
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="text-sm-left">
                                @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('success') !!}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add Customer")}}</button>
                        </div>
                    </div>

                    <div class="table-responsive nagtive-margin">
                        <table class="table table-striped dt-responsive nowrap w-100"  id="pricing-datatable">
                            <thead>
                                <tr>
                                    <th>{{__("Name")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th>{{__("Email")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th>{{__("Dial Code")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th>{{__("Phone number")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th>{{__("Status")}}</th>
                                    <th style="width: 85px;">{{__("Action")}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                    
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>

</div>
@include('Customer.customer-modal')

@endsection

@section('script')
    <script src="{{asset('assets/js/storeAgent.js')}}"></script>
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
@include('Customer.pagescript')  

@endsection