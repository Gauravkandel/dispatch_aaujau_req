@extends('layouts.vertical', ['title' => 'Create Team'])
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Team</h4>
                </div>
            </div>
        </div>
        
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                           
                            
                        </div>
                        <form id="submitTeam" action="{{ route('team.store') }}" method="POST">
                            @csrf
                            <div class="modal-body px-3 py-0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" id="nameInput">
                                            <label for="name" class="control-label">NAME</label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="John Doe"
                                                require>
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                   
                                </div>
            
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3" id="location_accuracyInput">
                                            <label for="location_accuracy">Location Accuracy</label>
                                            <select class="form-control" id="location_accuracy" name="location_accuracy">
                                                @foreach ($location_accuracy as $k => $la)
                                                    <option value="{{ $k }}">{{ __($la) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3" id="location_frequencyInput">
                                            <label for="location_frequency">Location Frequency</label>
                                            <select class="form-control" id="location_frequency" name="location_frequency">
                                                @foreach ($location_frequency as $k => $lf)
                                                    <option value="{{ $k }}">{{ __($lf) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group mb-3">
                                            <label>Tag</label>
            
                                            <input type="text" class="form-control" value="" name="tags" id="form-tags-4" >
                                        </div>
                                    </div>
            
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">Submit</button>
                                    </div>
        
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
