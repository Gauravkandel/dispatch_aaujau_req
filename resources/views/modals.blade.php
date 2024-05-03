@extends('layouts.vertical', ['title' => 'Options'])

@section('css')
<link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

<!-- for File Upload -->

<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
@include('modals.add-card')
@include('modals.add-agent')
@include('modals.add-customer')
@include('modals.create-appoinment')
@include('modals.pickup-delivery')
@include('modals.request-recieve')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Settings</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-xl-11 col-md-offset-1">
            <div class="alert alert-success d-none">
                <span></span>
            </div>
            <div class="card-box">
                <h4 class="header-title">Modals</h4>
                <div class="button-list">
                    <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal"
                        data-target="#add-agent-modal" data-backdrop="static" data-keyboard="false">Add {{getAgentNomenclature()}}</button>
                    <button type="button" class="btn btn-outline-success waves-effect waves-light" data-toggle="modal"
                        data-target="#add-card-modal">Add Card</button>
                    <button type="button" class="btn btn-outline-info waves-effect waves-light" data-toggle="modal"
                        data-target="#add-customer-modal" data-backdrop="static" data-keyboard="false">Add
                        Customer</button>
                    <button type="button" class="btn btn-outline-warning waves-effect waves-light" data-toggle="modal"
                        data-target="#create-appoinment-modal">Create
                        Appoinment</button>
                    <button type="button" class="btn btn-outline-danger waves-effect waves-light" data-toggle="modal"
                        data-target="#pickup-delivery-modal">Pickup
                        Delivery</button>
                    <button type="button" class="btn btn-outline-dark waves-effect waves-light" data-toggle="modal"
                        data-target="#request-receive-modal">Request Receive</button>

                </div>
            </div>
        </div>
    </div>


</div>
@endsection

@section('script')

<!-- Plugins js-->
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-advanced.init.js')}}"></script>
<script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>

<script src="{{asset('assets/js/storeAgent.js')}}"></script>

<!-- for File Upload -->
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>
<script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
@endsection