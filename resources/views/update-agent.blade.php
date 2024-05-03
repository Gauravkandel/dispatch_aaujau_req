@extends('layouts.vertical', ['title' => 'Options'])
@section('css')
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/css/intlTelInput.css'>
    <style>
        // workaround
        .intl-tel-input {
            display: table-cell;
        }

        .intl-tel-input .selected-flag {
            z-index: 4;
        }

        .intl-tel-input .country-list {
            z-index: 5;
        }

        .input-group .intl-tel-input .form-control {
            border-top-left-radius: 4px;
            border-top-right-radius: 0;
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 0;
        }
        .cursors {
            cursor:move;
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
                    <h4 class="page-title">{{ getAgentNomenclature() }}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if (isset($agent))
                            <form id="UpdateAgent" method="post" action="{{ route('agent.update', $agent->id) }}"
                                enctype="multipart/form-data">
                                @method('PUT')
                            @else
                                <form id="StoreAgent" method="post" action="{{ route('agent.store') }}"
                                    enctype="multipart/form-data">
                        @endif
                        @csrf
                        <div class="modal-body px-3 py-0">
                            <div class="col-sm-10">
                                <div class="text-sm-left">
                                    @if (\Session::has('success'))
                                        <div class="alert alert-success">
                                            <span>{!! \Session::get('success') !!}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <div class="form-group" id="profile_pictureInput">
                                        <input type="file" data-plugins="dropify" name="profile_picture"
                                            data-default-file="{{ isset($agent->profile_picture) ? asset('agents/' . $agent->profile_picture . '') : '' }}" />
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                    <p class="text-muted text-center mt-2 mb-0">Profile Pic</p>
                                </div>
                            </div>
                            <span class="show_all_error invalid-feedback"></span>
               
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="nameInput">
                                        <label for="name" class="control-label">NAME</label>
                                        <input type="text" class="form-control" id="name" placeholder="John Doe" name="name"
                                            value="{{ old('name', $agent->name ?? '') }}">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="phone_numberInput">
                                        <label for="phone_number" class="control-label">CONTACT NUMBER</label>
                                        <div class="input-group">
                                           
                                            <input type="text" name="phone_number" class="form-control" id="phone_number"
                                                placeholder="Enter mobile number"
                                                value="{{ $agent->phone_number }}">
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="typeInput">
                                        <label for="type" class="control-label">TYPE</label>
                                        <select class="form-control" data-style="btn-light" name="type" id="type">
                                            <option value="Employee" @if ($agent->type == 'Employee') selected @endif
                                                >Employee</option>
                                            <option value="Freelancer" @if ($agent->type == 'Freelancer') selected @endif
                                                >Freelancer</option>

                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="team_idInput">
                                        <label for="team_id" class="control-label">ASSIGN TEAM</label>
                                        <select class="form-control" data-style="btn-light" name="team_id" id="team_id">
                                            <option>Select Team</option>
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}" @if ($agent->team_id == $team->id) selected
                                            @endif>{{ $team->name }}</option>
                                            @endforeach
                                            <option value="other">other</option>
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="vehicle_type_idInput">
                                        <p class="text-muted mt-3 mb-2">TRANSPORT ICON</p>
                                        <div class="radio radio-blue form-check-inline click cursors">
                                            <input type="radio" id="onfoot" value="onfoot" name="vehicle_type_id" @if ($agent->vehicle_type_id == 'onfoot') checked
                                            @endif>
                                            <img id="foot"
                                                src="{{ $agent->vehicle_type_id == 'onfoot' ? asset('assets/icons/walk_blue.png') : asset('assets/icons/walk.png') }}">
                                        </div>

                                        <div class="radio radio-primery form-check-inline click cursors">
                                            <input type="radio" id="bycycle" value="bycycle" name="vehicle_type_id"
                                                @if ($agent->vehicle_type_id == 'bycycle')
                                            checked @endif >
                                            <img id="cycle"
                                                src="{{ $agent->vehicle_type_id == 'bycycle' ? asset('assets/icons/cycle_blue.png') : asset('assets/icons/cycle.png') }}">
                                        </div>
                                        <div class="radio radio-info form-check-inline click cursors">
                                            <input type="radio" id="motorbike" value="motorbike" name="vehicle_type_id"
                                                @if ($agent->vehicle_type_id == 'motorbike')
                                            checked @endif>
                                            <img id="bike"
                                                src="{{ $agent->vehicle_type_id == 'motorbike' ? asset('assets/icons/bike_blue.png') : asset('assets/icons/bike.png') }}">
                                        </div>
                                        <div class="radio radio-danger form-check-inline click cursors">
                                            <input type="radio" id="car" value="car" name="vehicle_type_id" @if ($agent->vehicle_type_id == 'car') checked
                                            @endif>
                                            <img id="cars"
                                                src="{{ $agent->vehicle_type_id == 'car' ? asset('assets/icons/car_blue.png') : asset('assets/icons/car.png') }}">
                                        </div>
                                        <div class="radio radio-warning form-check-inline click cursors">
                                            <input type="radio" id="truck" value="truck" name="vehicle_type_id" @if ($agent->vehicle_type_id == 'truck') checked
                                            @endif>
                                            <img id="trucks"
                                                src="{{ $agent->vehicle_type_id == 'truck' ? asset('assets/icons/truck_blue.png') : asset('assets/icons/truck.png') }}">
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>

                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="make_modelInput">
                                        <label for="make_model" class="control-label">TRANSPORT DETAILS</label>
                                        <input type="text" class="form-control" id="make_model"
                                            placeholder="Year, Make, Model" name="make_model"
                                            value="{{ old('name', $agent->make_model ?? '') }}">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="plate_numberInput">
                                        <label for="plate_number" class="control-label">{{__('LICENCE NUMBER')}}</label>
                                        <input type="text" class="form-control" id="plate_number" name="plate_number"
                                            placeholder="508.KLV" value="{{ old('name', $agent->plate_number ?? '') }}">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="colorInput">
                                        <label for="color" class="control-label">COLOR</label>
                                        <input type="text" class="form-control" id="color" name="color" placeholder="Color"
                                            value="{{ old('name', $agent->color ?? '') }}">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <button type="submit" class="btn btn-info waves-effect waves-light">Submit</button>
                            </div>

                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
    <!-- Page js-->
    <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.7/js/intlTelInput.js"></script>

    <script>
        $("#phone_number").intlTelInput({
            nationalMode: false,
            formatOnDisplay: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/js/utils.js"
        });
        $('.intl-tel-input').css('width', '100%');

    //    // var regEx = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/;
    //     $("#UpdateAgent").bind("submit", function() {
    //         var val = $("#phone_number").val();
    //         // if (!val.match(regEx)) {
    //         //     $('#phone_number').css('color', 'red');
    //         //     return false;
    //         // }
    //     });

        $(function() {
            $('#phone_number').focus(function() {
                $('#phone_number').css('color', '#6c757d');
            });
        });

        $(document).ready(function() {

            $('.click').click(function() {
                $(this).find('input[type="radio"]').prop('checked', true);
                var check = $(this).find('input[type="radio"]').val();
                switch (check) {
                    case "onfoot":
                        $("#foot").attr("src", "{{ asset('assets/icons/walk_blue.png') }}");
                        $("#cycle").attr("src", "{{ asset('assets/icons/cycle.png') }}");
                        $("#bike").attr("src", "{{ asset('assets/icons/bike.png') }}");
                        $("#cars").attr("src", "{{ asset('assets/icons/car.png') }}");
                        $("#trucks").attr("src", "{{ asset('assets/icons/truck.png') }}");
                        break;
                    case "bycycle":
                        $("#foot").attr("src", "{{ asset('assets/icons/walk.png') }}");
                        $("#cycle").attr("src", "{{ asset('assets/icons/cycle_blue.png') }}");
                        $("#bike").attr("src", "{{ asset('assets/icons/bike.png') }}");
                        $("#cars").attr("src", "{{ asset('assets/icons/car.png') }}");
                        $("#trucks").attr("src", "{{ asset('assets/icons/truck.png') }}");
                        break;
                    case "motorbike":
                        $("#foot").attr("src", "{{ asset('assets/icons/walk.png') }}");
                        $("#cycle").attr("src", "{{ asset('assets/icons/cycle.png') }}");
                        $("#bike").attr("src", "{{ asset('assets/icons/bike_blue.png') }}");
                        $("#cars").attr("src", "{{ asset('assets/icons/car.png') }}");
                        $("#trucks").attr("src", "{{ asset('assets/icons/truck.png') }}");
                        break;
                    case "car":
                        $("#foot").attr("src", "{{ asset('assets/icons/walk.png') }}");
                        $("#cycle").attr("src", "{{ asset('assets/icons/cycle.png') }}");
                        $("#bike").attr("src", "{{ asset('assets/icons/bike.png') }}");
                        $("#cars").attr("src", "{{ asset('assets/icons/car_blue.png') }}");
                        $("#trucks").attr("src", "{{ asset('assets/icons/truck.png') }}");
                        break;
                    case "truck":
                        $("#foot").attr("src", "{{ asset('assets/icons/walk.png') }}");
                        $("#cycle").attr("src", "{{ asset('assets/icons/cycle.png') }}");
                        $("#bike").attr("src", "{{ asset('assets/icons/bike.png') }}");
                        $("#cars").attr("src", "{{ asset('assets/icons/car.png') }}");
                        $("#trucks").attr("src", "{{ asset('assets/icons/truck_blue.png') }}");
                        break;
                }
            });
        });

    </script>
@endsection
