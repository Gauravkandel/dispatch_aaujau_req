<div class="row">
    <div class="col-md-12">
        <div class="card-box pb-0 pt-0 mb-1">            
            <div class="row d-flex">
                <div class="col-sm-12 col-xl-4 col-md-12" style="border-right: 1px solid #ccc;">
                    @csrf
                    <div class="row mb-2" id="dateredio">
                        <div class="col-md-12">
                            <div class="radio radio-primary form-check-inline mr-3">
                                <input type="radio" id="tasknow" value="now" name="task_type" class="checkschedule" checked>
                                <label for="tasknow"> {{__("Add Now")}} </label>
                            </div>
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" id="taskschedule" value="schedule" class="checkschedule" name="task_type">
                                <label for="taskschedule"> {{__("Schedule For Later")}} </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 datenow">
                        <div class="col-md-12">
                            <input type="text"  name="schedule_time"
                            class="form-control opendatepicker upside datetime-datepicker" placeholder='{{__("Date Time")}}'>
                            <button type="button" class="cstmbtn check_btn btn btn-info"><i class="fa fa-check" aria-hidden="true"></i></button>
                        </div>
                    </div>

                    <h4 class="header-title mb-2">{{__("Customer Details")}}</h4>

                    <div class="row mb-2" id="customerradio">
                        <div class="col-md-12">
                            <div class="radio radio-primary form-check-inline mr-3">
                                <input type="radio" id="existing_customer" value="existingcustomer" name="customer_type" class="checkcustomer" checked>
                                <label for="existing_customer"> {{__("Existing Customer")}} </label>
                            </div>
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" id="new_customer" value="newcustomer" class="checkcustomer" name="customer_type">
                                <label for="new_customer"> {{__("New Customer")}} </label>
                            </div>
                        </div>
                    </div>
                    <span class="span1 searchspan">{{__("Please search a customer or add a customer")}}</span>
                    <div class="row mb-1 searchshow">
                        <div class="col-md-12">
                            <div class="form-group" id="nameInputHeader">
                            {!! Form::text('search', null, ['class' => 'form-control', 'placeholder' => __('Search Customer'), 'id' => 'searchCust']) !!}
                            <input type="hidden" id='cusid' name="ids" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-1 newcustomer">
                        <div class="col-md-12">
                            <div class="form-group" id="nameInput">
                                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Name'),'id'=>'name_new']) !!}
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-1">
                        <div class="col-md-12">
                            <div class="form-group" id="emailInput">
                                {!! Form::text('email', null, ['class' => 'form-control email', 'placeholder' => __('Email'),'id'=>'email']) !!}
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" id="phone_numberInput">
                                <div class="input-group">
                                    <input type="text" name="phone_number" class="form-control phone_number" id="phone_number" placeholder="{{__('Enter mobile number')}}" maxlength="14">
                                    <input type="hidden" id="dialCode" name="dialCode" value="{{getCountryPhoneCode()}}">
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <input type="hidden" id="check-pickup-barcode" value="{{ (!empty($task_proofs[0]->barcode_requried) ? $task_proofs[0]->barcode_requried : 0)}}">
                        <input type="hidden" id="check-drop-barcode" value="{{ (!empty($task_proofs[1]->barcode_requried) ? $task_proofs[1]->barcode_requried : 0)}}">
                        <input type="hidden" id="check-appointment-barcode" value="{{ (!empty($task_proofs[2]->barcode_requried) ? $task_proofs[2]->barcode_requried : 0)}}">
                    </div>

                    <h4 class="header-title mb-2">{{__("Meta Data")}} <a href="javascript:void(0)" class="edit-icon-float-right"> <i class="mdi mdi-chevron-down"></i></a></h4>
                    <div class="meta_data_task_div" style="display:none;">
                        <div class="row mb-2">
                            <div class="col-md-12" id="make_modelInput">
                                {!! Form::hidden('recipient_phone', null, ['class' => 'form-control rec', 'placeholder' =>
                                __('Recipient Phone'), 'required' => 'required']) !!}
                                {!! Form::hidden('recipient_email', null, ['class' => 'form-control rec', 'placeholder'
                                => __('Recipient Email'), 'required' => 'required']) !!}
                                {!! Form::textarea('task_description', null, ['class' => 'form-control',
                                'placeholder' => __('Please enter task description'), 'rows' => 3, 'cols' => 40]) !!}
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>

                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <input type="file" data-plugins="dropify" class="dropify" name="file[]" data-height="300" multiple accept="image/*"/>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-md-12"  id="make_modelInput">
                            {!! Form::text('call_back_url', null, ['class' => 'form-control rec', 'placeholder' => __('Call Back URL')]) !!}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12" id="cash_to_be_collectedInput">
                            <input class="form-control" type="text" placeholder='{{__("Cash to be collected")}}' name="cash_to_be_collected">
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>

                    <h4 class="header-title mb-2">{{__("Allocation")}}</h4>
                    <div class="row mb-2" id="rediodiv">
                        <div class="col-md-12">
                            <div class="radio radio-primary form-check-inline mr-2">
                                <input type="radio" id="customRadio" value="u" name="allocation_type" class="assignRadio" {{$allcation->manual_allocation == 0 ?'checked':''}}>
                                <label for="customRadio"> {{__("Unassigned")}} </label>
                            </div>
                            <div class="radio radio-info form-check-inline mr-2">
                                <input type="radio" id="customRadio22" value="a" name="allocation_type" class="assignRadio" {{$allcation->manual_allocation == 1 ?'checked':''}}>
                                <label for="customRadio22"> {{__("Auto Allocation")}} </label>
                            </div>
                            <div class="radio radio-warning form-check-inline">
                                <input type="radio" id="customRadio33" value="m" name="allocation_type" class="assignRadio">
                                <label for="customRadio33"> {{__("Manual")}} </label>
                            </div>
                        </div>
                    </div>
                    
                    <span class="span1 tagspan">{{__("Please select atlest one tag for ".getAgentNomenclature())}}</span>
                    <div class="tags {{ $allcation->manual_allocation == 0 ? 'hidealloction':''}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{__("Team Tag")}}</label>
                                    <select name="team_tag[]" id="selectize-optgroups" class="selectizeInput" multiple placeholder={{__("Select tag...")}}>
                                        <option value="">{{__("Select Tag...")}}</option>
                                        @foreach ($teamTag as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{__(getAgentNomenclature()." Tag")}}</label>
                                    <select name="agent_tag[]" id="selectize-optgroup" class="selectizeInput" multiple placeholder="{{__('Select tag...')}}">
                                        <option value="">{{__("Select Tag...")}}</option>
                                        @foreach ($agentTag as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row drivers hidealloction">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__(getAgentNomenclature()."s")}}</label>
                                {!! Form::text('searchDriver', null, ['class' => 'form-control', 'placeholder' => __('Search '.getAgentNomenclature()), 'id' => 'searchDriver']) !!}
                                <input type="hidden" id='agentid' name="agent" readonly>
                                {{-- <select class="form-control selectpicker" name="agent" id="driverselect">
                                    @foreach ($agents as $item)
                                        @php
                                            $checkAgentActive = ($item->is_available == 1) ? ' ('.__('Online').')' : ' ('.__('Offline').')';
                                        @endphp
                                        <option value="{{ $item->id }}">{{ ucfirst($item->name) . $checkAgentActive }}</option>
                                    @endforeach
                                </select> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-8 col-md-12">
                    <h4 class="header-title mb-2">{{__("Tasks")}}</h4>
                    <span class="span1 addspan">{{__("Please select a address or create new")}}</span>
                    <div class="cust_add_div" id="addHeader1">
                        <div class="alTaskType pt-1 pb-1 copyin1 cloningDiv check-validation" id="copyin1">
                            <div class="alFormTaskType row m-0">
                                <div class="col-md-12">
                                    <div class="row firstclone1">
                                        <div class="col-10 col-md-6">
                                            <div class="form-group mb-1">
                                                <select class="selecttype mt-1" id="task_type"  name="task_type_id[]" style="width:100%;" required>
                                                    <option value="1">{{__("Pickup Task")}}</option>
                                                    <option value="2">{{__("Drop Off Task")}}</option>
                                                    <option value="3">{{__("Appointment")}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-10 col-md-5">
                                            <div class="form-group appoint mt-1 mb-1">
                                                {!! Form::text('appointment_date[]', null, ['class' => 'form-control appointment_date', 'placeholder' => __('Duration (In Min)')]) !!}
                                                <span class="invalid-feedback" role="alert">
                                                    <strong></strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-1 text-center pt-2 pr-2" >
                                            <span class="span1 delbtnhead" id="spancheck"><img style="filter: grayscale(.5);" src="{{asset('assets/images/ic_delete.png')}}"  alt=""></span>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="alCol-12 mainaddress col-8">
                                            <div class="row">
                                                <div class="col-6 addressDetails border-right">
                                                    <h6>Address Details</h6>
                                                    <div class="row">
                                                        <div class="form-group col-6 mb-1">
                                                            {!! Form::text('short_name[]', null, ['class' => 'form-control address', 'placeholder' => __('Short Name')]) !!}
                                                        </div>
                                                        <div class="input-group form-group col-6 mb-2">
                                                            <input type="text" id="addHeader1-input" name="address[]" class="form-control address cust_add" placeholder='{{__("Location")}}'>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-xs btn-dark waves-effect waves-light showMapHeader cust_btn" type="button" num="addHeader1"> <i class="mdi mdi-map-marker-radius"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-6 mb-1">
                                                            {!! Form::text('flat_no[]', null, ['class' => 'form-control address flat_no','placeholder' => __('House/Apartment/Flat no'),'id'=>'addHeader1-flat_no']) !!}
                                                        </div>
                                                        <div class="form-group col-6 mb-1">
                                                            <input type="hidden" name="latitude[]" id="addHeader1-latitude" value="0" class="cust_latitude" />
                                                            <input type="hidden" name="longitude[]" id="addHeader1-longitude" value="0" class="cust_longitude" />
                                                            {!! Form::text('post_code[]', null, ['class' => 'form-control address postcode','placeholder' => __('Post Code'),'id'=>'addHeader1-postcode']) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="alContactOther col-6">
                                                    <div class="row">
                                                        <div class="col-6 alRightBorder">
                                                            <h6>Contact Details</h6>
                                                            <div class="row">
                                                                <div class="form-group mb-1 col-12">
                                                                    {!! Form::text('address_email[]', null, ['class' => 'form-control address address_email','placeholder' => __('Email'),'id'=>'addHeader1-address_email']) !!}
                                                                </div>
                                                                <div class="form-group mb-1 col-12">
                                                                    {!! Form::text('address_phone_number[]', null, ['class' => 'form-control address address_phone_number','placeholder' => __('Phone Number'),'id'=>'addHeader1-address_phone_number']) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <h6>Other Details</h6>
                                                            <div class="row">
                                                                <div class="form-group mb-1 col-12">
                                                                    {!! Form::text('barcode[]', null, ['class' => 'form-control barcode','placeholder' => __('Task Barcode')]) !!}
                                                                </div>
                                                                <div class="form-group mb-1 col-12">
                                                                    {!! Form::text('quantity[]', null, ['class' => 'form-control quantity onlynumber','placeholder' => __('Quantity')]) !!}
                                                                </div>
                                                                <span class="span1 pickup-barcode-error">{{__("Task Barcode is required for pickup")}}</span>
                                                                <span class="span1 drop-barcode-error">{{__("Task Barcode is required for drop")}}</span>
                                                                <span class="span1 appointment-barcode-error">{{ __("Task Barcode is required for appointment")}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 alsavedaddress" id="alsavedaddress" style="display:none;">
                                            <h6>Saved Addresses</h6>
                                            <div class="form-group withradio" id="typeInputss">
                                                <div class="oldhide text-center">
                                                    <img class="showsimage" src="{{url('assets/images/ic_location_placeholder.png')}}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="addSubFields" style="width:100%;height:400px; display: none;">&nbsp;</div>
                        <div class="row">
                            <div class="col-md-12 mt-2" id="adds">
                                <a href="javascript:void(0)" class="add-sub-task-btn waves-effect waves-light subTaskHeader">{{__("Add Sub Task")}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
