@extends('layouts.vertical', ['title' => 'Options'])

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
        <div class="col-xl-11 col-md-offset-1">
            <div class="card-box">
                <h4 class="header-title">Options</h4>
                <p class="sub-header">
                    Select whether you want to allow feedback on tracking URL.
                </p>
                <div class="alert alert-success d-none">
                    <span></span>
                </div>
                <form id="AllowFeedback">
                    @csrf
                    <input type="hidden" id="get_client_id" name="client_id" value="{{Auth::user()->id}}">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <div class="text-sm-left">
                                <h4 class="header-title">Allow Feedback on Tracking URL</h4>
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <div class="custom-control custom-switch form-group" id="profile_pictureInput">
                                <input type="checkbox" class="custom-control-input" id="allow_feedback_tracking_url"
                                    value="y" name="allow_feedback_tracking_url"
                                    {{ (isset($preference) && $preference->allow_feedback_tracking_url == "y")? "checked" : "" }}>
                                <label class="custom-control-label" for="allow_feedback_tracking_url"></label>
                            </div>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>
@endsection

@section('script')
@endsection