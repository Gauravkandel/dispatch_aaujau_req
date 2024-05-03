@extends('layouts.vertical', ['title' => 'Auto Allocation'])

@section('css')
    <style>
        .hidden-desc {
            display: none;
        }

        .book {

            margin-bottom: 10px;
        }

        .font-weight-bold {
            font-weight: 700 !important;
            margin-bottom: 19px !important;
        }

        .checkss {
            margin-top: 20px;

        }

        .redio-all {
            margin-top: 5px;
        }

        .message {
            margin-left: 20px;
            font-weight: bold;
        }

        .header-title {
            margin-bottom: 20px;
        }

        .extra {
            display: none;
        }

    </style>
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{__("Auto Allocation")}}</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-11">
                <div class="text-sm-left">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <span>{!! \Session::get('success') !!}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <form method="POST" action="{{ route('preference', Auth::user()->code) }}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card-box">
                                <h4 class="header-title">{{__("Acknowledgement Type")}}</h4>
                                <p class="sub-header">
                                    {{__(getAgentNomenclature())}} {{__("can either acknowledge the receipt of the task or accept/decline a Task based on your selection below.")}}
                                </p>
                                {{-- @php
                                dd($preference->);
                                @endphp --}}
                                <div class="row mb-2">
                                    <div class="col-md-12">
                                    <div class="login-form">
                                        <ul class="list-inline">
                                            <li class="d-inline-block mr-2">
                                                <input type="radio" id="acknowledge1" class="autoredio" value="acknowledge"
                                                name="acknowledgement_type"
                                                {{ isset($preference) && $preference->acknowledgement_type == 'acknowledge' ? 'checked' : '' }}>
                                            <label for="acknowledge1"> {{__("Acknowledge")}} </label>
                                            </li>
                                            <li class="d-inline-block mr-2">
                                                <input type="radio" id="acknowledge2" class="autoredio" value="acceptreject"
                                                name="acknowledgement_type"
                                                {{ isset($preference) && $preference->acknowledgement_type == 'acceptreject' ? 'checked' : '' }}>
                                            <label for="acknowledge2"> {{__("Accept")}}/{{__("Reject")}} </label>
                                            </li>
                                            <li class="d-inline-block">
                                                <input type="radio" id="acknowledge3" class="autoredio" value="none" name="acknowledgement_type"
                                                {{ isset($preference) && $preference->acknowledgement_type == 'none' ? 'checked' : '' }}>
                                            <label for="acknowledge3"> {{__("None")}} </label>
                                            </li>
                                          </ul>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </form>
                <form method="post" action="{{ route('auto-allocation.update', Auth::user()->code ?? '') }}" id="logic_form">
                    @csrf
                    @method('PUT')
                    <div class="card-box">
                        <h4 class="header-title">{{__("Auto Allocation")}}</h4>
                        <div class="custom-switch redio-all">
                            <input type="checkbox" value="1" class="custom-control-input large-icon" id="manual_allocation"
                                name="manual_allocation"
                                {{ isset($allocation) && $allocation->manual_allocation == 1 ? 'checked' : '' }}>
                            <label class="custom-control-label checkss" for="manual_allocation">{{__("Enable this option to automatically assign Task to your ")}} {{(getAgentNomenclature())}}</label>

                            <div class="col-sm-4 text-right">

                                @if ($errors->has('manual_allocation'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('manual_allocation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- <div class="custom-switch redio-all mb-3">
                            <input type="checkbox" value="1" class="custom-control-input large-icon" id="self_assign"
                                name="self_assign"
                                {{ isset($allocation) && $allocation->self_assign == 1 ? 'checked' : '' }}>
                            <label class="custom-control-label checkss" for="self_assign">Enable this option to alow self Assign.</label>

                            <div class="col-sm-4 text-right">

                                @if ($errors->has('manual_allocation'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('manual_allocation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}

                        <div class="row mb-2 mt-3">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="number_of_retries">{{ __("No. Of Retries")}}</label>
                                    <select name="number_of_retries" id="number_of_retries" class="form-control">
                                        <option value="1" {{ isset($allocation) && $allocation->number_of_retries == 1 ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ isset($allocation) && $allocation->number_of_retries == 2 ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ isset($allocation) && $allocation->number_of_retries == 3 ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ isset($allocation) && $allocation->number_of_retries == 4 ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ isset($allocation) && $allocation->number_of_retries == 5 ? 'selected' : '' }}>5</option>

                                    </select>

                                </div>
                                @if ($errors->has('number_of_retries'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('number_of_retries') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="start_before_task_time">{{__("Start Allocation Before")}} ({{__("In Min")}})</label>
                                    <input type="text" name="start_before_task_time" id="start_before_task_time"
                                        placeholder="5" class="form-control"
                                        value="{{ isset($allocation) && $allocation->start_before_task_time != null ? $allocation->start_before_task_time : '' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="request_expiry">{{__("Request Expires")}} ({{__("In Sec")}})</label>
                                    <input type="text" name="request_expiry" id="request_expiry" placeholder="30"
                                        class="form-control"
                                        value="{{ isset($allocation) && $allocation->request_expiry != null ? $allocation->request_expiry : '' }}"
                                        require>
                                </div>
                                @if ($errors->has('request_expiry'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('request_expiry') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="task_priority">{{__("Maximum Task Per Person")}}</label>
                                    <input type="text" name="maximum_task_per_person" id="request_expiry" placeholder="10"
                                        class="form-control"
                                        value="{{ isset($allocation) && $allocation->maximum_task_per_person != null ? $allocation->maximum_task_per_person : '' }}"
                                        require>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="maximum_radius">{{__("Maximum Radius")}} {{$preference->distance_unit  == "metric" ? '(In Km)':'(In Mile)'}}</label>
                                    <input type="text" name="maximum_radius" id="maximum_radius" placeholder="30"
                                        class="form-control"
                                        value="{{ isset($allocation) && $allocation->maximum_radius != null ? $allocation->maximum_radius : '' }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="maximum_radius">{{__("Maximum Cash At Hand Per Person")}}</label>
                                    <input type="text" name="maximum_cash_at_hand_per_person" id="maximum_radius" placeholder="3000"
                                        class="form-control"
                                        value="{{ isset($allocation) && $allocation->maximum_cash_at_hand_per_person != null ? $allocation->maximum_cash_at_hand_per_person : '' }}">
                                </div>

                            </div>

                        </div>

                        <h4 class="header-title">{{__("Select a method to allocate task")}}</h4>

                        <div class="row mb-2 mt-2" id="rediodivs">
                            <div class="col-md-6 click first_click five" id="redio1">
                                <div class="border p-3 rounded book ">
                                    <div class="row">
                                        <div class="col-md-8 first-part">

                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="shippingMethodRadio1" name="auto_assign_logic"
                                                class="custom-control-input custom-logic" value="one_by_one"
                                                {{ isset($allocation) && $allocation->auto_assign_logic == 'one_by_one' ? 'checked' : '' }}>
                                            <label class="custom-control-label font-16 font-weight-bold lab"
                                                for="shippingMethodRadio1">{{__("One By One")}}</label>
                                        </div>
                                        <strong class="tagline one_by_one" style="">{{__("Allocation will done one by one")}}</strong>
                                    </div>
                                    <div class="col-md-4 icon-part">
                                    <img class="img-fluid" src="{{asset('assets/icons/onebyone.png')}}"  alt="">
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 click five sendtoall" id="redio2">
                                <div class="border p-3 rounded book">
                                    <div class="row">
                                    <div class="col-md-8 first-part">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="shippingMethodRadio2" name="auto_assign_logic"
                                                class="custom-control-input custom-logic" value="send_to_all"
                                                {{ isset($allocation) && $allocation->auto_assign_logic == 'send_to_all' ? 'checked' : '' }}>
                                            <label class="custom-control-label font-16 font-weight-bold lab"
                                                for="shippingMethodRadio2">{{__("Send to all")}}</label>
                                        </div>
                                        <strong class="tagline send_to_all">{{__("Allocation request will send to all")}}</strong>
                                    </div>
                                    <div class="col-md-4 icon-part">
                                        <img class="img-fluid" src="{{asset('assets/icons/sendtoall.png')}}"  alt="">
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="col-md-6 click batch" id="redio3">
                                <div class="border p-3 rounded book">
                                    <div class="row">
                                        <div class="col-md-8 first-part">

                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="shippingMethodRadio3" name="auto_assign_logic"
                                                class="custom-control-input custom-logic" value="batch_wise"
                                                {{ isset($allocation) && $allocation->auto_assign_logic == 'batch_wise' ? 'checked' : '' }}>
                                            <label class="custom-control-label font-16 font-weight-bold lab"
                                                for="shippingMethodRadio3">{{__("Batch Wise")}}</label>
                                        </div>
                                        <strong class="tagline batch_wise">{{__("Allocation request will done batch wise")}}</strong>
                                    </div>
                                    <div class="col-md-4 icon-part">
                                        <img class="img-fluid" src="{{asset('assets/icons/batch.png')}}"  alt="">
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 click five" id="redio4">
                                <div class="border p-3 rounded book set">
                                    <div class="row">
                                        <div class="col-md-8 first-part">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="shippingMethodRadio4" name="auto_assign_logic"
                                                class="custom-control-input custom-logic" value="round_robin"
                                                {{ isset($allocation) && $allocation->auto_assign_logic == 'round_robin' ? 'checked' : '' }}>
                                            <label class="custom-control-label font-16 font-weight-bold lab"
                                                for="shippingMethodRadio4">{{__("Round Robin")}}</label>
                                        </div>
                                        <strong class="tagline round_robin">{{__("Allocation request will done in round robin format")}}</strong>
                                    </div>
                                    <div class="col-md-4 icon-part">
                                        <img class="img-fluid" src="{{asset('assets/icons/roundrobin.png')}}"  alt="">
                                    </div>
                                    </div>
                                </div>
                                <div class="abc">
                                </div>
                            </div>


                            @if ($errors->has('auto_assign_logic'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('auto_assign_logic') }}</strong>
                                </span>
                            @endif
                        </div>


                        <div class="extra">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="start_radius">{{__("Start Radius")}} {{$preference->distance_unit  == "metric" ? '(In Km)':'(In Mile)'}}</label>
                                        <input type="text" name="start_radius" id="start_radius" placeholder="0"
                                            class="form-control"
                                            value="{{ isset($allocation) && $allocation->start_radius != null ? $allocation->start_radius : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="increment_radius">{{__("Increment Radius")}} {{$preference->distance_unit  == "metric" ? '(In Km)':'(In Mile)'}}</label>
                                        <input type="text" name="increment_radius" id="increment_radius" placeholder="5"
                                            class="form-control"
                                            value="{{ isset($allocation) && $allocation->increment_radius != null ? $allocation->increment_radius : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="maximum_batch_size">{{__("Maximum Batch Size")}}</label>
                                        <input type="text" name="maximum_batch_size" id="maximum_batch_size"
                                            placeholder="10" class="form-control"
                                            value="{{ isset($allocation) && $allocation->maximum_batch_size != null ? $allocation->maximum_batch_size : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-blue btn-block" type="submit"> {{__("Update")}} </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var sendtoall = "{{$preference->acknowledgement_type}}";
                if(sendtoall == 'acknowledge'){
                    $('.sendtoall').hide();
                }

            $(function() {
                $('#manual_allocation').change(function() {
                    var checked = $('#manual_allocation').prop('checked');
                    if (checked) {
                        $('.custom-logic').attr('disabled', false);
                    } else {
                        $('.custom-logic').attr('disabled', true);
                    }
                });
            });
            $('.click').click(function() {
                $(this).find('input[type="radio"]').prop('checked', true);
            });

            var simple = '{{ isset($allocation->auto_assign_logic) ? $allocation->auto_assign_logic :'' }}';
            if(simple === 'batch_wise'){
                $('.extra').show();
            }

            $('.batch').click(function() {
                $('.extra').show();
            });

            $('.five').click(function() {
                $('.extra').hide();
            });

            var CSRF_TOKEN = $("input[name=_token]").val();

            $(document).on('click', '.autoredio', function () {
                var value = $("input[name='acknowledgement_type']:checked").val();
                if(value == 'acknowledge'){
                    $('.sendtoall').hide();
                }else{
                    $('.sendtoall').show();
                }
            $.ajax({
                url: "{{ route('auto-update', Auth::user()->code ?? '') }}",
                type: 'PATCH',
                data: { _token: CSRF_TOKEN,acknowledgement_type: value,_method: "PATCH"},
                success: function(res) {

                }
            });
          });

        });

    </script>
@endsection
