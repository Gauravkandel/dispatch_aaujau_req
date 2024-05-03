@extends('layouts.god-vertical', ['title' => 'Clients'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Clients</h4>
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
                                @if(\Session::has('success'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('success') !!}</span>
                                </div>
                                @endif

                                @if(\Session::has('error'))
                                <div class="alert alert-error">
                                    <span>{!! \Session::get('error') !!}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <a class="btn btn-blue waves-effect waves-light text-sm-right"
                                href="{{route('client.create')}}"><i class="mdi mdi-plus-circle mr-1"></i> Add
                                Client</a>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Database Name</th>
                                    <th>Client Code</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                <tr>
                                    <td class="table-user">
                                        <a href="javascript:void(0);"
                                            class="text-body font-weight-semibold">{{$client->name}}</a>
                                    </td>
                                    <td>
                                        {{$client->email}}
                                    </td>
                                    <td>
                                        {{$client->phone_number}}
                                    </td>
                                    <td>
                                        {{$client->database_name}}
                                    </td>
                                    <td>
                                        {{ $client->code }}
                                    </td>
                                    <!-- <td>
                                        <span class="badge bg-soft-success text-success">Active</span>
                                    </td> -->


                                    <td>
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div"> <a href1="#" href="{{route('client.edit', $client->id)}}"  class="action-icon editIconBtn"> <i class="mdi mdi-square-edit-outline"></i></a></div>
                                            <div class="inner-div">
                                                <form id="clientdelete{{$client->id}}" method="POST" action="{{route('client.destroy', $client->id)}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-primary-outline action-icon"> <i class="mdi mdi-delete" clientid="{{ $client->id }}"></i></button>

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
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{ $clients->links() }}
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>


</div>
@endsection

@section('script')
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>

<script>
    $('.mdi-delete').click(function(){
            
            var r = confirm("{{__('Are you sure?')}}");
            if (r == true) {
               var clientid = $(this).attr('clientid');
               $('form#clientdelete'+clientid).submit();

            }
        });
    </script>

<!-- @parent

@if(count($errors->add) > 0)
<script>
$(function() {
    $('#add-client-modal').modal({
        show: true
    });
});
</script>
@elseif(count($errors->update) > 0)
<script>
$(function() {
    $('#update-client-modal').modal({
        show: true
    });
});
</script>
@endif
@if(\Session::has('getClient'))
<script>
$(function() {
    $('#update-client-modal').modal({
        show: true
    });
});
</script>
@endif -->
@endsection