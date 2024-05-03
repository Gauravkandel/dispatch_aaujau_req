
@extends('layouts.vertical', ['title' => 'Settings'])

@section('css')
@endsection

@section('content')
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
            <div class="col-xl-12">
                <div class="card-box">
                    <h4 class="header-title">Options</h4>
                    <p class="sub-header">
                        Select whether you want to allow feedback on tracking URL.
                    </p>
                    <div class="card-box">
                        <div class="row mb-2">
                            <div class="col-sm-8">
                                <div class="text-sm-left">
                                    <h4 class="header-title">Allow Feedback on Tracking URL</h4>
                                </div>
                            </div>
                            <div class="col-sm-4 text-right">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" checked="" class="custom-control-input" id="allow_tracking_url">
                                    <label class="custom-control-label" for="allow_tracking_url"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card-box">
                    <h4 class="header-title">Notifications</h4>
                    <p class="sub-header">
                        Send custom SMS's,emails and webhooks based on each trigger and customize the content by clicking on the pencil icon.
                    </p>
                    <div class="card-box">
                        <div class="row mb-2">
                            <div class="col-sm-8">
                                <div class="text-sm-left">
                                    <h4 class="header-title">Pickup Notifications</h4>
                                </div>
                            </div>
                            <div class="col-sm-4 text-right">
                                <p class="btn btn-blue waves-effect waves-light text-sm-right">
                                    <i class="mdi mdi-plus-circle mr-1"></i> Add More
                                </p>
                            </div>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                <thead class="thead-light">
                                    <tr>
                                        <th>Events</th>
                                        <th>SMS</th>
                                        <th>EMAIL</th>
                                        <th>WEBHOOK</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Request Recieved</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="smscustomSwitch1" checked="">
                                                <label class="custom-control-label" for="smscustomSwitch1"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="emailcustomSwitch1">
                                                <label class="custom-control-label" for="emailcustomSwitch1"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="webhookcustomSwitch1">
                                                <label class="custom-control-label" for="webhookcustomSwitch1"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <a href="javascript: void(0);" class="action-icon">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">{{getAgentNomenclature()}} Started</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="smscustomSwitch2">
                                                <label class="custom-control-label" for="smscustomSwitch2"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="emailcustomSwitch2">
                                                <label class="custom-control-label" for="emailcustomSwitch2"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="webhookcustomSwitch2">
                                                <label class="custom-control-label" for="webhookcustomSwitch2"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <a href="javascript: void(0);" class="action-icon">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">{{getAgentNomenclature()}} Arrived</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="smscustomSwitch3">
                                                <label class="custom-control-label" for="smscustomSwitch3"></label>
                                            </div>
                                        </td>

                                        <td>
                                           <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="emailcustomSwitch3">
                                                <label class="custom-control-label" for="emailcustomSwitch3"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="webhookcustomSwitch3">
                                                <label class="custom-control-label" for="webhookcustomSwitch3"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <a href="javascript: void(0);" class="action-icon">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Successfull</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="smscustomSwitch4">
                                                <label class="custom-control-label" for="smscustomSwitch4"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="emailcustomSwitch4">
                                                <label class="custom-control-label" for="emailcustomSwitch4"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="webhookcustomSwitch4">
                                                <label class="custom-control-label" for="webhookcustomSwitch4"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <a href="javascript: void(0);" class="action-icon">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Failed</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="smscustomSwitch5">
                                                <label class="custom-control-label" for="smscustomSwitch5"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="emailcustomSwitch5">
                                                <label class="custom-control-label" for="emailcustomSwitch5"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="webhookcustomSwitch5">
                                                <label class="custom-control-label" for="webhookcustomSwitch5"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <a href="javascript: void(0);" class="action-icon">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-box">

                        <div class="row mb-2">
                            <div class="col-sm-8">
                                <div class="text-sm-left">
                                    <h4 class="header-title">Delivery Notifications</h4>
                                </div>
                            </div>
                            <div class="col-sm-4 text-right">
                                <p class="btn btn-danger waves-effect waves-light text-sm-right">
                                    <i class="mdi mdi-plus-circle mr-1"></i> Add More
                                </p>
                            </div>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                <thead class="thead-light">
                                    <tr>
                                        <th>Events</th>
                                        <th>SMS</th>
                                        <th>EMAIL</th>
                                        <th>WEBHOOK</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Request Recieved</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="deliverysmscustomSwitch1">
                                                <label class="custom-control-label" for="deliverysmscustomSwitch1"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="deliveryemailcustomSwitch1">
                                                <label class="custom-control-label" for="deliveryemailcustomSwitch1"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="deliverywebhookcustomSwitch1">
                                                <label class="custom-control-label" for="deliverywebhookcustomSwitch1"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <a href="javascript: void(0);" class="action-icon">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">{{getAgentNomenclature()}} Started</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="deliverysmscustomSwitch2">
                                                <label class="custom-control-label" for="deliverysmscustomSwitch2"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="deliveryemailcustomSwitch2">
                                                <label class="custom-control-label" for="deliveryemailcustomSwitch2"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="deliverywebhookcustomSwitch2">
                                                <label class="custom-control-label" for="deliverywebhookcustomSwitch2"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <a href="javascript: void(0);" class="action-icon">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">{{getAgentNomenclature()}} Arrived</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="deliverysmscustomSwitch3">
                                                <label class="custom-control-label" for="deliverysmscustomSwitch3"></label>
                                            </div>
                                        </td>

                                        <td>
                                           <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="deliveryemailcustomSwitch3">
                                                <label class="custom-control-label" for="deliveryemailcustomSwitch3"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="deliverywebhookcustomSwitch3">
                                                <label class="custom-control-label" for="deliverywebhookcustomSwitch3"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <a href="javascript: void(0);" class="action-icon">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Successfull</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="deliverysmscustomSwitch4">
                                                <label class="custom-control-label" for="deliverysmscustomSwitch4"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="deliveryemailcustomSwitch4">
                                                <label class="custom-control-label" for="deliveryemailcustomSwitch4"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="deliverywebhookcustomSwitch4">
                                                <label class="custom-control-label" for="deliverywebhookcustomSwitch4"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <a href="javascript: void(0);" class="action-icon">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Failed</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="deliverysmscustomSwitch5">
                                                <label class="custom-control-label" for="deliverysmscustomSwitch5"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="deliveryemailcustomSwitch5">
                                                <label class="custom-control-label" for="deliveryemailcustomSwitch5"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="deliverywebhookcustomSwitch5">
                                                <label class="custom-control-label" for="deliverywebhookcustomSwitch5"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <a href="javascript: void(0);" class="action-icon">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
@endsection
