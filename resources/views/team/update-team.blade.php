@extends('layouts.vertical', ['title' => 'Update Team'])

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />
    <style>
        .sub {
            margin-top: 30px;
        }

    </style>
@endsection

@php
$tagname = [];

foreach ($team->tags as $item){
array_push($tagname,$item->name);
}
if (isset($tagname)) {
    $List = implode(' , ', $tagname);
}


@endphp
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{__("Team")}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if (isset($team))
                            <form id="UpdateTeam" method="post" action="{{ route('team.update', $team->id) }}"
                                enctype="multipart/form-data">
                                @method('PUT')
                            @else
                                <form id="StoreTeam" method="post" action="{{ route('team.store') }}"
                                    enctype="multipart/form-data">
                        @endif
                        @csrf

                        <div class=" row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="control-label">{{__("NAME")}}</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ old('name', $team->name ?? '') }}" placeholder="John Doe">
                                    @if ($errors->has('name'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group" id="manager_idInput">
                                    <label for="team-manager">{{__("Manager")}}</label>
                                    <select class="form-control" id="team-manager" name="manager_id">
                                        @foreach ($agents as $agent)
                                            <option value="{{ $agent->id }}" @if ($agent->id == $team->manager_id) selected
                                        @endif >{{ $agent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" id="location_accuracyInput">
                                    <label for="location_accuracy" class="control-label">{{__("Location Accuracy")}}</label>
                                    <select class="form-control" id="location_accuracy" name="location_accuracy">
                                        @foreach ($location_accuracy as $k => $la)
                                            <option value="{{ $k }}" @if ($team->location_accuracy == $k) selected
                                        @endif>{{ __($la) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('location_accuracy'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('location_accuracy') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="location_frequencyInput">
                                    <label for="location_frequency" class="control-label">{{__("Location Frequency")}}</label>
                                    <select class="form-control" id="location_frequency" name="location_frequency">
                                        @foreach ($location_frequency as $k => $lf)
                                            <option value="{{ $k }}" @if ($team->location_frequency == $k) selected
                                        @endif>{{ __($lf) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('location_frequency'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('location_frequency') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="control-label">{{__("Tags")}}</label>
                                <input id="form-tags-4" name="tags" type="text" value="{{isset($List) ? $List: ''}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-md-12 sub">
                                <button type="submit" class="btn btn-info waves-effect waves-light">{{__("Submit")}}</button>
                            </div>

                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



    </div> <!-- container -->
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
        integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>

    <script>
        $(function() {

            var tagvar = <?php  echo json_encode($tags); ?>;
            $('#form-tags-4').tagsInput({
                'autocomplete': {
                    source: tagvar
                }
            })


        });

    </script>

@endsection
