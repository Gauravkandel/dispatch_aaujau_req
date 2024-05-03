@extends('layouts.vertical', ['title' => 'Task'])

@section('css')
<link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />


<!-- for File Upload -->

<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/css/intlTelInput.css'>
<style>
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
    #radio1, #radio2, #radio3, #radio4 {  
        -ms-transform: scale(1.2); /* IE 9 */
        -webkit-transform: scale(1.2); /* Chrome, Safari, Opera */
        transform: scale(1.2); }
    #adds {
        margin-bottom: 14px;
    }

    .shows {
        display: none;
    }

    .rec {
        margin-bottom: 7px;
    }

    .needsclick {

        margin-left: 27%;
    }

    .padd {
        padding-left: 9% !important;
    }

    .newchnage {
        margin-left: 27% !important;
    }

    .address {
        margin-bottom: 6px
    }

    .tags {
        display: none;
    }

    #typeInputss {
        overflow-y: auto;
        height: 168px;
    }

    .upload {
        margin-bottom: 20px;
        margin-top: 10px;

    }

    .span1 {
        color: #ff0000;
    }

    .check {
        margin-left: 116px !important;
    }

    .newcheck {
        margin-left: -54px;
    }

    .upside {
        margin-top: -10px;
    }

    .newgap {
        margin-top: 11px !important;
    }

    

    .append {
        margin-bottom: 15px;
    }

    .spanbold {
        font-weight: bolder;
    }

    .copyin {
        background-color: rgb(148 148 148 / 11%);
        margin-top: 10px;
        

    }
    
    hr.new3 {
     border-top: 1px dashed white;
     margin: 0 0 .5rem 0;
   }
   #spancheck{
       display: none;
   }
   .imagepri{
    min-width: 50px;
       height: 50px;
       width: 50px;
       border-style: groove;
       margin-left: 5px;
       margin-top: 5px;
   }
   .withradio{
   
    
   }
   .showsimage{
    margin-top: 31px;
    margin-left: 140px;
   }
   .showshadding{
    margin-left: 98px;
   }
   .newchnageimage{
       margin-left: 100px;
   }
   .showsimagegall{
    margin-left: 148px;
    margin-top: 21px;

   }
   .allset{
       margin-left: 9px !important;
       padding-top: 10px;
   }

.pac-container, .pac-container .pac-item, .ui-menu.ui-autocomplete { z-index: 99999 !important; }

</style>
@endsection

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ auth()->user()->getPreference->agent_name ?? 'Tasks' }}</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="text-sm-left">
                                @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('success') !!}</span>
                                </div>
                                @endif
                            </div>
                        </div> 

                        <div class="col-sm-6">
                        <form name="getTask" id="getTask" method="get" action="{{route('tasks.index')}}">
                            <div class="login-form">
                            <ul class="list-inline">
                                <li class="d-inline-block mr-2">
                                  <input type="radio" id="student" onclick="handleClick(this);" name="animal" value="" checked>
                                  <label for="student">All</label>
                                </li>
                                <li class="d-inline-block mr-2">
                                  <input type="radio" id="teacher" name="animal" onclick="handleClick(this);">
                                  <label for="teacher">Pending</label>
                                </li>
                  
                                <li class="d-inline-block mr-2">
                                  <input type="radio" id="parent" name="animal" value="" onclick="handleClick(this);">
                                  <label for="parent">History</label>
                                </li>
                              </ul>
                            </div>
                                {{-- <div class="d-inline-block mr-3">
                                    <input type="radio" name="status" onclick="handleClick(this);" id="radio1" value="all" {{(!isset($status) || $status == 'all') ? 'checked' : ''}}>
                                    <label for="radio1">All</label>
                                </div>
                                <div class="d-inline-block mr-3">
                                    <input type="radio" name="status" onclick="handleClick(this);" id="radio2" value="pending" {{(isset($status) && $status == 'pending') ? 'checked' : ''}}>
                                    <label for="radio2">Pending</label>
                                </div>
                                
                                <div class="d-inline-block mr-3">
                                    <input type="radio" name="status" onclick="handleClick(this);" id="radio3" value="active" {{(isset($status) && $status == 'active') ? 'checked' : ''}}>
                                    <label for="radio3">Active</label>
                                </div>
                               <div class="d-inline-block">
                                <input type="radio" name="status" onclick="handleClick(this);" id="radio4" value="completed" {{(isset($status) && $status == 'completed') ? 'checked' : ''}}>
                                <label for="radio4">Completed</label>
                               </div> --}}

                        </form>
                        </div>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4 text-right">
                            <button type="button" class="btn btn-blue waves-effect waves-light addTaskModal1" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> Add Task</button>
                         <!--<a href="{{ route('tasks.create') }}" class="btn btn-blue waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i> Add Task</a> -->
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100"  id="agents-datatable">
                            <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Customer</th>
                                    {{-- <th>Order Id</th> --}}
                                    <th>{{getAgentNomenclature()}}</th>
                                    <th>Create Time</th>
                                    <th>Pricing Rule</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                <tr>
                                    
                                    <td>
                                        {{$task->id}}
                                    </td>
                                    <td>
                                        {{$task->customer->name}}
                                    </td>
                                    {{-- <td>
                                        {{$task->order->id}}
                                    </td> --}}
                                    <td>
                                        UnAssind
                                    </td>
                                    <td>
                                        {{$task->created_at}}
                                    </td>
                                    <td>
                                        Not Alloted
                                    </td>

                                    <td>
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div"> <a href1="#" href="{{route('tasks.edit', $task->id)}}"  class="action-icon editIconBtn"> <i class="mdi mdi-square-edit-outline"></i></a></div>
                                            <div class="inner-div">
                                                <form method="POST" action="{{route('tasks.destroy', $task->id)}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary-outline action-icon"> <i class="mdi mdi-delete"></i></button>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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

