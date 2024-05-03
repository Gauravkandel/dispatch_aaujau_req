<div id="add-team-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">{{__("Add Team")}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="submitTeam" action="{{ route('team.store') }}" method="POST">
                @csrf
                <div class="modal-body px-3 py-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="nameInput">
                                <label for="name" class="control-label">{{__("NAME")}}</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="John Doe">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3" id="location_accuracyInput">
                                <label for="location_accuracy">{{__("Location Accuracy")}}</label>
                                <select class="form-control" id="location_accuracy" name="location_accuracy">
                                    @foreach ($location_accuracy as $k => $la)
                                        <option value="{{ $k }}">{{ __($la) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3" id="location_frequencyInput">
                                <label for="location_frequency">{{__("Location Frequency")}}</label>
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
                                <label>{{__("Tag")}}</label>
                                <input id="form-tags-1" name="tags" type="text" value="" class="myTag1">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light addTeamForm">{{__("Submit")}}</button>
                </div>
                
            </form>
        </div>
    </div>
</div>

<div id="edit-team-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">{{__("Edit Team")}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="UpdateTeam" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body py-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-box p-0" id="editCardBox">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light submitEditForm">{{__("Submit")}}</button>
                </div>
                
            </form>
        </div>
    </div>
</div>

