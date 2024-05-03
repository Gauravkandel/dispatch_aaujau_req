<div id="edit-team-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Edit Team</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="updateTeam" action="{{ route('team.store') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body px-3 pb-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="nameInput">
                                <label for="name" class="control-label">NAME</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="John Doe">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="form-group mb-3" id="manager_idInput">
                                <label for="team-manager">Manager</label>
                                <select class="form-control" id="team-manager" name="manager_id">
                                    @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> -->
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3" id="location_accuracyInput">
                                <label for="location_accuracy">Location Accuracy</label>
                                <select class="form-control" id="location_accuracy" name="location_accuracy">
                                    @foreach($location_accuracy as $k=>$la)
                                    <option value="{{ $k }}">{{ __($la) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3" id="location_frequencyInput">
                                <label for="location_frequency">Location Frequency</label>
                                <select class="form-control" id="location_frequency" name="location_frequency">
                                    @foreach($location_frequency as $k=>$lf)
                                    <option value="{{ $k }}">{{ __($lf) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="tagsInput">
                                <label for="tags" class="control-label">ADD TAGS</label>
                                <select class="form-control select2-multiple" data-toggle="select2" multiple="multiple"
                                    data-placeholder="Choose ..." name="tagsUpdate[]" id="tagsUpdate">
                                    @foreach($tags as $tag)
                                    <option value="{{$tag->id}}">{{$tag->name}}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->