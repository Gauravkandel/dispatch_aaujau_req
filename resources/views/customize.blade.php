@extends('layouts.vertical', ['title' => 'Customize'])

@section('css')
@endsection
@section('content')
@include('modals.tandc')
@include('modals.privacyandpolicy')

<!-- Start Content-->
<div class="container-fluid">

    @if (\Session::has('success'))
    <div class="row">
        <div class="col-11">
            <div class="text-sm-left">

                <div class="alert alert-success">
                    <span>{!! \Session::get('success') !!}</span>
                </div>

            </div>
        </div>
    </div>
    @endif
    <!-- start Section title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{__("Nomenclature & Localisation")}}</h4>
            </div>
        </div>
    </div>
    <!-- end Section title -->
    <div class="row mb-3">
        <div class="col-xl-3 col-md-4">
            <div class="card-box h-100">
                <div class="row">
                    <div class="col-md-12">
                        <form method="POST" class="h-100" action="{{route('preference', Auth::user()->code)}}">
                            @csrf
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h4 class="header-title mb-0">{{__("Nomenclature")}}</h4>
                                <button class="btn btn-outline-info d-block" type="submit"> {{__('Save')}} </button>
                            </div>
                            <p class="sub-header">{{__("View and update the naming, currency and distance units.")}}</p>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="agent_type">{{__(strtoupper(getAgentNomenclature())." NAME")}}</label>
                                        <input type="text" name="agent_name" id="agent_type" placeholder="e.g {{ __(getAgentNomenclature())}}" class="form-control" value="{{ old('agent_type', $preference->agent_name ?? '')}}">
                                        @if($errors->has('agent_name'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('agent_name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="currency">{{__("CURRENCY")}}</label>
                                        <select class="form-control" id="currency" name="currency_id">
                                            @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}" {{ ($preference && $preference->currency_id == $currency->id)? "selected" : "" }}>{{ $currency->iso_code }} - {{ $currency->symbol }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('currency_id'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('currency_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label>{{__('Unit')}}</label>
                                    <div class="col-sm-12">
                                        <div class="radio radio-info form-check-inline">
                                            <input type="radio" id="metric" value="metric" name="distance_unit" {{ ($preference && $preference->distance_unit =="metric")? "checked" : "" }}>
                                            <label for="metric"> {{__("Metric")}}</label>
                                        </div>
                                        <div class="radio form-check-inline">
                                            <input type="radio" id="imperial" value="imperial" name="distance_unit" {{ ($preference && $preference->distance_unit =="imperial")? "checked" : "" }}>
                                            <label for="imperial"> {{__("Imperial")}}</label>
                                        </div>
                                        @if($errors->has('distance_unit'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('distance_unit') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form method="POST" action="{{route('preference',Auth::user()->code)}}">
                        @csrf
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h4 class="header-title mb-0">{{__("Date & Time")}}</h4>
                                <button class="btn btn-outline-info d-block" type="submit"> {{__('Save')}} </button>
                            </div>
                            <p class="sub-header">
                                {{__("View and update the date & time format.")}}
                            </p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_format">{{__("DATE FORMAT")}}</label>
                                        <select class="form-control" id="date_format" name="date_format">
                                            <option value="m/d/Y" {{ ($preference && $preference->date_format =="m/d/Y")? "selected" : "" }}>
                                                MM/DD/YYYY</option>
                                            <option value="d-m-Y" {{ ($preference && $preference->date_format =="d-m-Y")? "selected" : "" }}>
                                                DD-MM-YYYY</option>
                                            <option value="d/m/Y" {{ ($preference && $preference->date_format =="d/m/Y")? "selected" : "" }}>
                                                DD/MM/YYYY</option>
                                            <option value="Y-m-d" {{ ($preference && $preference->date_format =="Y-m-d")? "selected" : "" }}>
                                                YYYY-MM-DD</option>
                                        </select>
                                        @if($errors->has('date_format'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('date_format') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="time_format">{{__("TIME FORMAT")}}</label>
                                        <select class="form-control" id="time_format" name="time_format">
                                            <option value="12" {{ ($preference && $preference->time_format =="12")? "selected" : "" }}>12 {{__("hours")}}
                                            </option>
                                            <option value="24" {{ ($preference && $preference->time_format =="24")? "selected" : "" }}>24 {{__("hours")}}
                                            </option>
                                        </select>
                                        @if($errors->has('time_format'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('time_format') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-4">
            <div class="card-box h-100">
                <form method="POST" class="h-100" action="{{route('preference', Auth::user()->code)}}">
                    @csrf
                    <input type="hidden" name="address_limit_order_config" value="1">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">{{__("Saved Address selection")}}</h4>
                        <button class="btn btn-outline-info d-block" type="submit"> {{__('Save')}} </button>
                    </div>
                    <p class="sub-header">{{__("Manage how you want to show saved addresses while creating routes.")}}</p>
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="radio radio-info form-check-inline mb-2">
                                <input type="radio" id="all_contact" value="1" name="allow_all_location" {{ (isset($preference) && $preference->allow_all_location ==1)? "checked" : "" }}>
                                <label for="all_contact"> {{__("Shared saved addresses for all customers")}} </label>
                            </div>
                            <div class="radio form-check-inline mb-2">
                                <input type="radio" id="my_contact" value="0" name="allow_all_location" {{ (isset($preference) &&  $preference->allow_all_location ==0)? "checked" : "" }}>
                                <label for="my_contact"> {{__("Saved addresses linked to each customer")}} </label>
                            </div>
                            @if($errors->has('allow_all_location'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('allow_all_location') }}</strong>
                            </span>
                            @endif
                            <hr>
                            <h4 class="header-title">{{__("Show Limited Address")}}</h4>

                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input event_type" id="show_limited_address" name="show_limited_address" {{isset($preference) && $preference->show_limited_address == 1 ? 'checked':''}}>
                                <label class="custom-control-label" for="show_limited_address">{{__("Show only first 5 address")}}</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-xl-3 col-md-4">
            <div class="card-box h-100">
                <form method="POST" class="h-100" action="{{route('update.contact.us', Auth::user()->code)}}">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">{{__('Contact Us')}}</h4>
                        <button class="btn btn-outline-info d-block" type="submit"> {{__('Save')}} </button>
                    </div>

                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <label for="contact_address">{{__('Address')}}</label>
                                <div class="input-group">
                                    <input type="text" name="contact_address" id="contact_address"  class="form-control" value="{{ old('contact_address', $clientContact->contact_address ?? '')}}">
                                </div>
                                @if($errors->has('contact_address'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('contact_address') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group mt-2 mb-0">
                                <label for="contact_phone_number">{{__('Number')}}</label>
                                <input type="text" name="contact_phone_number" id="contact_phone_number" placeholder="" class="form-control" value="{{ old('contact_phone_number', $clientContact->contact_phone_number ?? '')}}">
                                @if($errors->has('contact_phone_number'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('contact_phone_number') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group mt-2 mb-0">
                                <label for="contact_email">{{__('Email')}}</label>
                                <input type="text" name="contact_email" id="contact_email" placeholder="" class="form-control" value="{{ old('contact_email', $clientContact->contact_email ?? '')}}">
                                @if($errors->has('contact_email'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('contact_email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <form method="POST" action="{{route('task.proof')}}">
        @csrf
        <div class="row">
            <div class="col-xl-9 col-md-12">
                <div class="card-box">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">{{__('Task Completion Proofs')}}</h4>
                        <button class="btn btn-outline-info d-block" type="submit"> {{__('Save')}} </button>
                    </div>
                    <div>
                        {{-- @php
                            echo "<pre>";
                            print_r($task_list); @endphp --}}
                        @foreach ($task_proofs as $key => $taskproof)
                        @php $counter = 1; @endphp
                        <h5 class="header-title mb-3">{{__($task_list[$key]->name)}}</h5>

                        <div class="table-responsive table_spacing">
                            <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                <thead class="thead-light">
                                    <tr>
                                        <th>{{__("Type")}}</th>
                                        <th>{{__("Enable")}}</th>
                                        <th>{{__("Required")}}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    </td>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">{{__("Image")}}</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'1'}}" name="image_{{$key+1}}" {{isset($taskproof->image) && $taskproof->image == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'1'}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'2'}}" name="image_requried_{{$key+1}}" {{isset($taskproof->image_requried) && $taskproof->image_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'2'}}"></label>
                                            </div>
                                        </td>


                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">{{__("Signature")}}</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'3'}}" name="signature_{{$key+1}}" {{isset($taskproof->signature) && $taskproof->signature == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'3'}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'4'}}" name="signature_requried_{{$key+1}}" {{isset($taskproof->signature_requried) && $taskproof->signature_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'4'}}"></label>
                                            </div>
                                        </td>


                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">{{__('Notes')}}</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'5'}}" name="note_{{$key+1}}" {{isset($taskproof->note) && $taskproof->note == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'5'}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'6'}}" name="note_requried_{{$key+1}}" {{isset($taskproof->note_requried) && $taskproof->note_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'6'}}"></label>
                                            </div>
                                        </td>


                                    </tr>


                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">{{__("Barcode")}}</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'7'}}" name="barcode_{{$key+1}}" {{isset($taskproof->barcode) && $taskproof->barcode == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'7'}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type barcode-requried-check" id="customSwitch_{{$key.''.$counter.'8'}}" name="barcode_requried_{{$key+1}}" {{isset($taskproof->barcode_requried) && $taskproof->barcode_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'8'}}"></label>
                                            </div>
                                        </td>


                                    </tr>
                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">{{__("OTP")}}</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'9'}}" name="otp_{{$key+1}}" {{!empty($taskproof->otp) && $taskproof->otp == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'9'}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type otp-requried-check" id="customSwitch_{{$key.''.$counter.'10'}}" name="otp_requried_{{$key+1}}" {{!empty($taskproof->otp_requried) && $taskproof->otp_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'10'}}"></label>
                                            </div>
                                        </td>


                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">{{__("Face Proof")}}</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'13'}}" name="face_{{$key+1}}" {{!empty($taskproof->face) && $taskproof->face == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'11'}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type otp-requried-check" id="customSwitch_{{$key.''.$counter.'14'}}" name="face_requried_{{$key+1}}" {{!empty($taskproof->face_requried) && $taskproof->face_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'12'}}"></label>
                                            </div>
                                        </td>


                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">{{__("QR Code Scan")}}</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_{{$key.''.$counter.'11'}}" name="qrcode_{{$key+1}}" {{!empty($taskproof->qrcode) && $taskproof->qrcode == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'11'}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type otp-requried-check" id="customSwitch_{{$key.''.$counter.'12'}}" name="qrcode_requried_{{$key+1}}" {{!empty($taskproof->qrcode_requried) && $taskproof->qrcode_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_{{$key.''.$counter.'12'}}"></label>
                                            </div>
                                        </td>


                                    </tr>


                                </tbody>
                            </table>
                         </div>
                         {{-- <h4 class="header-title mb-3">{{$key == 0 ? 'Drop-Off': $key == 1 ? 'Appointment':''}}</h4> --}}
                         @php $counter++; @endphp
                        @endforeach

                        {{-- <h4 class="header-title mb-3">Drop-Off</h4>
                        <div class="table-responsive table_spacing">
                            <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                <thead class="thead-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Enable</th>
                                        <th>Required</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    </td>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Image</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_7" name="image_2" {{isset($taskproof->image) && $taskproof->image == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_7"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_8" name="image_requried_2" {{isset($taskproof->image_requried) && $taskproof->image_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_8"></label>
                                            </div>
                                        </td>


                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Signature</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_9" name="signature_2" {{isset($taskproof->signature) && $taskproof->signature == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_9"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_10" name="signature_requried_2" {{isset($taskproof->signature_requried) && $taskproof->signature_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_10"></label>
                                            </div>
                                        </td>


                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Notes</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_11" name="note_2" {{isset($taskproof->note) && $taskproof->note == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_11"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_12" name="note_requried_2" {{isset($taskproof->note_requried) && $taskproof->note_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_12"></label>
                                            </div>
                                        </td>


                                    </tr>


                                </tbody>
                            </table>
                        </div> --}}

                        {{-- <h4 class="header-title mb-3">Appointment</h4>
                        <div class="table-responsive table_spacing">
                            <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                <thead class="thead-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Enable</th>
                                        <th>Required</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    </td>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Image</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_13" name="image_3" {{isset($taskproof->image) && $taskproof->image == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_13"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_14" name="image_requried_3" {{isset($taskproof->image_requried) && $taskproof->image_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_14"></label>
                                            </div>
                                        </td>


                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Signature</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_15" name="signature_3" {{isset($taskproof->signature) && $taskproof->signature == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_15"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_16" name="signature_requried_3" {{isset($taskproof->signature_requried) && $taskproof->signature_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_16"></label>
                                            </div>
                                        </td>


                                    </tr>

                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">Notes</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_17" name="note_3" {{isset($taskproof->note) && $taskproof->note == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_17"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" id="customSwitch_18" name="note_requried_3" {{isset($taskproof->note_requried) && $taskproof->note_requried == 1 ? 'checked':''}}>
                                                <label class="custom-control-label" for="customSwitch_18"></label>
                                            </div>
                                        </td>


                                    </tr>


                                </tbody>
                            </table>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </form>




</div> <!-- container -->
@endsection

@section('script')
<script>

    $(document).ready(function() {



        var CSRF_TOKEN = $("input[name=_token]").val();



        $( '#tandc_form' ).on( 'submit', function(e) {
            e.preventDefault();

            var content = $(this).find('textarea[name=content]').val();


            $.ajax({
            type: "POST",
            url: "{{ route('cms.save',[1]) }}",
            data: { _token: CSRF_TOKEN,content:content,id:1},
            success: function( msg ) {
                $("#create-tandc-modal .close").click();
            }
           });

        });

        $( '#pandp_form' ).on( 'submit', function(e) {
            e.preventDefault();

            var content = $(this).find('textarea[name=content]').val();


            $.ajax({
            type: "POST",
            url: "{{ route('cms.save',2) }}",
            data: { _token: CSRF_TOKEN,content:content},
            success: function( msg ) {
                $("#create-pandp-modal .close").click();
            }
           });

        });


        $(document).on('click', '[name="myRadios"]', function () {

            if (lastSelected != $(this).val() && typeof lastSelected != "undefined") {
                alert("radio box with value " + $('[name="myRadios"][value="' + lastSelected + '"]').val() + " was deselected");
            }
            lastSelected = $(this).val();

        });

    });

</script>
@endsection
