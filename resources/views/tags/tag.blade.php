@extends('layouts.vertical', ['title' => 'Tags'])

@section('css')
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-6">
            <div class="page-title-box">
                <h4 class="page-title">Settings</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row mb-2">
        <div class="col-md-6">
            <div class="text-sm-left">
                @if (\Session::has('success'))
                <div class="alert alert-success">
                    <span>{!! \Session::get('success') !!}</span>
                </div>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="text-right">
                <a class="btn btn-blue waves-effect waves-light text-sm-right" href="{{route('tag.create')}}">
                    <i class="mdi mdi-plus-circle mr-1"></i> Add
                    Tag</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="text-sm-left">
                                Tag for {{ auth()->user()->getPreference->agent_name ?? 'Agents' }}
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agentTags as $tag)
                                <tr>
                                    <td class="table-user">
                                        <a href="javascript:void(0);"
                                            class="text-body font-weight-semibold">{{$tag->name}}</a>
                                    </td>

                                    <td>
                                        <a href="{{route('tag.edit', [$tag->id, 'agent'])}}" class="action-icon"> <i
                                                class="mdi mdi-square-edit-outline"></i></a>
                                        <form class="action-icon" method="POST"
                                            action="{{route('tag.destroy', [$tag->id, 'agent'])}}">
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
                        {{ $agentTags->links() }}
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->

        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="text-sm-left">
                                Tag for Team
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teamTeags as $tag)
                                <tr>
                                    <td class="table-user">
                                        <a href="javascript:void(0);"
                                            class="text-body font-weight-semibold">{{$tag->name}}</a>
                                    </td>

                                    <td>
                                        <a href="{{route('tag.edit', [$tag->id, 'team'])}}" class="action-icon"> <i
                                                class="mdi mdi-square-edit-outline"></i></a>
                                        <form class="action-icon" method="POST"
                                            action="{{route('tag.destroy', [$tag->id, 'team'])}}">
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
                        {{ $teamTeags->links() }}
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>


</div>
@endsection

@section('script')

@endsection