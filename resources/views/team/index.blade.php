@extends('layouts.vertical', ['title' => 'Team'])

@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />

<style>

</style>
@endsection

@section('content')

<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{__("Team")}}</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-xl-8">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <div class="text-sm-left">
                        @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <span>{!! \Session::get('success') !!}</span>
                        </div>
                        @endif
                        @if (\Session::has('error'))
                        <div class="alert alert-danger">
                            <span>{!! \Session::get('error') !!}</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-sm-4 text-right">
                    <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add Team")}}</button>
                </div>

            </div>

            <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap w-100" id="teams-datatable">
                    <thead>
                        <tr>
                            <th>{{__("Team Name")}}</th>
                            <th>{{__("Location Accuracy")}}</th>
                            <th>{{__("Location Frequency")}}</th>
                            <th>{{__("Team Strength")}}</th>
                            <th>{{__("Tags")}}</th>
                            <th>{{__("Action")}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teams as $team)

                        <tr class="team-list-1 cursors" data-id="{{ $team->id }}">
                            <td class="table-user">
                                <a href="javascript:void(0);" class="text-body font-weight-semibold">{{ $team->name }}</a>
                            </td>
                            <td>{{ $team->location_accuracy }}</td>
                            <td>{{ $team->location_frequency }}</td>
                            <td>{{$team->agents->count()}}</td>
                            <td>
                                @php
                                $tagname = [];

                                foreach ($team->tags as $item){
                                array_push($tagname,$item->name);
                                }
                                @endphp
                                {{ $List = implode(' , ', $tagname) }}
                            </td>
                            <td>
                                <div class="form-ul" style="width: 60px;">
                                    <div class="inner-div"> <a href="#" class="action-icon editIcon" teamId="{{$team->id}}"> <i class="mdi mdi-square-edit-outline"></i></a>
                                    </div>
                                    <div class="inner-div">
                                        <form method="POST" id="teamdelete{{$team->id}}" action="{{ route('team.destroy', $team->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary-outline action-icon"> <i class="mdi mdi-delete" data-teamid="{{$team->id}}"></i></button>
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
                {{ $teams->links() }}
            </div>
        </div> <!-- end col -->

        @foreach ($teams as $index => $team)
        <div class="col-xl-4 team-agent-list" id="team_agents_{{ $team->id }}" @if ($index !=0) style="display:none;" @endif>
            <div class="card-box">
                <div class="dropdown float-right">
                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">

                        {{-- <a href="javascript:void(0);" class="dropdown-item">{{__("Edit Report")}}</a> --}}

                        <a href="{{route('team.agents.export', $team->id)}}" class="dropdown-item">{{__("Export Report")}}</a>

                        {{-- <a href="javascript:void(0);" class="dropdown-item">{{__("Action")}}</a> --}}
                    </div>
                </div>

                <h4 class="header-title mb-3">{{__(getAgentNomenclature()) }}</h4>

                <div class="table-responsive">
                    <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                        <thead class="thead-light">
                            <tr>
                                <th>{{__("Name")}}</th>
                                <th>{{__("Action")}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($team->agents as $agent)
                            <tr>
                                <td>
                                    <h5 class="m-0 font-weight-normal">{{ $agent->name }}</h5>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('team.agent.destroy', ['team_id' => $agent->team_id, 'agent_id' => $agent->id]) }}" class="delete-team-agent-form">
                                        @csrf
                                        @method('DELETE')
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary-outline action-icon"> <i class="mdi mdi-delete"></i></button>

                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div> <!-- end .table-responsive-->
            </div> <!-- end card-box-->
        </div>
        @endforeach
    </div>
</div>

@include('team.team-modal')

@endsection

@section('script')

<script src="{{ asset('assets/js/jquery-ui.min.js') }}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
<script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>

<link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />
<script>
    if (window.module) module = window.module;
</script>

@include('team.team-script')

@endsection