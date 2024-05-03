<div id="add-customer-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">{{__("Add Customer")}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="add_customer" action="{{ route('customer.store') }}" method="POST">
                @csrf
                <div class="modal-body py-0">

                    <div class="row">



                        <div class="col-md-12">
                            <div class="card-box mb-0 p-0">

                                <div class="row">

                                    <div class="col-lg-4 col-sm-6 mb-lg-0 mb-3">
                                        <div class="form-group" id="nameInput">
                                            {!! Form::label('title', __('Name'),['class' => 'control-label']) !!}
                                            {!! Form::text('name', null, ['class' => 'form-control']) !!}

                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 mb-lg-0 mb-3">
                                        <div class="form-group" id="emailInput">
                                            {!! Form::label('title', __('Email'),['class' => 'control-label']) !!}
                                            {!! Form::email('email', null, ['class' => 'form-control']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 mb-lg-0 mb-3">
                                        <div class="form-group" id="phone_numberInput">
                                            {!! Form::label('title', __('Phone Number'),['class' => 'control-label']) !!}<br/>
                                            <div class="input-group">
                                                {!! Form::text('phone_number', null, ['class' => 'form-control phone_number', 'id'=>'phone_number']) !!}
                                                <input type="hidden" id="dialCode" name="dialCode" value="{{getCountryPhoneCode()}}">
                                            </div>
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>


                                <div class="addapp">
                                    {!! Form::label('title', __('Address'),['class' => 'control-label']) !!}
                                    <div class="row address addressrow1" id="add1">
                                        <div class="col-lg-4 col-md-3 mb-lg-0 mb-3">
                                            <div class="form-group">
                                                <input type="text" id="add1-flat" name="flat_no[]" class="form-control" placeholder="{{__('House / Apartment/ Flat number')}}">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong></strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 mb-lg-0 mb-3">
                                            <div class="form-group" id="short_nameInput">
                                                <input type="text" name="short_name[]" class="form-control" placeholder="{{__('Short Name')}}">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong></strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 mb-lg-0 mb-3">
                                            <div class="form-group input-group" id="addressInput">
                                                <input type="text" id="add1-input" name="address[]" class="form-control" placeholder="{{__('Address')}}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="add1"> <i class="mdi mdi-map-marker-radius"></i></button>
                                                </div>
                                                <input type="hidden" name="latitude[]" id="add1-latitude" value="0" />
                                                <input type="hidden" name="longitude[]" id="add1-longitude" value="0" />
                                                <span class="invalid-feedback" role="alert" id="address">
                                                    <strong></strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 mb-lg-0 mb-3">
                                            <div class="form-group">
                                                <input type="text" id="add1-email" name="address_email[]" class="form-control" placeholder={{__("Email")}}>
                                                <span class="invalid-feedback" role="alert">
                                                    <strong></strong>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-3 mb-lg-0 mb-3">
                                            <div class="form-group">
                                                <input type="text" id="add1-phone_number" name="address_phone_number[]" class="form-control" placeholder={{__("Phone Number")}}>
                                                <span class="invalid-feedback" role="alert">
                                                    <strong></strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 mb-lg-0 mb-3">
                                            <div class="form-group d-flex align-items-center" id="post_codeInput">
                                                <input type="text" name="post_code[]" class="form-control" placeholder="{{__('Post Code')}}" id="add1-postcode">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong></strong>
                                                </span>
                                                <button type="button" class="btn btn-primary-outline action-icon" onclick="deleteAddress('',1)"> <i class="mdi mdi-delete"></i></button>
                                            </div>
                                        </div>

                                        {{-- <div class="col-sm-6 mb-lg-0 mb-3">

                                            <div class="form-group">
                                                <label for="">Due After</label>
                                                <input type="time" id="add1-due_after" name="due_after[]" class="form-control" placeholder="Due After">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong></strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-lg-0 mb-3">
                                            <div class="form-group">
                                                <label for="">Due Before</label>
                                                <input type="time" id="add1-due_before" name="due_before[]" class="form-control" placeholder="Due Before">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong></strong>
                                                </span>
                                            </div>
                                        </div> --}}


                                    </div>
                                    <div id="address-map-container" style="width:100%;height:400px; display: none;">
                                        <div style="width: 100%; height: 100%" id="address-map"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-center" id="adds">
                                        <a href="#"  class="btn btn-success btn-rounded waves-effect waves-light addField" >{{__("Add More Address")}}</a>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">

                                    </div>
                                    <div class="col-md-7">

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light submitCustomerForm">{{__("Submit")}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-customer-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">{{__("Edit Customer")}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form id="edit_customer" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body px-3 py-0" id="editCardBox">

                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-blue waves-effect waves-light submitEditForm">{{__("Submit")}}</button>
                </div>

            </form>
        </div>
    </div>
</div>

<div id="show-map-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header border-0">
                <h4 class="modal-title">{{__("Select Location")}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body px-3 py-0">

                <div class="row">
                    <form id="task_form" action="#" method="POST" style="width: 100%">
                        <div class="col-md-12">
                            <div id="googleMap" style="height: 500px; min-width: 500px; width:100%"></div>
                            <input type="hidden" name="lat_input" id="lat_map" value="0" />
                            <input type="hidden" name="lng_input" id="lng_map" value="0" />
                            <input type="hidden" name="for" id="map_for" value="" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-blue waves-effect waves-light selectMapLocation">{{__("Ok")}}</button>
                <!--<button type="Cancel" class="btn btn-blue waves-effect waves-light cancelMapLocation">cancel</button>-->
            </div>
        </div>
    </div>
</div>
