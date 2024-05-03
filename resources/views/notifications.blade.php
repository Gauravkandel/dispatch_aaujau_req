@extends('layouts.vertical', ['title' => 'Notifications'])
@section('css')
@endsection

@section('content')
@include('modals.add-webhook')
    <!-- Start Content-->
    <div class="container-fluid">
        
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{__("Notifications")}}</h4>
                </div>
            </div>
        </div>     
        
        <div class="row">
            <div class="col-xl-12">
                <div class="card-box">
                    <h4 class="header-title">{{__("Notifications")}}</h4>
                    <p class="sub-header">
                        {{__("Send SMS's and emails based on each trigger and customize the content by clicking on the edit icon.")}}
                    </p>
                    @foreach($notification_types as $key => $notification_type)
                    <div class="card-box">
                        <div class="row mb-2">
                            <div class="col-sm-8">
                                <div class="text-sm-left">
                                    <h4 class="header-title">{{ __($notification_type->name) }}</h4>
                                </div>
                            </div>
                            
                        </div>

                        <div class="row no-wrap">
                            <div class="offset-3 col-3 text-center">
                                <h4 class="header-title pl-3">{{__("Customer")}}</h4>
                            </div>
                            <div class="offset-1 col-5">
                                <h4 class="header-title pl-4">{{__("Recipient")}}</h4>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                <thead class="thead-light">
                                    <tr>
                                        <th>{{__("Events")}}</th>
                                        <th>{{__("SMS")}}</th>
                                        <th>{{__("EMAIL")}}</th>
                                        <th>{{__("SMS")}}</th>
                                        <th>{{__("EMAIL")}}</th>
                                        <!--<th>WEBHOOK</th>
                                         <th>WEBHOOK URL</th>  -->
                                        <th></th>
                                    </tr>
                                    
                                </thead>
                                <tbody>
                                    @foreach($notification_type->notification_events as $index => $event)
                                    <tr>
                                        <td>
                                            <h5 class="m-0 font-weight-normal">{{ __($event->name) }}</h5>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" data-id="{{ $event->id }}" data-event-type="sms" id="smscustomSwitch_{{ $event->id}}"  @if($event->is_checked_sms(Auth::user()->code))  checked @endif>
                                                <label class="custom-control-label" for="smscustomSwitch_{{ $event->id}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" data-id="{{ $event->id }}" data-event-type="email" id="emailcustomSwitch_{{ $event->id}}" @if($event->is_checked_email(Auth::user()->code))  checked @endif >
                                                <label class="custom-control-label" for="emailcustomSwitch_{{ $event->id}}"></label>
                                            </div>
                                        </td>

                                        <td>   {{-- for receipient sms --}}
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" data-id="{{ $event->id }}" data-event-type="recipient_sms" id="recipient_smscustomSwitch_{{ $event->id}}"  @if($event->is_checked_recipient_sms(Auth::user()->code))  checked @endif>
                                                <label class="custom-control-label" for="recipient_smscustomSwitch_{{ $event->id}}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" data-id="{{ $event->id }}" data-event-type="recipient_email" id="recipient_emailcustomSwitch_{{ $event->id}}" @if($event->is_checked_recipient_email(Auth::user()->code))  checked @endif >
                                                <label class="custom-control-label" for="recipient_emailcustomSwitch_{{ $event->id}}"></label>
                                            </div>
                                        </td>
                                      <!-- <td> 
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input event_type" data-id="{{ $event->id }}" data-event-type="webhook" id="webhookcustomSwitch_{{ $event->id}}" @if($event->is_checked_webhook(1))  checked @endif>
                                                <label class="custom-control-label" for="webhookcustomSwitch_{{ $event->id}}"></label>
                                            
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">

                                                @foreach ($client_notifications as $item)
                                                     @if ($item->notification_event_id == $event->id)
                                                        {{$item->webhook_url}}
                                                     @else
                                                 
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>-->

                                        <td>
                                            <a href="javascript: void(0);" class="action-icon">
                                                <i class="mdi mdi-square-edit-outline web-hook-add" data-id="{{ $event->id }}" data-webhook-url="{{ $event->message }}"></i>
                                            </a>
                                        </td> 
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                    @if($showCustomerNotification == 1)
                    <div class="col-md-4">
                        <div class="card-box">
                            <form method="POST" class="h-100" action="{{ route('preference', Auth::user()->code) }}">
                                @csrf
                                <input type="hidden" name="refer_and_earn" value="1">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="header-title">{{ __('Send Customer Notification Per Distance') }}</h4>
                                        <div class="row">
                                            <div class="col-xl-12 my-2 d-flex align-items-center justify-content-between mt-3 mb-2">
                                                <h5 class="font-weight-normal m-0">{{ __('Enable') }}</h5>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="customer_notification"
                                                        name="customer_notification[is_send_customer_notification]"
                                                        {{ (!empty($client_preference->is_send_customer_notification) && ($client_preference->is_send_customer_notification == 'on'))? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="customer_notification"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-12 my-2" id="">
                                                <label class="primaryCurText">{{ __('Title') }}</label>
                                                <input class="form-control" type="text" id="title"
                                                    name="customer_notification[title]" placeholder="push notification title"
                                                    value="{{ !empty($client_preference)? $client_preference->title : '' }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-8 my-2" id="">
                                                <label class="primaryCurText">{{ __('Description') }}</label>
                                                <textarea class="txtarea form-control" rows="3" placeholder="Description" name="customer_notification[description]" type="text" id="description">{{ !empty($client_preference->description)? $client_preference->description : '' }}</textarea>
                                            </div>
                                            <div class="col-xl-4 my-2" id="">
                                                <label for="title" class="control-label">Tags:-<div id="tags" disabled="">{distance}, {co2_emission}</div></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-12 mb-2 mt-3" id="">
                                                <label class="primaryCurText">{{ __('CO2 Emission') }}</label>
                                                <input class="form-control" type="number" name="customer_notification[co2_emission]"
                                                    id="co2_emission"
                                                    value="{{ !empty($client_preference->co2_emission)? $client_preference->co2_emission : '' }}"
                                                    min="0">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-12 mb-2 mt-3" id="">
                                                <label class="primaryCurText">{{ __('Distance Increment') }}</label>
                                                <input class="form-control" type="number" name="customer_notification[notification_per_distance]"
                                                    id="notification_per_distance"
                                                    value="{{ !empty($client_preference->notification_per_distance)? $client_preference->notification_per_distance : '' }}"
                                                    min="0">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-12 mb-2 mt-3" id="">
                                                <div class="radio radio-info form-check-inline">
                                                    <input type="radio" id="distance_unit_km" value="km" name="customer_notification[distance_unit]" {{ (!empty($client_preference->distance_unit) && ($client_preference->distance_unit == 'km'))? 'checked' : '' }} >
                                                    <label for="distance_unit_km"> {{__("KM")}}</label>
                                                </div>
                                                <div class="radio radio-info form-check-inline">
                                                    <input type="radio" id="distance_unit_miles" value="miles" name="customer_notification[distance_unit]" {{ (!empty($client_preference->distance_unit) && ($client_preference->distance_unit == 'miles'))? 'checked' : '' }} >
                                                    <label for="distance_unit_miles"> {{__("Miles")}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-0 text-center">
                                            <button class="btn btn-blue btn-block" type="submit"> {{ __('Update') }} </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div> 
            </div> 
        </div>
    </div>
@endsection

@section('script')
<script>
    $(function(){
        $('.event_type').change(function(){
            var current_value = this.checked ? 1 : 0;
            var notification_event_id=  $(this).attr('data-id');
            var notification_type = $(this).attr('data-event-type');
            formData = {
                current_value : current_value,
                notification_event_id : notification_event_id,
                notification_type : notification_type,
                _token : "{{ csrf_token() }}"
            };
            spinnerJS.showSpinner();
            //startLoader('body');
            $.ajax({
                method: "POST",
                headers: {
                    Accept: "application/json"
                },
                url: "/notification_update",
                data: formData,
                success: function(response) {
                    
                    if (response.status == 'success') {

                    } else {
                        $(".show_all_error.invalid-feedback").show();
                        $(".show_all_error.invalid-feedback").text(response.message);
                    }
                    spinnerJS.hideSpinner();
                },
                error: function(response) {
                    spinnerJS.hideSpinner();
                    if (response.status === 422) {
                        let errors = response.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            $("#" + key + "Input input").addClass("is-invalid");
                            $("#" + key + "Input span.invalid-feedback").children(
                                "strong").text(errors[key][
                                0
                            ]);
                            $("#" + key + "Input span.invalid-feedback").show();
                        });
                    } else {
                        $(".show_all_error.invalid-feedback").show();
                        $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                    }
                }
            });
        });
    });

    //webhook url updation //
    $(function(){
        $('.web-hook-add').click(function(){
            $('#notification_event_id').val($(this).attr('data-id'));
            $('#webhook_url').val($(this).attr('data-webhook-url'));
            $('#add-webhook-modal').modal('show');
        });
    });
</script>
@endsection