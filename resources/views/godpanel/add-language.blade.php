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
                <h4 class="page-title">Create Client</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($client))
                    <form id="UpdateClient" method="post" action="{{route('client.update', $client->id)}}" enctype="multipart/form-data">
                        @method('PUT')
                        @else
                        <form id="StoreClient" method="post" action="{{route('language.store')}}" enctype="multipart/form-data">
                            @endif
                            @csrf


                            <div class=" row">
                                <div class="col-md-6">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name" class="control-label">NAME</label>
                                            <input type="text" class="form-control" name="name" id="name" value="" placeholder="English">
                                            @if($errors->has('name'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name" class="control-label">Sort Code</label>
                                            <input type="text" class="form-control" name="sort_code" id="name" value="" placeholder="en">
                                            @if($errors->has('sort_code'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('sort_code') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>



                                    <div class="row">
                                    <div class="col-md-10">
                                         
                                        </div>
                                        <div class="col-md-2 pull-right">
                                            <button type="submit" class="btn btn-info waves-effect waves-light">Submit</button>
                                        </div>

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
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>


@endsection