<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            @csrf
            <div class="row d-flex align-items-center" id="dateredio">
                
                <div class="col-md-3">
                    <h4 class="header-title mb-3">Customer</h4>
                </div>
                <div class="col-md-5 text-right">
                    <div class="login-form">
                        <ul class="list-inline">
                            <li class="d-inline-block mr-2">
                                <input type="radio" class="custom-control-input check" id="tasknow"
                                name="task_type" value="now" checked>
                                <label class="custom-control-label" for="tasknow">Now</label>
                            </li>
                            <li class="d-inline-block">
                                <input type="radio" class="custom-control-input check" id="taskschedule"
                                name="task_type" value="schedule" >
                                <label class="custom-control-label" for="taskschedule">Schedule</label>
                            </li>
                          </ul>
                        </div>
                </div>
                <div class="col-md-4 datenow">
                    <input type="text" id='datetime-datepicker' name="schedule_time"
                        class="form-control upside" placeholder="Date Time">
                </div>
            </div>

            <span class="span1 searchspan">Please search a customer or add a customer</span>
            <div class="row searchshow">
                <div class="col-md-8">
                    <div class="form-group" id="nameInput">

                        {!! Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'Search Customer', 'id' => 'search']) !!}
                        <input type="hidden" id='cusid' name="ids" readonly>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" id="AddressInput">
                        <a href="#" class="add-sub-task-btn">New Customer</a>

                    </div>
                </div>

            </div>

            <div class="row newcus shows">
                <div class="col-md-3">
                    <div class="form-group" id="">
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" id="">
                        {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" id="">
                        {!! Form::text('phone_number', null, ['class' => 'form-control', 'placeholder' => 'Phone Number',
                        ]) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" id="Inputsearch">
                        <a href="#" class="add-sub-task-btn">Previous</a>

                    </div>

                </div>
            </div>

            <div class="taskrepet" id="newadd">
                <div class="copyin1" id="copyin1">
                  <div class="requried allset">
                    <div class="row firstclone1">
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <select class="form-control selecttype mt-1 taskselect" id="task_type"  name="task_type_id[]" required>
                                    <option value="1">Pickup Task</option>
                                    <option value="2">Drop Off Task</option>
                                    <option value="3">Appointment</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group appoint mt-1">
                                {!! Form::text('appointment_date[]', null, ['class' => 'form-control
                                appointment_date', 'placeholder' => 'Duration (In Min)']) !!}
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>


                        </div>
                        <div class="col-md-1 text-center pt-2" >
                            
                        <span class="span1 onedelete" id="spancheck"><img style="filter: grayscale(.5);" src="{{asset('assets/images/ic_delete.png')}}"  alt=""></span>


                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="header-title mb-2">Address</h4>
                        </div>
                        <div class="col-md-6">
                            {{-- <h4 class="header-title mb-2">Saved Addresses</h4> --}}
                        </div>
                    </div>
                    
                    <span class="span1 addspan">Please select a address or create new</span>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group alladdress" id="typeInput">
                                {!! Form::text('short_name[]', null, ['class' => 'form-control address',
                                'placeholder' => 'Address Short Name','required' => 'required']) !!}

                                <input type="text" id="#add1-input" name="address[]" class="form-control address cust_add" placeholder="Address">
                                <div class="input-group-append">
                                    <button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="add1"> <i class="mdi mdi-map-marker-radius"></i></button>
                                </div>
                                <input type="hidden" name="latitude[]" id="add1-latitude" value="0" class="cust_latitude" />
                                <input type="hidden" name="longitude[]" id="add1-longitude" value="0" class="cust_longitude" />
                                {!! Form::text('post_code[]', null, ['class' => 'form-control address','placeholder' => 'Post Code','required' => 'required']) !!}
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6" id="onlyFirst">
                            <div class="form-group withradio" id="typeInputss">
                                <div class="oldhide">
                                    <img class="showsimage" src="{{url('assets/images/ic_location_placeholder.png')}}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
            <div class="row">
                <div class="col-md-12" id="adds">
                    <a href="#" class="add-sub-task-btn waves-effect waves-light subTaskAdd">Add Sub
                        Task</a>
                </div>
            </div>

            <!-- end row -->

            <!-- container -->
            <h4 class="header-title mb-2">Meta Data</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="make_modelInput">
                        {!! Form::text('recipient_phone', null, ['class' => 'form-control rec', 'placeholder' =>
                        'Recipient Phone', 'required' => 'required']) !!}
                        {!! Form::email('recipient_email', null, ['class' => 'form-control rec', 'placeholder'
                        => 'Recipient Email', 'required' => 'required']) !!}
                            {!! Form::textarea('task_description', null, ['class' => 'form-control',
                            'placeholder' => 'Task_description', 'rows' => 2, 'cols' => 40]) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                       
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>

                </div>
                <div class="col-md-6">
                   
                    <div class="form-group" id="colorInput">
                        <label class="btn btn-info width-lg waves-effect waves-light newchnageimage upload-img-btn">
                            <span><i class="fas fa-image mr-2"></i>Upload Image</span>
                            <input id="file" type="file" name="file[]" multiple style="display: none"/>
                        </label>
                        <img class="showsimagegall" src="{{url('assets/images/ic_image_placeholder.png')}}" alt="">
                        <div class="allimages">
                          <div id="imagePreview" class="privewcheck"></div>
                        </div>
                    </div>

                </div>
            </div>

            <h4 class="header-title mb-3">Allocation</h4>
            <div class="row my-3" id="rediodiv">
                <div class="col-md-12">
                    <div class="login-form">
                        <ul class="list-inline">
                            <li class="d-inline-block mr-2">
                                <input type="radio" class="custom-control-input check" id="customRadio"
                                name="allocation_type" value="u" checked>
                            <label class="custom-control-label" for="customRadio">Unassigned</label>
                            </li>
                            <li class="d-inline-block mr-2">
                                <input type="radio" class="custom-control-input check" id="customRadio22"
                                name="allocation_type" value="a">
                            <label class="custom-control-label" for="customRadio22">Auto Allocation</label>
                            </li>
                            <li class="d-inline-block">
                                <input type="radio" class="custom-control-input check" id="customRadio33"
                                name="allocation_type" value="m">
                            <label class="custom-control-label" for="customRadio33">Manual</label>
                            </li>
                          </ul>
                        </div>
                </div>
                {{-- <div class="col-md-4 padd">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input check" id="customRadio"
                            name="allocation_type" value="u" checked>
                        <label class="custom-control-label" for="customRadio">Un-Assigned</label>
                    </div>
                </div>
                <div class="col-md-4 padd">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input check" id="customRadio22"
                            name="allocation_type" value="a">
                        <label class="custom-control-label" for="customRadio22">Auto Allocation</label>
                    </div>
                </div>
                <div class="col-md-4 padd">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input check" id="customRadio33"
                            name="allocation_type" value="m">
                        <label class="custom-control-label" for="customRadio33">Manual</label>
                    </div>
                </div> --}}
            </div>
            <span class="span1 tagspan">Please select atlest one tag for {{getAgentNomenclature()}} and {{getAgentNomenclature()}}</span>
            <div class="row tags">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Team Tag</label>
                        <select name="team_tag[]" id="selectize-optgroups" multiple placeholder="Select tag...">
                            <option value="">Select Tag...</option>
                            @foreach ($teamTag as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>{{getAgentNomenclature()}} Tag</label>
                        <select name="agent_tag[]" id="selectize-optgroup" multiple placeholder="Select tag...">
                            <option value="">Select Tag...</option>
                            @foreach ($agentTag as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row drivers">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label>{{getAgentNomenclature()}}s</label>
                        <select class="form-control" name="agent" id="driverselect">
                            @foreach ($agents as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- <div class="col-md-5"></div> --}}
                <div class="col-md-12">
                    <button type="submit" class="btn btn-block btn-lg btn-blue waves-effect waves-light">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>