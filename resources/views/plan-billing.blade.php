@extends('layouts.vertical', ['title' => 'Options'])

@section('css')
@endsection

@section('content')
@include('modals.add-card')
@include('modals.update-plan')

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
            <div class="card-box">
                <h4 class="header-title">Plan & Billings</h4>
                <p class="sub-header">
                    View and update your Plans & Billing details.
                </p>
                <h4 class="header-title">Plan Details</h4>
                <div class="card-box row border p-3 mb-3 rounded">
                    <div class="col-md-4">
                        <h5 class="card-title text-muted">PLAN NAME</h5>
                        <h6 class="card-subtitle">Basic Trial</h6>
                    </div>
                    <div class="col-md-4">
                        <h5 class="card-title text-muted">EXPIRE ON</h5>
                        <h6 class="card-subtitle">23 June 2020 - 16 days left</h6>
                    </div>
                    <div class="col-md-4 align-self-center">
                        <a href="javascript:;"data-toggle="modal" data-target="#update-card_modal">Update Plan</a>
                    </div>
                </div>
                <h4 class="header-title">Billing Details</h4>
                <div class="card-box row border p-3 mb-3 rounded">
                    <div class="col-md-8">
                        <h5 class="card-title text-muted">CARD DETAILS</h5>
                        <div class="media">
                            <img src="{{asset('assets/images/visa.svg')}}" alt="Generic placeholder image"
                                class="mr-3 avatar-sm bg-white">
                            <div class="media-body">
                                <h5 class="card-title ">Card Ending in 2284</h5>
                                <h6 class="card-subtitle text-muted">Expire in 08/24</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 align-self-center">
                        <a href="javascript:;" data-toggle="modal" data-target="#add-card-modal">Change Card</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection

@section('script')

@endsection