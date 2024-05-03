@extends('layouts.vertical', ['title' => getAgentNomenclature()])

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

    </style>
@endsection

@section('content')
    @include('modals.add-agent')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ Session::get('agent_name') }}</h4>
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
                                <button type="button" class="btn btn-blue waves-effect waves-light" data-toggle="modal"
                                    data-target="#add-agent-modal" data-backdrop="static" data-keyboard="false"><i
                                        class="mdi mdi-plus-circle mr-1"></i> Add {{ Session::get('agent_name') }}</button>
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
@endsection

@section('script')

    <!-- Plugins js-->
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <!-- Page js-->
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-pickers.init.js') }}"></script>

    <script src="{{ asset('assets/js/storeAgent.js') }}"></script>

    <!-- for File Upload -->
    <script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
    <!-- Page js-->
    <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.7/js/intlTelInput.js"></script>

    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

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
