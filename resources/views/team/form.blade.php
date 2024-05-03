<div class=" row">
    <div class="col-md-12">
        <div class="form-group" id="nameInputEdit">
            <label for="name" class="control-label">{{__("NAME")}}</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $team->name ?? '') }}" placeholder="John Doe">
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>

    {{-- <div class="col-md-6">
        <div class="form-group" id="manager_idInputEdit">
            <label for="team-manager">Manager</label>
            <select class="form-control" id="team-manager" name="manager_id">
                @foreach ($agents as $agent)
                    <option value="{{ $agent->id }}" @if ($agent->id == $team->manager_id) selected
                @endif >{{ $agent->name }}</option>
                @endforeach
            </select>
        </div>
    </div> --}}

    <div class="col-md-6">
        <div class="form-group" id="location_accuracyInputEdit">
            <label for="location_accuracy" class="control-label">{{__("Location Accuracy")}}</label>
            <input type="hidden" id="team_id" val_id="{{ $team->id }}" url="{{route('team.update', $team->id)}}">
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
        <div class="form-group" id="location_frequencyInputEdit">
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
    <div class="col-md-12">
        <div class="form-group mb-0">
            <label class="control-label">{{__("Tags")}}</label>
        <input id="form-tags-4" name="tags" type="text" value="{{isset($teamTagIds) ? implode(',', $teamTagIds) : ''}}" class="myTag1">
        </div>
    </div>

</div>