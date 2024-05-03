@extends('layouts.god-vertical', ['title' => 'Options'])

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
                <h4 class="page-title">Currencies</h4>
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
                        

                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Iso Code</th>
                                    <th>Symbol</th>
                                    <th>Priority</th>
                                    <!-- <th style="width: 85px;">Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($currency as $curr)
                                <tr>
                                    <td>
                                        {{$curr->name}}
                                    </td>
                                    <td>
                                        {{$curr->iso_code}}
                                    </td>
                                    <td>
                                        {{$curr->symbol}}
                                    </td>
                                    <td>
                                        {{$curr->priority}}
                                    </td>
                                    
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{ $currency->links() }}
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