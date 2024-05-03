@extends('layouts.vertical', ['title' => 'Options'])

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
</style>
@endsection

@section('content')
@include('modals.add-manager')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Managers</h4>
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
                        data-target="#add-manager-modal" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> Add Manager</button>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($managers as $manager)
                                <tr>
                                    <td class="table-user">
                                        <a href="javascript:void(0);"
                                            class="text-body font-weight-semibold">{{$manager->name}}</a>
                                    </td>
                                    <td>
                                        {{$manager->email}}
                                    </td>
                                    <td>
                                        {{$manager->phone_number}}
                                    </td>
                                    <!-- <td>
                                        <span class="badge bg-soft-success text-success">Active</span>
                                    </td> -->

                                    <td>
                                        <a href="{{route('manager.edit', $manager->id)}}" class="action-icon"> <i
                                                class="mdi mdi-square-edit-outline"></i></a>
                                        <!-- <a href="{{route('manager.destroy', $manager->id)}}" class="action-icon">
                                            <i class="mdi mdi-delete"></i>
                                        </a> -->
                                        <form method="POST" action="{{route('manager.destroy', $manager->id)}}" class="action-icon">
                                            @csrf
                                            @method('DELETE')
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary-outline action-icon"> <i
                                                        class="mdi mdi-delete"></i></button>

                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{ $managers->links() }}
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>


</div>
@endsection

@section('script')

<!-- Plugins js-->
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-advanced.init.js')}}"></script>
<script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>

<script src="{{asset('assets/js/storeManager.js')}}"></script>

<!-- for File Upload -->
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.7/js/intlTelInput.js"></script>
<script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>

<script>
$("#phone_number").intlTelInput({
  utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/js/utils.js"
});
$('.intl-tel-input').css('width','100%');

var regEx = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/;
$("#addManager").bind("submit", function() {
       var val = $("#phone_number").val();
       if (!val.match(regEx)) {
            $('#phone_number').css('color','red');
            return false;
        }
});

$(function(){
    $('#phone_number').focus(function(){
        $('#phone_number').css('color','#6c757d');
    });
});


</script>
@endsection