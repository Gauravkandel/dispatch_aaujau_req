@extends('layouts.vertical', ['title' => __('Routes')])
@section('css')
<link href="{{ asset('assets/libs/selectize/selectize.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@php
use Carbon\Carbon;
@endphp
@section('content')
<style> .addTaskModalHeader{display: none;}</style>
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title-box">
                    <h4 class="page-title">{{__('Edit Route')}}</h4>
                </div>
            </div>
            <div class="col-md-6">
                <div class="page-title-box text-right">
                    <a href="{{ route('tasks.index') }}">
                    <button type="button" class="btn btn-blue" title="Back To List" data-keyboard="false"><span><i class="mdi mdi-chevron-double-left mr-1"></i> Back</span></button>
                    </a>
                </div>
            </div>
        </div>
        <!-- start page title -->
        <input type="hidden" id="order-id" value="{{ $task->id }}">
        <!-- end page title -->
        {!! Form::model($task, ['route' => ['tasks.update', $task->id], 'enctype' => 'multipart/form-data', 'id'=>'taskFormHeader']) !!}
        {{ method_field('PATCH') }}
        @csrf
        <div class="row">

            <div class="col-lg-8">
                <div class="card-box">
                    {{-- @csrf --}}
                    <div class="row d-flex align-items-center" id="dateredio">
                        <div class="col-md-3">
                            <h4 class="header-title mb-3">{{__('Customer')}}</h4>
                        </div>
                        <div class="col-md-5 text-right">
                            <div class="login-form">
                                <ul class="list-inline">
                                    <li class="d-inline-block mr-2">
                                        <input type="radio" class="custom-control-input check" id="tasknow" name="task_type"
                                            value="now">
                                        <label class="custom-control-label" for="tasknow">{{__('Now')}}</label>
                                    </li>
                                    <li class="d-inline-block">
                                        <input type="radio" class="custom-control-input check" id="taskschedule"
                                            name="task_type" value="schedule" checked>
                                        <label class="custom-control-label" for="taskschedule">{{__('Schedule')}}</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <input type="hidden" id="check-pickup-barcode" value="{{ (!empty($task_proofs[0]->barcode_requried) ? $task_proofs[0]->barcode_requried : 0)}}">
                        <input type="hidden" id="check-drop-barcode" value="{{ (!empty($task_proofs[1]->barcode_requried) ? $task_proofs[1]->barcode_requried : 0)}}">
                        <input type="hidden" id="check-appointment-barcode" value="{{ (!empty($task_proofs[2]->barcode_requried) ? $task_proofs[2]->barcode_requried : 0)}}">
                        @php
                            
                            $order = Carbon::createFromFormat('Y-m-d H:i:s', $task->order_time, 'UTC');
                            // $order->setTimezone(isset(Auth::user()->timezone) ? Auth::user()->timezone : 'Asia/Kolkata');
                            $order->setTimezone($client_timezone);
                            $scheduletime = date('Y-m-d H:i:a', strtotime($order));
                        @endphp
                        
                        <div class="col-md-4 datenow">
                            <input type="text" id='datetime-datepicker' name="schedule_time" class="form-control upside opendatepicker"
                                placeholder="{{__('Date Time')}}" value="{{ $scheduletime }}">
                            <button type="button" class="cstmbtn check_btn btn btn-info"><i class="fa fa-check" aria-hidden="true"></i></button>
                        </div>

                    </div>

                    {{-- <span class="span1 searchspan">Please search a customer or add a customer</span> --}}
                    <span class="span1 searchspan"></span>
                    <div class="row searchshow">
                        <div class="col-md-8">
                            <div class="form-group" id="nameInput">
                                <input type="text" id='search' class="form-control" name="search"
                                    placeholder="{{__('Search Customer')}}" value="{{ isset($task->customer->name)?$task->customer->name:'' }}">
                                <input type="hidden" id='cusid' name="ids" value="{{ isset($task->customer->id)?$task->customer->id:'' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="AddressInput">
                                <a href="#" class="add-sub-task-btn">{{__('New Customer')}}</a>
                            </div>
                        </div>
                    </div>

                    <div class="newcus shows">
                        <div class="row ">
                            <div class="col-md-3">
                                <div class="form-group" id="">
                                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Name')]) !!}
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" id="">
                                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Email')]) !!}
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" id="">
                                    {!! Form::text('phone_number', null, ['class' => 'form-control', 'placeholder' => __('Phone Number')]) !!}
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group" id="Inputsearch">
                                    <a href="#" class="add-sub-task-btn">{{__('Previous')}}</a>

                                </div>

                            </div>
                        </div>
                    </div>
                    @php
                        $newcount = 0;
                    @endphp

                    <div class="taskrepet" id="newadd">
                    
                        @foreach ($task->task as $keys => $item)
                            @php
                                $maincount = 0;
                                $newcount++;
                            @endphp
                            
                            <div class="copyin check-validation" id="copyin1">
                                <div class="requried allset">
                                    <div class="row firstclone1">

                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <select class="form-control selecttype mt-1 taskselect" id="task_type"
                                                    name="task_type_id[]" required>
                                                    <option value="1" {{ $item->task_type_id == 1 ? 'selected' : '' }}>
                                                        {{__('Pickup Task')}}</option>
                                                    <option value="2" {{ $item->task_type_id == 2 ? 'selected' : '' }}>{{__('Drop Off Task')}}</option>
                                                    <option value="3" {{ $item->task_type_id == 3 ? 'selected' : '' }}>{{__('Appointment')}}</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group {{ $item->task_type_id == 3 ? 'newclass' : 'appoint' }}" style="display: none;">
                                                <input type="text" class="form-control appointment_date"
                                                    name="appointment_date[]" placeholder="{{__('Duration (In Min)')}}"
                                                    value="{{ $item->allocation_type }}">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong></strong>
                                                </span>
                                            </div>


                                        </div>
                                        <div class="col-md-1 ">

                                            <span class="span1 onedeletex" id="spancheckd" data-taskid="{{ $item->id }}"><img style="filter: grayscale(.5);"
                                                    src="{{ asset('assets/images/ic_delete.png') }}" alt=""></span>
                                        </div>

                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6 d-flex align-items-center">
                                            <h4 class="header-title mb-0">{{__('Address')}}</h4>
                                            <a href="javascript:void(0);" id="clear-address" class="btn btn-info clear-btn ml-3">{{__('Clear')}}</a>
                                        </div>
                                        <div class="col-md-6">
                                            
                                        </div>
                                    </div>
                                    
                                    <span class="span1 addspan">{{__('Please select a address or create new')}}</span>

                                    <div class="row">

                                        <div class="col-lg-8 cust1_add_div" id="add{{ $newcount }}">
                                            <div class="form-group alladdress" id="typeInput">

                                                <div class="row no-gutters row-spacing">
                                                    <div class="col-md-6">
                                                        {!! Form::text('short_name[]', null, ['class' => 'form-control address',
                                                         'placeholder' => __('Address Short Name')]) !!}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" name="address_email[]"
                                                id="add{{ $newcount }}-address_email" class="form-control address address_email"
                                                placeholder="{{__('Email')}}" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group input-group address" id="addressInput">
                                                            <input type="text" id="add{{ $newcount }}-input" name="address[]"
                                                                class="form-control cust1_add" placeholder="{{__('Address')}}">
                                                            <div class="input-group-append">
                                                                <button
                                                                    class="btn btn-xs btn-dark waves-effect waves-light showMapTask cust1_btn"
                                                                    type="button" num="add{{ $newcount }}"> <i
                                                                        class="mdi mdi-map-marker-radius"></i></button>
                                                            </div>
                                                            <input type="hidden" name="latitude[]"
                                                                id="add{{ $newcount }}-latitude" class="cust1_latitude"
                                                                value="0" />
                                                            <input type="hidden" name="longitude[]"
                                                                id="add{{ $newcount }}-longitude" class="cust1_longitude"
                                                                value="0" />
                                                            <span class="invalid-feedback" role="alert" id="address">
                                                                <strong></strong>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" name="address_phone_number[]"
                                                        id="add{{ $newcount }}-address_phone_number" class="form-control address address_phone_number"
                                                        placeholder="{{__('Phone Number')}}" />
                                                    </div>
                                                    {{-- @if($preference->route_flat_input == 1) --}}
                                                    <div class="col-md-6">
                                                        <input type="text" name="flat_no[]"
                                                        id="add{{ $newcount }}-flat_no" value="{{ $item->flat_no }}" class="form-control address flat_no"
                                                        placeholder="{{__('House/Apartment/Flat no')}}" />
                                                    </div>
                                                    {{-- @endif --}}
                                                    <div class="col-md-6">
                                                        <input type="text" name="post_code[]"
                                                        id="add{{ $newcount }}-postcode" class="form-control address postcode"
                                                        placeholder="{{__('PostsCode')}}" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row no-gutters">
                                                            <div class="col-6 pr-1">
                                                                {!! Form::text('barcode[]', $item->barcode, ['class' => 'form-control barcode','placeholder' => __('Task Barcode')]) !!}  
                                                            </div>
                                                            <div class="col-6 pl-1">
                                                                {!! Form::text('quantity[]', $item->quantity, ['class' => 'form-control quantity onlynumber','placeholder' => __('Quantity')]) !!}
                                                            </div>
                                                            <span class="span1 pickup-barcode-error" style="display:none;">{{__('Task Barcode is required for pickup')}}</span>
                                                            <span class="span1 drop-barcode-error" style="display:none;">{{__('Task Barcode is required for drop')}}</span>
                                                            <span class="span1 appointment-barcode-error" style="display:none;">{{__('Task Barcode is required for appointment')}}</span>
                                                        </div> 
                                                    </div>
                                                    @if($preference->route_alcoholic_input == 1)
                                                    <div class="col-md-6">
                                                        <div class="custom-switch redio-all">
                                                            <input type="checkbox" value="1" class="custom-control-input large-icon alcoholic_item" id="add{{ $newcount }}-alcoholic_item" name="alcoholic_item[]" {{ ($item->alcoholic_item == 1)? 'checked' : ''}}>
                                                            <label class="custom-control-label checkss alcoholic_item_label" for="add{{ $newcount }}-alcoholic_item">{{__("Alcoholic Item")}}</label>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    {{-- <div class="col-md-6">
                                                        <span>Due After</span>
                                                        {!! Form::time('due_after[]', $item->due_after, ['class' => 'form-control due_after', 'placeholder' => 'Due After']) !!}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span>Due Before</span>
                                                        {!! Form::time('due_before[]', $item->due_before, ['class' => 'form-control due_before','placeholder' => 'Due Before']) !!}
                                                    </div> --}}

                                                </div>
                                                <span class="invalid-feedback" role="alert">
                                                    <strong></strong>
                                                </span>
                                            </div>

                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group withradio" id="typeInputss">

                                                <div class="oldhide">

                                                    <img class="showsimage"
                                                        src="{{ url('assets/images/ic_location_placeholder.png') }}"
                                                        alt="">
                                                </div>
                                                @if(isset($task->customer->location))
                                                <?php
                                                $locationarray1 = [];
                                                $locationarray2 = [];
                                                foreach ($task->customer->location as $singlelocation) {
                                                    if($singlelocation->id == $item->location_id)
                                                    {
                                                        $locationarray1[] = $singlelocation;
                                                    }else{
                                                        $locationarray2[] = $singlelocation;
                                                    }
                                                }
                                                $finallocationarray = array_merge($locationarray1,$locationarray2);
                                               



                                                ?>
                                                @foreach ($finallocationarray as $key => $items)

                                                    <div class="append">
                                                        <div class="custom-control custom-radio"><input type="radio"
                                                                id="{{ $keys }}{{ $items->id }}{{ 12 }}"
                                                                name="old_address_id{{ $keys != 0 ? $keys : '' }}"
                                                                value="{{ $items->id }}"
                                                                data-srtadd="{{ $items->short_name }}" 
                                                                data-adr="{{ $items->address }}" 
                                                                data-lat="{{ $items->latitude }}" 
                                                                data-long="{{ $items->longitude }}" 
                                                                data-pstcd="{{ $items->post_code }}" 
                                                                data-flat_no="{{ $items->flat_no }}" 
                                                                data-emil="{{ $items->email }}" 
                                                                data-ph="{{ $items->phone_number }}"
                                                                {{ $item->location_id == $items->id ? 'checked' : '' }}
                                                                class="custom-control-input redio old-select-address">
                                                                <label
                                                                class="custom-control-label"
                                                                for="{{ $keys }}{{ $items->id }}{{ 12 }}"><span
                                                                    class="spanbold">{{ $items->short_name }}</span>-{{ $items->address }}</label>
                                                        </div>
                                                    </div>

                                                @endforeach
                                                @endif
                                                {{-- alllocations --}}
                                                <?php
                                                if(count($alllocations)>0)
                                                {
                                                    foreach($alllocations as $key => $items)
                                                    {?>
                                                        <div class="append">
                                                            <div class="custom-control custom-radio"><input type="radio"
                                                                    id="{{ $keys }}{{ $items->id }}{{ 12 }}"
                                                                    name="old_address_id{{ $keys != 0 ? $keys : '' }}"
                                                                    value="{{ $items->id }}"
                                                                    {{ $item->location_id == $items->id ? 'checked' : '' }}
                                                                    class="custom-control-input redio"><label
                                                                    class="custom-control-label"
                                                                    for="{{ $keys }}{{ $items->id }}{{ 12 }}"><span
                                                                        class="spanbold">{{ $items->short_name }}</span>-{{ $items->address }}</label>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } ?>
                                                    
                                                    
                                                    

                                                @php $maincount++; @endphp
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="new3">
                                </div>
                            </div>
                        @endforeach
                        <input type="hidden" id="newcount" value="{{$newcount}}">
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="adds">
                            <a href="#" class="add-sub-task-btn waves-effect waves-light subTask">{{__('Add Sub Task')}}</a>
                        </div>
                    </div>

                    <!-- end row -->

                    <!-- container -->
                    <h4 class="header-title mb-3">{{__('Task Description')}}</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group" id="make_modelInput">
                                {!! Form::hidden('recipient_phone', null, ['class' => 'form-control rec', 'placeholder' =>
                                __('Recipient Phone')]) !!}
                                {!! Form::hidden('Recipient_email', null, ['class' => 'form-control rec', 'placeholder' =>
                                __('Recipient Email')]) !!}
                                {{-- {!! Form::textarea('task_description', null, ['class' => 'form-control', 'placeholder' =>
                                'Task Description', 'rows' => 2, 'cols' => 40]) !!} --}}
                                <textarea class='form-control' placeholder="{{__('Please enter task description')}}" rows='2' cols='40' name="task_description">{{$task->task_description}}</textarea>
                                {!! Form::hidden('net_quantity', null, ['class' => 'form-control rec mt-1', 'placeholder' =>
                                __('Net Quantity')]) !!}
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>

                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group text-center" id="colorInput">
                                <label class="btn btn-info width-lg waves-effect waves-light newchnageimage upload-img-btn">
                                    <span><i class="fas fa-image mr-2"></i>{{__('Upload Image')}}</span>
                                    <input id="file" type="file" name="file[]" multiple style="display: none" />
                                </label>
                                
                                @if (count($images) && $images[0] == '')
                                    <img class="showsimagegall d-block m-auto" src="{{ url('assets/images/ic_image_placeholder.png') }}"
                                        alt="">
                                @endif

                                <div class="allimages">
                                    <div id="imagePreview" class="privewcheck d-flex justify-content-center flex-wrap">
                                        @if (count($images) > 0 && $images[0] != '')
                                            @foreach ($images as $i => $item)
                                                <div class="imagepri_wrap mb-2 saved" data-id="{{ $i }}">
                                                    <img src="{{ $main }}{{ $item }}" class="imagepri mr-2" />
                                                    <button type="button" class="close imagepri_close saved" aria-hidden="true">×</button>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @if($task->call_back_url)
                    <h4 class="header-title mb-3">{{__('Call Back URL')}}</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group" id="make_modelInput">
                                {!! Form::text('call_back_url', null, ['class' => 'form-control rec', 'placeholder' =>
                                __('Call Back URL'),'readonly']) !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    <h4 class="header-title mb-3">{{__('Allocation')}}</h4>
                    <div class="row my-3" id="rediodiv">
                        <div class="col-md-8">
                            <div class="login-form">
                                <ul class="list-inline">
                                    <li class="d-inline-block mr-2">
                                        <input type="radio" class="custom-control-input check assignRadio" id="customRadio"
                                            name="allocation_type" value="u"
                                            {{ $task->auto_alloction == 'u' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customRadio">{{__('Unassigned')}}</label>
                                    </li>
                                    <li class="d-inline-block mr-2">
                                        <input type="radio" class="custom-control-input check assignRadio"
                                            id="customRadio22" name="allocation_type" value="a"
                                            {{ $task->auto_alloction == 'a' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customRadio22">{{__('Auto Allocation')}}</label>
                                    </li>
                                    <li class="d-inline-block">
                                        <input type="radio" class="custom-control-input check assignRadio"
                                            id="customRadio33" name="allocation_type" value="m"
                                            {{ $task->auto_alloction == 'm' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customRadio33">{{__('Manual')}}</label>
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4 class="header-title">{{__('Cash to be collected')}}</h4>
                            <input class="form-control" type="text" placeholder="{{__('Cash to be collected')}}"
                                name="cash_to_be_collected"
                                value="{{ isset($task->cash_to_be_collected) ? $task->cash_to_be_collected : '' }}">
                        </div>
                    </div>
                    {{-- <span class="span1 tagspan">Please select atlest one tag for {{getAgentNomenclature()}} and agent</span> --}}
                    <span class="span1 tagspan"></span>
                    <div class="tags">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>{{__('Team Tag')}}</label>
                                    <select name="team_tag[]" id="selectize-optgroups" multiple placeholder="Select tag...">
                                        <option value="">{{__('Select Tag...')}}</option>
                                        @foreach ($teamTag as $item)
                                            <option value="{{ $item->id }}"
                                                {{ in_array($item->id, $saveteamtag) ? 'selected' : '' }}>{{ $item->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>{{__(getAgentNomenclature().' Tag')}}</label>
                                    <select name="agent_tag[]" id="selectize-optgroup" multiple placeholder="Select tag...">
                                        <option value="">{{__('Select Tag...')}}</option>
                                        @foreach ($agentTag as $item)
                                            <option value="{{ $item->id }}"
                                                {{ in_array($item->id, $savedrivertag) ? 'selected' : '' }}>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row drivers">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>{{__(getAgentNomenclature().'s')}}</label>
                                <select class="form-control" name="agent" id="location_accuracy">
                                    @foreach ($agents as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $task->driver_id == $item->id ? 'selected' : '' }}>{{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-block btn-lg btn-blue waves-effect waves-light submitUpdateTaskHeader">{{__('Submit')}}</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        {!! Form::close() !!}

    </div>

    <div id="show-map-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header border-0">
                    <h4 class="modal-title">{{__('Select Location')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body px-3 py-0">

                    <div class="row">
                        <form id="task_form" action="#" method="POST" style="width: 100%">
                            <div class="col-md-12">
                                <div id="googleMap" style="height: 500px; min-width: 500px; width:100%"></div>
                                <input type="hidden" name="lat_input" id="lat_map" value="0" />
                                <input type="hidden" name="lng_input" id="lng_map" value="0" />
                                <input type="hidden" name="address_input" id="addredd_map" value="">
                                <input type="hidden" name="for" id="map_for" value="" />
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light selectMapLocation">{{__('Ok')}}</button>
                    <!--<button type="Cancel" class="btn btn-blue waves-effect waves-light cancelMapLocation">cancel</button>-->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/pages/form-advanced2.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script> --}}
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    
    <script>
        var savedFileListArray = {!! json_encode($images) !!};
    </script>
    @include('tasks.updatepagescript')
    
@endsection
