@extends('layouts.vertical', ['title' => 'Options'])

@section('css')
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Settings</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($tag))
                    <form id="UpdateClient" method="post" action="{{route('tag.update', $tag->id)}}">
                        @method('PUT')
                        <input type="hidden" name="type" value="{{$type}}">
                        @else
                        <form id="StoreClient" method="post" action="{{route('tag.store')}}">
                            @endif
                            @csrf

                            <div class=" row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="control-label">NAME</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{ old('name', $tag->name ?? '')}}" placeholder="John Doe" required>
                                        @if($errors->has('name'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @if(!isset($tag))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type" class="control-label">Type</label>
                                        <select class="form-control" name="type" id="type">
                                            <option value="default">Default</option>
                                            <option value="team">For Team</option>
                                            <option value="agent">For {{getAgentNomenclature()}}s</option>
                                        </select>
                                        @if($errors->has('type'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('type') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-blue waves-effect waves-light">Submit</button>
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


@endsection