<div class="copyin forClone" id="copyin1" style="display: none;">
    <div class="requried allset">
        <div class="row firstclone1">
            <div class="col-md-4">
                <h4 class="header-title mb-3 newgap">Task</h4>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <select class="form-control selecttype" id="task_type" name="task_type_id[]" required="">
                        <option value="">Selcet Task </option>
                        <option value="1">Pickup</option>
                        <option value="2">Drop</option>
                        <option value="3">Appointment</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group appoint" style="display: none;">
                    <input class="form-control
                    appointment_date" placeholder="Duration (In Min)" name="appointment_date[]" type="text">
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>

            </div>
            <div class="col-md-1 ">
                <span class="span1 onedelete" id="newspan"><img src="https://royodelivery.com/assets/images/ic_delete.png" alt=""></span>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <h4 class="header-title mb-2">Address</h4>
            </div>
            <div class="col-md-6">
                <h4 class="header-title mb-2">Saved Addresses</h4>
            </div>
        </div>
        
        <span class="span1 addspan" style="display: none;">Please select a address or create new</span>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group alladdress" id="typeInput">
                    {!! Form::text('short_name[]', null, ['class' => 'form-control address',
                    'placeholder' => 'Address Short Name','required' => 'required']) !!}

                    <input type="text" id="#CURRENTMAP-input" name="address[]" class="form-control address cust_add" placeholder="Address">
                    <div class="input-group-append">
                        <button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="#CURRENTMAP"> <i class="mdi mdi-map-marker-radius"></i></button>
                    </div>
                    <input type="hidden" name="latitude[]" id="#CURRENTMAP-latitude" value="0" class="cust_latitude" />
                    <input type="hidden" name="longitude[]" id="#CURRENTMAP-longitude" value="0" class="cust_longitude" />
                    {!! Form::text('post_code[]', null, ['class' => 'form-control address','placeholder' => 'Post Code','required' => 'required']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>

            </div>
            
            <div class="col-md-6 secondDiv">

            </div>
        </div>
        <hr class="new3">
    </div>
</div>

@include('task-new.modal')
@endsection

@section('script')

<!-- Plugins js-->
  
<script src="{{ asset('assets/js/jquery-ui.min.js') }}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB85kLYYOmuAhBUPd7odVmL6gnQsSGWU-4&libraries=places" async defer></script>

<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-advanced.init.js')}}"></script>
<script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>
<script src="{{asset('assets/libs/datatables/datatables.min.js')}}"></script>
<script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>

<script>
$(document).ready( function () {
    $('#agents-datatable').DataTable();
});

function handleClick(myRadio) {
    $('#getTask').submit();
}

</script>

@include('task-new.pagescript')

@endsection