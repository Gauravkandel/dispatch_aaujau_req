@extends('layouts.vertical', ['title' => 'Options'])

@section('css')

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

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Manager</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($manager))
                    <form id="UpdateManager" method="post" action="{{route('manager.update', $manager->id)}}"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @else
                        <form id="StoreManager" method="post" action="{{route('manager.store')}}"
                            enctype="multipart/form-data">
                            @endif
                            @csrf
                            <div class="modal-body px-3 py-0">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <div class="form-group" id="profile_pictureInput">
                                        <input type="file" data-plugins="dropify" name="profile_picture"  data-default-file="{{isset($manager->profile_picture) ? asset('managers/'.$manager->profile_picture.'') : ''}}"/>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                    <p class="text-muted text-center mt-2 mb-0">Profile Pic</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group" id="nameInput">
                                        <label for="name" class="control-label">NAME</label>
                                        <input type="text" class="form-control" id="name" placeholder="John Doe" name="name" value="{{ old('name', $manager->name ?? '')}}" required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group" id="emailInput">
                                        <label for="email" class="control-label">EMAIL</label>
                                        <input type="email" class="form-control" id="email" placeholder="abc@example.com" name="email" value="{{ old('email', $manager->email ?? '')}}" required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group" id="phone_numberInput">
                                        <label for="phone_number" class="control-label">CONTACT NUMBER</label>
                                        <div class="input-group">
                                            <input type="text" name="phone_number" class="form-control" id="phone_number"
                                                placeholder="Enter mobile number" value="{{ old('phone_number', $manager->phone_number ?? '')}}" required>
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="can_create_taskInput">
                                        <p class="text-muted mt-3 mb-2">Permission to create task</p>
                                        <div class="radio radio-primary form-check-inline">
                                            <input type="radio" id="yes1" value="1" name="can_create_task" @if($manager->can_create_task) checked @endif>
                                            <label for="yes1"> Yes </label>
                                        </div>
                                        <div class="radio radio-success form-check-inline">
                                            <input type="radio" id="no1" value="0" name="can_create_task" @if(!$manager->can_create_task) checked @endif>
                                            <label for="no1"> No </label>
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="can_edit_task_createdInput">
                                        <p class="text-muted mt-3 mb-2">Permission to edit own tasks</p>
                                        <div class="radio radio-primary form-check-inline">
                                            <input type="radio" id="yes2" value="1" name="can_edit_task_created" @if($manager->can_edit_task_created) checked @endif>
                                            <label for="yes2"> Yes </label>
                                        </div>
                                        <div class="radio radio-success form-check-inline">
                                            <input type="radio" id="no2" value="0" name="can_edit_task_created" @if(!$manager->can_edit_task_created) checked @endif>
                                            <label for="no2"> No </label>
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="can_edit_allInput">
                                        <p class="text-muted mt-3 mb-2">Permission to Edit any tasks</p>
                                        <div class="radio radio-primary form-check-inline">
                                            <input type="radio" id="yes3" value="1" name="can_edit_all" @if($manager->can_edit_all) checked @endif>
                                            <label for="yes3"> Yes </label>
                                        </div>
                                        <div class="radio radio-success form-check-inline">
                                            <input type="radio" id="no3" value="0" name="can_edit_all" @if(!$manager->can_edit_all) checked @endif>
                                            <label for="no3"> No </label>
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="can_manage_unassigned_tasksInput">
                                        <p class="text-muted mt-3 mb-2">Permission to Edit any tasks</p>
                                        <div class="radio radio-primary form-check-inline">
                                            <input type="radio" id="yes4" value="1" name="can_manage_unassigned_tasks" @if($manager->can_manage_unassigned_tasks) checked @endif>
                                            <label for="yes4"> Yes </label>
                                        </div>
                                        <div class="radio radio-success form-check-inline">
                                            <input type="radio" id="no4" value="0" name="can_manage_unassigned_tasks" @if(!$manager->can_manage_unassigned_tasks) checked @endif>
                                            <label for="no4"> No </label>
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="can_edit_auto_allocationInput">
                                        <p class="text-muted mt-3 mb-2">Permission to Edit any tasks</p>
                                        <div class="radio radio-primary form-check-inline">
                                            <input type="radio" id="yes5" value="1" name="can_edit_auto_allocation" @if($manager->can_edit_auto_allocation) checked @endif>
                                            <label for="yes5"> Yes </label>
                                        </div>
                                        <div class="radio radio-success form-check-inline">
                                            <input type="radio" id="no5" value="0" name="can_edit_auto_allocation" @if(!$manager->can_edit_auto_allocation) checked @endif>
                                            <label for="no5"> No </label>
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Add</button>
                        </div>
                        </form>
                </div>
            </div>
        </div>
    </div>



</div>
@endsection

@section('script')


<script>
$("#phone_number").intlTelInput({
  utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/js/utils.js"
});
$('.intl-tel-input').css('width','100%');

var regEx = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/;
$("#updateManager").bind("submit", function() {
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