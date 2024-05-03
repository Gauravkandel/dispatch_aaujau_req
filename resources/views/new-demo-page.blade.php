@extends('layouts.vertical', ['title' => 'Geo'])

@section('css')

<style>
    #map-canvas {
  height:100%;
  margin: 0px;
  padding: 0px;
  position: unset;
}
</style>
@endsection

@section('content')
@include('modals.add-agent')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Route Detail</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-2 col-sm-6">
            <div class="card-box">
                <h4 class="header-title mb-2">Customers Details</h4>
                
                <div class="row align-items-center mb-3">
                    <div class="col-3 pic-left">
                        <img src="https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/https://royodelivery-assets.s3.us-west-2.amazonaws.com/assets/client_00000051/agents5fedb209f1eea.jpeg/Ec9WxFN1qAgIGdU2lCcatJN5F8UuFMyQvvb4Byar.jpg" alt="contact-img" title="contact-img" class="rounded-circle avatar-sm">
                    </div>
                    <div class="col-9 pl-1">
                        <h5 class="m-0 font-weight-normal">new {{getAgentNomenclature()}}</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <p><i class="fa fa-phone" aria-hidden="true"></i> 989898989</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <p><i class="fa fa-envelope" aria-hidden="true"></i> ttgs@yopmail.com</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-box">
                <h4 class="header-title mb-2">{{getAgentNomenclature()}} Details</h4>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row align-items-center mb-3">
                            <div class="col-3 pr-0 pic-left">
                                <img src="https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/https://royodelivery-assets.s3.us-west-2.amazonaws.com/assets/client_00000051/agents5fedb209f1eea.jpeg/Ec9WxFN1qAgIGdU2lCcatJN5F8UuFMyQvvb4Byar.jpg" alt="contact-img" title="contact-img" class="rounded-circle avatar-sm">
                            </div>
                            <div class="col-9 pl-1">
                                <h5 class="m-0 font-weight-normal">new {{getAgentNomenclature()}}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-12">
                        <div class="form-group">
                            <p><i class="fa fa-phone" aria-hidden="true"></i> 989898989</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-12">
                        <div class="form-group">
                            <p><i class="fa fa-text-width" aria-hidden="true"></i> Employee</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-12">
                        <div class="form-group">
                            <p><i class="fa fa-truck" aria-hidden="true"></i> Truck</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-12">
                        <div class="form-group">
                            <p><i class="fa fa-truck" aria-hidden="true"></i> 200132</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-12">
                        <div class="form-group">
                            <p><i class="fa fa-truck" aria-hidden="true"></i> 200132</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="col-xl-3 col-sm-6">
            <div class="card-box">
                <h4 class="header-title mb-2">Track Url</h4>
                <div class="site_link position-relative">
                    <a href="sales.dispatcher.com" target="_blank"><span id="pwd_spn" class="password-span">sales.dispatcher.com</span></a>
                    <label class="copy_link float-right" id="cp_btn" title="copy">
                        <!-- <i class="far fa-copy"></i> -->
                        <img src="https://easybook.co/easybook_html/images/domain_copy_icon.svg" alt="">
                        <span class="copied_txt" id="show_copy_msg_on_click_copy"></span>
                    </label>
                </div>
            </div>

            <div class="card-box">
                <h4 class="header-title mb-2">Rejections</h4>
                <div class="row align-items-center mb-2">
                    <div class="col-2 pr-0 pic-left">
                        <img src="https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/https://royodelivery-assets.s3.us-west-2.amazonaws.com/assets/client_00000051/agents5fedb209f1eea.jpeg/Ec9WxFN1qAgIGdU2lCcatJN5F8UuFMyQvvb4Byar.jpg" alt="contact-img" title="contact-img" class="rounded-circle avatar-sm">
                    </div>
                    <div class="col-10 pl-1">
                        <h5 class="m-0 font-weight-normal">new {{getAgentNomenclature()}}</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="mb-0">Date &amp; Time</label>
                            <p>10:00 am, 16-Aug-2021</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7">  
            <div class="card-box">
                <h4 class="header-title mb-2">Task List</h4>
                <div class="address_box mb-1">
                    <span class="yellow_ mb-0"> Pickup</span> 
                    <span class="short_name">gs_jind</span> 
                    <label class="m-0" data-toggle="tooltip" data-placement="bottom" title="Jind, Haryana, India">Jind, Haryana, India</label>
                </div>
                <div class="address_box mb-1">
                    <span class="yellow_ mb-0"> Pickup</span> 
                    <span class="short_name">gs_jind</span> 
                    <label class="m-0" data-toggle="tooltip" data-placement="bottom" title="Jind, Haryana, India">Jind, Haryana, India</label>
                </div>
                <div class="address_box mb-1">
                    <span class="yellow_ mb-0"> Pickup</span> 
                    <span class="short_name">gs_jind</span> 
                    <label class="m-0" data-toggle="tooltip" data-placement="bottom" title="Jind, Haryana, India">Jind, Haryana, India</label>
                </div>
            </div>       
            
            <div class="card-box p-2"> 
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d27442.65334027974!2d76.82252954940223!3d30.709075056260815!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1629116296414!5m2!1sen!2sin" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card-box">
                <div class="row">

                    <div class="col-md-12">
                        <div class="">
                            <h4 class="header-title mb-3">Pay Details</h4>

                            <div class="row">
                                
                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            <label for="title" class="control-label">Base Price</label> <br>
                                            <span id="base_price">10.00</span>
                                        </div>
                                    </div> 


                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            <label for="title" class="control-label">Duration Price</label> <br>
                                            <span id="duration_price">10.00 (Per min)</span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            <label for="title" class="control-label">Distance Price</label> <br>
                                            <span id="distance_fee">20.00 (Km)</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            <label class="control-label">{{getAgentNomenclature()}} Type</label> <br>
                                            
                                            <span id="driver_type"></span>
                                        </div>
                                    </div>
                                
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Base Distance</label> <br>
                                        <span id="base_distance">1</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Actual Distance</label> <br>
                                        <span id="actual_distance">0.00</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Billing Distance</label> <br>
                                        <span id="billing_distance">0</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Distance Cost</label> <br>
                                        <span id="distance_cost">0</span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Base Duration</label> <br>
                                        <span id="base_duration">5</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Actual Duration</label> <br>
                                        <span id="actual_duration">0.00</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Billing Duration</label> <br>
                                        <span id="billing_duration">0</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Duration Cost</label> <br>
                                        <span id="duration_cost">0</span>
                                    </div>
                                </div>
                            </div>
                            

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Order Cost</label>
                                        <h5 id="order_cost">10.00</h5>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">{{getAgentNomenclature()}} Cost</label>
                                        <h5 id="driver_cost">0.00</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">{{getAgentNomenclature()}} Commission %</label> <br>
                                        <span id="agent_commission_percentage">5</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id=""> 
                                        <label for="title" class="control-label">{{getAgentNomenclature()}} Commission Fixed</label> <br>
                                        <span id="agent_commission_fixed">8</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Freelancer Commission%</label> <br>
                                        <span id="freelancer_commission_percentage">6</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group pay-detail-box copyin1" id="">
                                        <label for="title" class="control-label">Freelancer Commission Fixed</label> <br>
                                        <span id="freelancer_commission_fixed">7</span>
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
@endsection
