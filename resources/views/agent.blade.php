@extends('layouts.vertical', ['title' => getAgentNomenclature().'s'])

@section('css')
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />


    <!-- for File Upload -->

    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/css/intlTelInput.css'>
    <style>
        // workaround
        .intl-tel-input {
            display: table-cell;
        }

        .inner-div {
            width: 50%;
            float: left;
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
            margin-right: 0rem !important;
        }

        #ui-id-1, #ui-id-2{
        z-index: 9999 ;

    }
    #ui-id-1 li, #ui-id-2 li{
        z-index: 9999 ;
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
                        <div class="row mb-2">
                            <div class="col-sm-8">
                                <div class="text-sm-left">
                                    @if (\Session::has('success'))
                                        <div class="alert alert-success">
                                            <span>{!! \Session::get('success') !!}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4 text-right">
                                <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal"
                                    data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> Add {{ getAgentNomenclature() }}</button>
                            </div>

                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="agents-datatable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Type</th>
                                        <th>Team</th>
                                        <th>Transport Icon</th>
                                        <th>Order Earning</th>
                                        <th>Cash Collected</th>
                                        <th>Final Balance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($agents as $agent)
                                        <tr>
                                            <td class="table-user">
                                                <a href="javascript:void(0);"
                                                    class="text-body font-weight-semibold">{{ $agent->name }}</a>
                                            </td>
                                            <td>
                                                {{ $agent->phone_number }}
                                            </td>
                                            <td>
                                                {{ $agent->type }}
                                            </td>
                                            <td>
                                                @if (isset($agent->team->name))
                                                    {{ $agent->team->name }}

                                                @else
                                                    {{ 'Team Not Alloted' }}
                                                @endif

                                            </td>
                                            <td>
                                                {{ $agent->vehicle_type_id }}
                                            </td>
                                            <td>
                                                {{ 1000 }}
                                            </td>
                                            <td>
                                                {{ 500 }}
                                            </td>
                                            <td>
                                                {{ 500 }}
                                            </td>
                                            <!-- <td>
                                                                                            <span class="badge bg-soft-success text-success">Active</span>
                                                                                        </td> -->

                                            <td>
                                                <div class="form-ul" style="width: 60px;">
                                                    <div class="inner-div"> <a href="{{ route('agent.edit', $agent->id) }}"
                                                            class="action-icon"> <i
                                                                class="mdi mdi-square-edit-outline"></i></a></div>
                                                    <div class="inner-div">
                                                        <form method="POST"
                                                            action="{{ route('agent.destroy', $agent->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="form-group">
                                                                <button type="submit"
                                                                    class="btn btn-primary-outline action-icon"> <i
                                                                        class="mdi mdi-delete"></i></button>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>


                                                <!-- <a href="{{ route('agent.destroy', $agent->id) }}" class="action-icon">
                                                                                                <i class="mdi mdi-delete"></i>
                                                                                            </a> -->

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>


    </div>




<div id="add-agent-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">{{__('Add')}} {{ getAgentNomenclature() }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="submitAgent" enctype="multipart/form-data" action="{{ route('agent.store') }}">
                @csrf
                <div class="modal-body px-3 py-0">
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <div class="form-group" id="profile_pictureInput">
                                <input type="file" data-plugins="dropify" name="profile_picture" />
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                            <p class="text-muted text-center mt-2 mb-0">Profile Pic</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="nameInput">
                                <label for="name" class="control-label">NAME</label>
                                <input type="text" class="form-control" id="name" placeholder="John Doe" name="name">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="phone_numberInput">
                                <label for="phone_number" class="control-label">CONTACT NUMBER</label>
                                <div class="input-group">
                                    <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="Enter mobile number" maxlength="14">
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
                                <select class="selectpicker" data-style="btn-light" name="type" id="type">
                                    <option value="Employee">Employee</option>
                                    <option value="Freelancer">Freelancer</option>
                                </select>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="team_idInput">
                                <label for="team_id" class="control-label">ASSIGN TEAM</label>
                                <select class="selectpicker" data-style="btn-light" name="team_id" id="team_id">
                                    <option hidden="true"></option>
                                    @foreach($teams as $team)
                                    <option value="{{$team->id}}">{{$team->name}}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row ">
                        <div class="col-md-12">
                            <div class="form-group" id="vehicle_type_idInput">
                                <p class="text-muted mt-3 mb-2">TRANSPORT ICON</p>
                                <div class="radio radio-blue form-check-inline click cursors">
                                    <input type="radio" id="onfoot" value="onfoot" name="vehicle_type_id" checked>
                                    <img id="foot" src="{{asset('assets/icons/walk.png')}}"> 
                                </div>

                                <div class="radio radio-primery form-check-inline click cursors">
                                    <input type="radio" id="bycycle" value="bycycle" name="vehicle_type_id">
                                    <img id="cycle" src="{{asset('assets/icons/cycle.png')}}">
                                </div>
                                <div class="radio radio-info form-check-inline click cursors">
                                    <input type="radio" id="motorbike" value="motorbike" name="vehicle_type_id">
                                    <img id="bike" src="{{asset('assets/icons/bike.png')}}">
                                </div>
                                <div class="radio radio-danger form-check-inline click cursors">
                                    <input type="radio" id="car" value="car" name="vehicle_type_id">
                                    <img id="cars" src="{{asset('assets/icons/car.png')}}">
                                </div>
                                <div class="radio radio-warning form-check-inline click cursors">
                                    <input type="radio" id="truck" value="truck" name="vehicle_type_id">
                                    <img id="trucks" src="{{asset('assets/icons/truck.png')}}">
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label>Tag</label>
                                <input id="form-tags-1" name="tags" type="text" value="" class="myTag1">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="make_modelInput">
                                <label for="make_model" class="control-label">TRANSPORT DETAILS</label>
                                <input type="text" class="form-control" id="make_model" placeholder="Year, Make, Model" name="make_model">
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
                                <input type="text" class="form-control" id="plate_number" name="plate_number" placeholder="508.KLV">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="colorInput">
                                <label for="color" class="control-label">COLOR</label>
                                <input type="text" class="form-control" id="color" name="color" placeholder="Color">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')


    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">



    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-pickers.init.js') }}"></script>
    <script src="{{ asset('assets/js/storeAgent.js') }}"></script>
    <script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script> 


    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.7/js/intlTelInput.js"></script>


    <script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />


    <script type="text/javascript">

        $('.openModal').click(function(){
                    $('#add-agent-modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    aaasa();
                });

        function aaasa(){

            $('.myTag1').tagsInput({
                'autocomplete': {
                    source: [
                        'apple',
                        'banana',
                        'orange',
                        'pizza'
                    ]
                } 
            });
        }

    </script>

    <script>
        $("#phone_number").intlTelInput({
            nationalMode: false,
            formatOnDisplay: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/js/utils.js"
        });
        $('.intl-tel-input').css('width', '100%');

        

        $(function() {
            $('#phone_number').focus(function() {
                $('#phone_number').css('color', '#6c757d');
            });
        });

        $(document).ready(function() {
            $('#agents-datatable').DataTable();
        });

        $(document).ready(function() {
            $('#basic-datatable').DataTable();
        });



        $('.click').click(function() {
            $('#mtl').click(function() {
                $('#picture').attr('src',
                    'http://profile.ak.fbcdn.net/hprofile-ak-ash3/41811_170099283015889_1174445894_q.jpg'
                );
            });


        });
        

        $(document).ready(function() {
             jQuery(function() {
                jQuery('#onfoot').click();
            })

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
