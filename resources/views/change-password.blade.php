
@extends('layouts.vertical', ['title' => 'Change Password'])

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
                    <h4 class="header-title">Change Password</h4>
                    <p class="sub-header">
                        <code>Organization details</code>/Change Password.         
                    </p>
                    <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group mb-3"> 
                                    <label for="old_password">Old Password</label>
                                    <div class="input-group input-group-merge ">
                                        <input class="form-control " name="old_password" type="password" required="" id="old_password" placeholder="Enter your old password">
                                            <div class="input-group-append" data-password="false">
                                            <div class="input-group-text">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group mb-3"> 
                                    <label for="password">New Password</label>
                                    <div class="input-group input-group-merge ">
                                        <input class="form-control " name="password" type="password" required="" id="password" placeholder="Enter your password">
                                            <div class="input-group-append" data-password="false">
                                            <div class="input-group-text">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group mb-3"> 
                                    <label for="confirm_password">Confirm Password</label>
                                    <div class="input-group input-group-merge ">
                                        <input class="form-control " name="password" type="password" required="" id="confirm_password" placeholder="Enter your confirm password">
                                            <div class="input-group-append" data-password="false">
                                            <div class="input-group-text">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-blue btn-block" type="submit"> Update </button>
                                </div>
                            </div>
                        </div>
                </div> 
            </div> 
        </div>
        
    </div> <!-- container -->
@endsection

@section('script')
@endsection