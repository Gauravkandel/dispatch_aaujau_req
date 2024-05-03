@extends('layouts.god-vertical', ['title' => 'Plans'])
@section('css')
<link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/jquery-toast-plugin/jquery-toast-plugin.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ __('Socket Urls') }}</h4>
            </div>
        </div>
    </div>
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
                            @if (\Session::has('error_delete'))
                                <div class="alert alert-danger">
                                    <span>{!! \Session::get('error') !!}</span>
                                </div>
                            @endif
                            @if ( ($errors) && (count($errors) > 0) )
                                <div class="alert alert-danger">
                                    <ul class="m-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <a class="btn btn-info waves-effect waves-light text-sm-right" href="#add" data-toggle="modal" data-target="#add-billing-plan"><i class="mdi mdi-plus-circle mr-1"></i> Add </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <form name="savebillingplan" id="savebillingplan"> @csrf </form>
                        <table class="table table-centered table-nowrap table-striped" id="socket-datatable">
                            <thead>
                                <tr>
                                    
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('url') }}</th>

                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="billingplan_list">
                                @foreach($ChatSockets as $ChatSocket)
                                <?php
                                ?>
                                <tr data-row-id="{{$ChatSocket->id}}">
                                    
                                    <td><a href="javascript:void(0)" class="editSocketBtn" data-id="{{$ChatSocket->id}}">{{__($ChatSocket->title)}}</a></td>
                                    <td>{{__($ChatSocket->domain_url)}}</td>
                                    <td>
                                        <input type="checkbox" data-id="{{$ChatSocket->id}}" data-plugin="switchery" name="userBillingPlanStatus" class="chk_box status_check" data-color="#43bee1" {{($ChatSocket->status == 1) ? 'checked' : ''}} >
                                    </td>
                                    <td>
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div" >
                                             
                                                    <a href="javascript:void(0)" class="action-icon editSocketBtn" data-id="{{$ChatSocket->id}}"><i class="mdi mdi-square-edit-outline"></i></a>
                                                    <a href="javascript:void(0)" class="action-icon deletePlanBtn" data-id="{{$ChatSocket->id}}"><i class="mdi mdi-delete"></i></a>
                                               
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                     
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end Content-->

        

    


    <div id="add-billing-plan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addBillingPlan_Label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __('Add Socket Url') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            {{ Form::open(array('id' => 'socket_form', 'method' => 'post', 'enctype' => 'multipart/form-data', 'route' => 'chatsocket.save')) }}
           
                @csrf
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                
                                <div class="col-md-12">
                                    <div class="form-group" id="nameInput">
                                        {!! Form::label('title', __('Title'),['class' => 'control-label']) !!}
                                        {!! Form::text('title', null, ['class'=>'form-control', 'required'=>'required']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group" id="nameInput">
                                        {!! Form::label('domain_url', __('Socket'),['class' => 'control-label']) !!}
                                        {!! Form::text('domain_url', null, ['class'=>'form-control', 'required'=>'required']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('status', __('Status'),['class' => 'control-label']) !!}
                                        <div class="mt-md-1">
                                            <input type="checkbox" data-plugin="switchery" name="status" class="form-control status chk_box" data-color="#43bee1" checked='checked'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light submitAddSubscriptionForm">{{ __("Submit") }}</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div id="socket-plan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editBillingPlan_Label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js')}}"></script>
<script type="text/javascript">
    var edit_socket_url = "{{ route('chatsocket.edit', ':id') }}";
    var delete_socket = "{{ route('chatsocket.delete', ':id') }}";

    var update_socket_status_url = "{{route('chatsocket.upDateSocketStatus', ':id')}}";
    
    $(document).delegate(".editSocketBtn", "click", function(){
        let slug = $(this).attr("data-id");
        $.ajax({
            type: "get",
            dataType: "json",
            url: edit_socket_url.replace(":id", slug),
            success: function(res) {
                $("#socket-plan .modal-content").html(res.html);
                $("#socket-plan").modal("show");
                
                var elems = Array.prototype.slice.call($('.chk_box1'));
                    elems.forEach(function(html) {
                    var switchery =new Switchery(html);
                });
            }
        });
    });

    $("#socket-datatable .status_check").on("change", function() {
        var slug = $(this).attr('data-id');
        var status = 2;
        if($(this).is(":checked")){
            status = 1;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        $.ajax({
            type: "post",
            dataType: "json",
            url: update_socket_status_url.replace(":id", slug),
            data: {status: status},
            success: function(jsondata) {
                if(jsondata.success)
                {var color = 'green';var heading ="Success!";}else{var color = 'red';var heading ="Error!";}
                $.toast({ 
                heading:heading,
                text : jsondata.message, 
                showHideTransition : 'slide', 
                bgColor : color,              
                textColor : '#eee',            
                allowToastClose : true,      
                hideAfter : 5000,            
                stack : 5,                   
                textAlign : 'left',         
                position : 'top-right'      
                })
            }
        });
    });


    $(document).delegate(".deletePlanBtn", "click", function(){
        let id = $(this).attr("data-id");
        Swal.fire({
            title: "{{__('Are you sure?')}}",
            text:"{{__('This will delete socket url.')}}",
                // icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            console.log(result);
            if(result.value)
            {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    url: delete_socket.replace(":id", id),
                    success: function(res) {
                       if(res.status == true) {

                        Swal.fire({
                            title: "{{__('Deleted')}}",
                            text:"{{__('Url has been deleted.')}}",
                                // icon: 'info',
                            showCancelButton: false,
                            confirmButtonText: 'Ok',
                        }).then((result) => {
                                location.reload();
                        })

                       }
                    }
                });
            }else{
                return false;
            }
        });
       
    });
    var elems = Array.prototype.slice.call(document.querySelectorAll('.chk_box'));
        elems.forEach(function(html) {
        var switchery =new Switchery(html);
    });

</script>
@endsection
