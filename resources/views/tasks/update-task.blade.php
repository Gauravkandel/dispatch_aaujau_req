@extends('layouts.vertical', ['title' => __('Routes')])
@section('css')
<link href="{{ asset('assets/libs/selectize/selectize.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('tracking/css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('tracking/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('tracking/css/responsive.css') }}">
@endsection
@php
use Carbon\Carbon;
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
$task_type_array = [__('Pickup'), __('Drop-Off'), __('Appointment')];
@endphp
@section('content')

<style> .addTaskModalHeader{display: none;}
.map_box #map_canvas{
    height:300px;
}
</style>
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{__('Edit Route')}}    

                    <a href="{{ route('tasks.index') }}" class="float-right">
                    <button type="button" class="btn btn-blue" title="Back To List" data-keyboard="false"><span><i class="mdi mdi-chevron-double-left mr-1"></i> Back</span></button>
                    </a>

                    </h4>
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
            <div class="col-sm-12 col-xl-9 col-md-7">
                <div class="card-box p-3">            
                    <div class="row d-flex">
                        <div class="col-sm-12 col-xl-4 col-md-12" style="border-right: 1px solid #ccc;">
                            @csrf
                            <div class="row mb-2" id="dateredio">
                                <div class="col-md-12">
                                    <div class="radio radio-primary form-check-inline mr-3">
                                        <input type="radio" id="tasknow" value="now" name="task_type" class="checkschedule" >
                                        <label for="tasknow"> {{__("Add Now")}} </label>
                                    </div>
                                    <div class="radio radio-info form-check-inline">
                                        <input type="radio" id="taskschedule" value="schedule" class="checkschedule" name="task_type" checked>
                                        <label for="taskschedule"> {{__("Schedule For Later")}} </label>
                                    </div>
                                </div>
                            </div>
                            @php
                            
                                $order = Carbon::createFromFormat('Y-m-d H:i:s', $task->order_time, 'UTC');
                                // $order->setTimezone(isset(Auth::user()->timezone) ? Auth::user()->timezone : 'Asia/Kolkata');
                                $order->setTimezone($client_timezone);
                                $scheduletime = date('Y-m-d H:i:s', strtotime($order));
                            @endphp
                            <div class="row mb-3 datenow">
                                <div class="col-md-12">
                                    <input type="text" id='datetime-datepicker' name="schedule_time" class="form-control upside opendatepicker" placeholder="{{__('Date Time')}}" value="{{ $scheduletime }}">
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
                            <div class="row searchshow">
                                <div class="col-md-12">
                                    <div class="form-group" id="nameInput">
                                        <input type="text" id='search' class="form-control" name="search"
                                            placeholder="{{__('Search Customer')}}" value="{{ isset($task->customer->name)?$task->customer->name:'' }}">
                                        <input type="hidden" id='cusid' name="ids" value="{{ isset($task->customer->id)?$task->customer->id:'' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="newcustomer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" id="">
                                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Name'),'id'=>'name_new']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="form-group" id="">
                                        {!! Form::text('email', isset($task->customer->email)?$task->customer->email:'', ['class' => 'form-control email', 'placeholder' => __('Email'),'id'=>'email_new']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" id="phone_numberInput">
                                        <div class="input-group">
                                        {!! Form::text('phone_number', isset($task->customer->phone_number)?$task->customer->phone_number:'', ['class' => 'form-control phone_number', 'placeholder' => __('Phone Number'),'id'=> 'phone_new'
                                        ]) !!}
                                        <input type="hidden" id="dialCode" name="dialCode" value="{{isset($task->customer->dial_code)?$task->customer->dial_code:''}}">
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
                            <div class="meta_data_task_div" style="display:{{($task->task_description!='' || $task->images_array!='' > 0)?'block':'none'}};">
                                <div class="row mb-2">
                                    <div class="col-md-12" id="make_modelInput">
                                        {!! Form::hidden('recipient_phone', null, ['class' => 'form-control rec', 'placeholder' =>
                                        __('Recipient Phone')]) !!}
                                        {!! Form::hidden('Recipient_email', null, ['class' => 'form-control rec', 'placeholder' =>
                                        __('Recipient Email')]) !!}
                                        {{-- {!! Form::textarea('task_description', null, ['class' => 'form-control', 'placeholder' =>
                                        'Task Description', 'rows' => 2, 'cols' => 40]) !!} --}}

                                        <textarea class='form-control' placeholder="{{__('Please enter task description')}}" rows='3' cols='40' name="task_description">{{$task->task_description}}</textarea>
                                        {!! Form::hidden('net_quantity', null, ['class' => 'form-control rec mt-1', 'placeholder' =>
                                        __('Net Quantity')]) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <input type="file" data-plugins="dropify" class="dropify" name="file[]" multiple data-height="300" accept="image/*"/>
                                    </div>
                                </div>

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
                            
                            <div class="row mb-2">
                                <div class="col-md-12"  id="make_modelInput">
                                    {!! Form::text('call_back_url', $task->call_back_url, ['class' => 'form-control rec', 'placeholder' => __('Call Back URL')]) !!}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12" id="cash_to_be_collectedInput">
                                    <input class="form-control" type="text" placeholder='{{__("Cash to be collected")}}' name="cash_to_be_collected" value="{{ isset($task->cash_to_be_collected) ? $task->cash_to_be_collected : '' }}">
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>

                            <h4 class="header-title mb-2">{{__("Allocation")}}</h4>
                            <div class="row mb-2" id="rediodiv">
                                <div class="col-md-12">
                                    <div class="radio radio-primary form-check-inline mr-2">
                                        <input type="radio" id="customRadio" value="u" name="allocation_type" class="assignRadio" {{ $task->auto_alloction == 'u' ? 'checked' : '' }}>
                                        <label for="customRadio"> {{__("Unassigned")}} </label>
                                    </div>
                                    <div class="radio radio-info form-check-inline mr-2">
                                        <input type="radio" id="customRadio22" value="a" name="allocation_type" class="assignRadio" {{ $task->auto_alloction == 'a' ? 'checked' : '' }}>
                                        <label for="customRadio22"> {{__("Auto Allocation")}} </label>
                                    </div>
                                    <div class="radio radio-warning form-check-inline">
                                        <input type="radio" id="customRadio33" value="m" name="allocation_type" class="assignRadio" {{ $task->auto_alloction == 'm' ? 'checked' : '' }}>
                                        <label for="customRadio33"> {{__("Manual")}} </label>
                                    </div>
                                </div>
                            </div>
                            
                            <span class="span1 tagspan">{{__("Please select atlest one tag for ".getAgentNomenclature())}}</span>
                            <div class="tags">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__("Team Tag")}}</label>
                                            <select name="team_tag[]" id="selectize-optgroups" class="selectizeInput" multiple placeholder={{__("Select tag...")}}>
                                                <option value="">{{__("Select Tag...")}}</option>
                                                @foreach ($teamTag as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ in_array($item->id, $saveteamtag) ? 'selected' : '' }}>{{ $item->name }}
                                                    </option>
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
                                                    <option value="{{ $item->id }}"
                                                        {{ in_array($item->id, $savedrivertag) ? 'selected' : '' }}>
                                                        {{ $item->name }}</option>
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
                        </div>
                        @php
                            $newcount = 0;
                        @endphp
                        <div class="col-sm-12 col-xl-8 col-md-12">
                            <h4 class="header-title mb-2">{{__("Tasks")}}</h4>
                            <span class="span1 addspan">{{__("Please select a address or create new")}}</span>
                            <div class="taskrepet" id="newadd">
                                @foreach ($task->task as $keys => $item)
                                @php
                                    $maincount = 0;
                                    $newcount++;
                                @endphp
                                <div class="alTaskType copyin check-validation" id="copyin1">
                                    <div class="alFormTaskType row m-0 pt-1 pb-1">
                                        <div class="col-sm-10 col-md-12">
                                            <div class="row firstclone1">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-1">
                                                        <select class=" selecttype mt-1" id="task_type"  name="task_type_id[]" style="width:100%;" required>
                                                            <option value="1" {{ $item->task_type_id == 1 ? 'selected' : '' }}>
                                                            {{__('Pickup Task')}}</option>
                                                            <option value="2" {{ $item->task_type_id == 2 ? 'selected' : '' }}>{{__('Drop Off Task')}}</option>
                                                            <option value="3" {{ $item->task_type_id == 3 ? 'selected' : '' }}>{{__('Appointment')}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group mt-1 mb-1 {{ $item->task_type_id == 3 ? 'newclass' : 'appoint' }}" style="display: none;">
                                                        {!! Form::text('appointment_date[]', $item->appointment_duration, ['class' => 'form-control appointment_date', 'placeholder' => __('Duration (In Min)')]) !!}
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong></strong>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 text-center pt-2 pr-2" >
                                                <span class="span1 onedeletex" id="spancheckd" data-taskid="{{ $item->id }}"><img style="filter: grayscale(.5);"
                                                    src="{{ asset('assets/images/ic_delete.png') }}" alt=""></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="alCol-12 mainaddress col-8" id="add{{ $newcount }}">
                                                    <div class="row">
                                                        <div class="col-6 addressDetails border-right">
                                                            <h6>Address Details</h6>
                                                            <div class="row">
                                                                <div class="form-group col-6 mb-1">
                                                                    {!! Form::text('short_name[]', null, ['class' => 'form-control address', 'placeholder' => __('Short Name')]) !!}
                                                                </div>
                                                                <div class="input-group form-group col-6 mb-2">
                                                                    <input type="text" id="add{{ $newcount }}-input" name="address[]" class="form-control address cust1_add" placeholder='{{__("Location")}}'>
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-xs btn-dark waves-effect waves-light showMapHeader cust1_btn" type="button" num="add{{ $newcount }}"> <i class="mdi mdi-map-marker-radius"></i></button>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-6 mb-1">
                                                                    {!! Form::text('flat_no[]', null, ['class' => 'form-control address flat_no','placeholder' => __('House/Apartment/Flat no'),'id'=>'add'.$newcount.'-flat_no']) !!}
                                                                </div>
                                                                <div class="form-group col-6 mb-1">
                                                                <input type="hidden" name="latitude[]" id="add{{ $newcount }}-latitude" class="cust1_latitude" value="0" />
                                                            <input type="hidden" name="longitude[]" id="add{{ $newcount }}-longitude" class="cust1_longitude" value="0" />
                                                                    {!! Form::text('post_code[]', null, ['class' => 'form-control address postcode','placeholder' => __('Post Code'),'id'=>'add'.$newcount.'-postcode']) !!}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="alContactOther col-6">
                                                            <div class="row">
                                                                <div class="col-6 alRightBorder">
                                                                    <h6>Contact Details</h6>
                                                                    <div class="row">
                                                                        <div class="form-group mb-1 col-12">
                                                                            {!! Form::text('address_email[]', null, ['class' => 'form-control address address_email','placeholder' => __('Email'),'id'=>'add'.$newcount.'-address_email']) !!}
                                                                        </div>
                                                                        <div class="form-group mb-1 col-12">
                                                                            {!! Form::text('address_phone_number[]', null, ['class' => 'form-control address address_phone_number','placeholder' => __('Phone Number'),'id'=>'add'.$newcount.'-address_phone_number']) !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <h6>Other Details</h6>
                                                                    <div class="row">
                                                                        <div class="form-group mb-1 col-12">
                                                                            {!! Form::text('barcode[]', $item->barcode, ['class' => 'form-control barcode','placeholder' => __('Task Barcode')]) !!}
                                                                        </div>
                                                                        <div class="form-group mb-1 col-12">
                                                                            {!! Form::text('quantity[]', $item->quantity, ['class' => 'form-control quantity onlynumber','placeholder' => __('Quantity')]) !!}
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
                                                    <div class="form-group editwithradio" id="typeInputss">
                                                        <div class="oldhide text-center">
                                                            <img class="showsimage" src="{{url('assets/images/ic_location_placeholder.png')}}" alt="">
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
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <input type="hidden" id="newcount" value="{{$newcount}}">
                                <div id="addSubFields" style="width:100%;height:400px; display: none;">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-12 mt-2" id="adds">
                                        <a href="#" class="add-sub-task-btn waves-effect waves-light subTask">{{__('Add Sub Task')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($task->status!='completed')
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-block btn-lg btn-blue waves-effect waves-light submitUpdateTaskHeader">{{__('Submit')}}</button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-sm-12 col-xl-3 col-md-5">
                <div class="card-box p-3">            
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="header-title mb-2">{{__("Order Tracking")}}</h4>
                            <div class="row no-gutters">
                                <div class="col-12 mb-4">
                                <div class="site_link position-relative">
                                    <a href="{{url('/order/tracking/'.Auth::user()->code.'/'.$task->unique_id.'')}}" target="_blank"><span id="pwd_spn" class="password-span">{{url('/order/tracking/'.Auth::user()->code.'/'.$task->unique_id.'')}}</span></a>
                                    <label class="copy_link float-right" id="cp_btn" title="copy">
                                        <img src="{{ URL::to('/assets/icons/domain_copy_icon.svg') }}" alt="">
                                        <span class="copied_txt" id="show_copy_msg_on_click_copy" style="display:none;">{{__('Copied')}}</span>
                                    </label>
                                </div>
                                </div>
                            </div>                  
                            <div class="row no-gutters">
                                <div class="col-12">
                                    <div class="map_box">
                                        <div id="map_canvas"></div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="location_box position-relative get_div_height">
                                <div class="row">
                                    <div class="col-md-12">
                                        <i class="fas fa-chevron-up detail_btn d-lg-none d-block show_attr_classes"></i>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-lg-12 padd-left mb-4">
                                                <div class="left-icon">
                                                    <img src="{{ 'https://imgproxy.royodispatch.com/insecure/fit/300/100/sm/0/plain/' . Storage::disk('s3')->url($order->profile_picture ?? 'assets/client_00000051/agents605b6deb82d1b.png/XY5GF0B3rXvZlucZMiRQjGBQaWSFhcaIpIM5Jzlv.jpg') }}"
                                                        alt="" />
                                                </div>
                                                <h5>{{ isset($task->name) ? $order->name :__(getAgentNomenclature().' not assigned yet') }}</h5>
                                                <p>{{ $task->phone_number }}</p>
                                            </div>
                                            <span class="col-lg-12 attrbute_classes">
                                                <div class="row align-items-center">
                                                    @foreach ($task->task as $item)
                                                        <div class="col-lg-6 d-flex align-items-center address_box mb-3">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            <div class="right_text">
                                                                <h4>{{ $task_type_array[$item->task_type_id - 1] }}</h4>
                                                                <p>{{ $item->address }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row no-gutters">
                                <div class="col-sm-12 btn_group d-flex align-items-center justify-content-between">
                                    <a class="btn pink_btn" href="tel:{{ $task->phone_number }}"><i
                                            class="fas fa-phone-alt position-absolute"></i><span>{{__('Call')}}</span></a>
                                    <a class="btn pink_btn" href="sms:{{ $task->phone_number }}"><i
                                            class="fas fa-comment position-absolute"></i><span>{{__('Message')}}</span></a>
                                </div>
                            </div>
                             
                        </div>
                    </div>   
                </div>                                                

                <div class="card-box p-3">            
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="header-title mb-2">{{__("Route Proofs")}}</h4>
                            <div class="row">
                                @foreach($task->task as $keys => $item)
                                <div class="col-md-12 all-remove">
                                    <div class="task-card">
                                        <div class="assigned-block bg-transparent"><h5>      
                                        @if($item->task_type_id == 1)    
                                        {{__('Pickup Task')}}
                                        @elseif($item->task_type_id == 2)
                                        {{__('Drop Off Task')}}
                                        @else
                                        {{__('Appointment')}}
                                        @endif
                                        </h5>
                                        </div>
                                        <div class="row">
                                        @if(($item->proof_image != '' && $item->proof_image != NULL) || ($item->proof_signature != '' && $item->proof_signature != NULL) || ($item->note != '' && $item->note != NULL))  
                                            
                                            @if($item->proof_image != '' && $item->proof_image != NULL)
                                            <div class="col-md-12">
                                                <label class="mb-1">{{__('Image')}}</label>
                                                <div class="status-wrap-block">
                                                    <div class="image-wrap-sign">
                                                        <a data-fancybox="images" href="{{$item->proof_image}}"><img src="https://imgproxy.royodispatch.com/insecure/fit/400/400/sm/0/plain/{{$item->proof_image}}" alt=""></a>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if($item->proof_signature != '' && $item->proof_signature != NULL)
                                            <div class="col-md-12">
                                                <label class="mb-1">{{__('Signature')}}</label>
                                                <div class="status-wrap-block">
                                                    <div class="image-wrap-sign">
                                                        <a data-fancybox="images" href="{{$item->proof_signature}}"><img src="https://imgproxy.royodispatch.com/insecure/fit/400/400/sm/0/plain/{{$item->proof_signature}}" alt=""></a>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if($item->note != '' && $item->note != NULL)
                                            <div class="col-md-12">
                                                <label class="mb-1">{{__('Notes')}}</label>
                                                <div class="status-wrap-block">
                                                    <div class="image-wrap-sign">
                                                    <span>{{$item->note}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        @else
                                            <div class="col-12 text-center">{{__('No Proof Found')}}</div>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card-box rejection-box style-4">
                    <h4 class="header-title mb-2">{{__('Rejections')}}</h4>
                    @if(!empty($task->task_rejects) && count($task->task_rejects) > 0)
                    @php
                    $timeformat = $preference->time_format == '24' ? 'H:i:s':'g:i a';
                    $preference->date_format = $preference->date_format ?? 'd-M-Y';
                    @endphp

                    @foreach($task->task_rejects as $task_reject)
                    @php
                    $rejection_time = Carbon::createFromFormat('Y-m-d H:i:s', $task_reject->created_at, 'UTC');
                    $rejection_time->setTimezone($client_timezone);
                    @endphp
                    <div class="row align-items-center mb-2">
                        <div class="col-2 pr-0 pic-left">
                            <img src="{{ !empty($task_reject->agent->profile_picture) ? $imgproxyurl.Storage::disk('s3')->url($task_reject->agent->profile_picture) : URL::to('/assets/images/user_dummy.jpg') }}" alt="{{__('contact-img')}}" title="{{__('contact-img')}}" class="rounded-circle avatar-sm">
                        </div>
                        <div class="col-10 pl-1">
                            <h5 class="mb-1  mt-0 font-weight-normal">{{ (isset($task_reject->agent->name))?$task_reject->agent->name:'' }}</h5>
                            <p class="mb-0">{{date(''.$preference->date_format.' '.$timeformat.'', strtotime($rejection_time))}}</p>
                        </div>
                    </div>

                    @endforeach
                    @else
                    {{__('No rejection found')}}
                    @endif
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
    <script src="{{ asset('tracking/js/common.js') }}"></script>
    <script>
        var savedFileListArray = {!! json_encode($images) !!};
    </script>
    @include('tasks.updatepagescript')
    @include('tasks.tracking_url_script')
@endsection
