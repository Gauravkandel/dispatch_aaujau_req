@php
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/900/900/sm/0/plain/';
@endphp
 <div class="row mb-2">
    <div class="col-md-4">
        <div class="form-group" id="profile_pictureInputEdit">
            <input type="file" id="profilePic" data-plugins="dropify" name="profile_picture" data-default-file="" showImg="{{ isset($agent->profile_picture) ? Storage::disk('s3')->url($agent->profile_picture) : '' }}" />
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
        <p class="text-muted text-center mt-2 mb-0">{{__("Profile Pic")}}</p>
    </div>
    <div class="col-md-8">
        <span>{{__('Live OTP')}}</span>
        <h4>{{isset($otp)?$otp: __('View OTP after Logging in the '.getAgentNomenclature().' App')}}</h4>
    </div>
</div>
<span class="show_all_error invalid-feedback"></span>
<div class="row">
    <input type="hidden" id="agent_id" val_id="{{ $agent->id }}" url="{{route('agent.update', $agent->id)}}">
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group" id="nameInputEdit">
            <label for="name" class="control-label">{{__("NAME")}}</label>
            <input type="text" class="form-control" id="name" placeholder="{{__('John Doe')}}" name="name"
                value="{{ old('name', $agent->name ?? '') }}">
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="phone_numberInputEdit">
            <label for="phone_number" class="control-label">{{__("CONTACT NUMBER")}}</label>
            <div class="input-group">
               
                <input type="tel" name="phone_number" class="form-control xyz" id="phone"
                    placeholder="Enter mobile number"
                    value="{{ $agent->phone_number }}" >
                    {{-- <input id="phone" name="phone" type="tel" class="form-control"> --}}
            </div>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group" id="typeInputEdit">
            <label for="type" class="control-label">{{__("TYPE")}}</label>
            <select class="form-control" data-style="btn-light" name="type" id="type">
                <option value="Employee" @if ($agent->type == 'Employee') selected @endif
                    >{{__("Employee")}}</option>
                <option value="Freelancer" @if ($agent->type == 'Freelancer') selected @endif
                    >{{__("Freelancer")}}</option>

            </select>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="team_idInputEdit">
            <label for="team_id" class="control-label">{{__("ASSIGN TEAM")}}</label>
            <select class="form-control" data-style="btn-light" name="team_id" id="team_id">
                @foreach ($teams as $team)
                    <option value="{{ $team->id }}" {{$team->id == $agent->team_id ? 'selected':''}}>{{ $team->name }}</option>
                @endforeach
                
            </select>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-12">
        <div class="form-group" id="vehicle_type_idInputEdit">
            <p class="text-muted mt-3 mb-2">{{__("TRANSPORT ICON")}}</p>
            <div class="radio radio-blue form-check-inline click cursors">
                <input type="radio" id="onfoot" value="1" act="edit" name="vehicle_type_id" @if ($agent->vehicle_type_id == '1') checked
                @endif>
                <img id="foot_edit" src="{{ $agent->vehicle_type_id == '1' ? asset('assets/icons/walk_blue.png') : asset('assets/icons/walk.png') }}">
            </div>

            <div class="radio radio-primery form-check-inline click cursors">
                <input type="radio" id="bycycle" value="2" name="vehicle_type_id" act="edit" @if ($agent->vehicle_type_id == '2')
                checked @endif >
                <img id="cycle_edit" src="{{ $agent->vehicle_type_id == '2' ? asset('assets/icons/cycle_blue.png') : asset('assets/icons/cycle.png') }}">
            </div>
            <div class="radio radio-info form-check-inline click cursors">
                <input type="radio" id="motorbike" value="3" name="vehicle_type_id" act="edit" @if ($agent->vehicle_type_id == '3') checked @endif>
                <img id="bike_edit" src="{{ $agent->vehicle_type_id == '3' ? asset('assets/icons/bike_blue.png') : asset('assets/icons/bike.png') }}">
            </div>
            <div class="radio radio-danger form-check-inline click cursors">
                <input type="radio" id="car" value="4" name="vehicle_type_id" act="edit" @if ($agent->vehicle_type_id == '4') checked
                @endif>
                <img id="cars_edit" src="{{ $agent->vehicle_type_id == '4' ? asset('assets/icons/car_blue.png') : asset('assets/icons/car.png') }}">
            </div>
            <div class="radio radio-warning form-check-inline click cursors">
                <input type="radio" id="truck" value="5" name="vehicle_type_id" act="edit" @if ($agent->vehicle_type_id == '5') checked @endif>
                <img id="trucks_edit"
                    src="{{ $agent->vehicle_type_id == '5' ? asset('assets/icons/truck_blue.png') : asset('assets/icons/truck.png') }}">
            </div>
            <div class="radio radio-red form-check-inline click cursors">
                <input type="radio" id="auto" value="6" name="vehicle_type_id" act="edit" @if ($agent->vehicle_type_id == '6') checked @endif>
                <img id="autos_edit"
                    src="{{ $agent->vehicle_type_id == '6' ? asset('assets/icons/auto_blue.png') : asset('assets/icons/auto.png') }}">
            </div>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label">{{__("Tags")}}</label>
        <input id="form-tags-4" name="tags" type="text" value="{{isset($tagIds) ? implode(',', $tagIds) : ''}}" class="myTag1">
        </div>
    </div>
</div>

{{-- <div class="row mt-3">
    <div class="col-md-6">
        <div class="form-group" id="make_modelInputEdit">
            <input type="hidden" id="agent_id" val_id="{{ $agent->id }}" url="{{route('agent.update', $agent->id)}}">
            <label for="make_model" class="control-label">{{__("TRANSPORT DETAILS")}}</label>
            <input type="text" class="form-control" id="make_model"
                placeholder="Year, Make, Model" name="make_model"
                value="{{ old('name', $agent->make_model ?? '') }}">
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="make_modelInput1">
            <label for="make_model" class="control-label">{{__("UID")}}</label>
            <input type="text" class="form-control" id="uid" placeholder="897abd" name="uid" value="{{ $agent->uid}}">
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group" id="plate_numberInputEdit">
            <label for="plate_number" class="control-label">{{__("LICENCE NUMBER")}}</label>
            <input type="text" class="form-control" id="plate_number" name="plate_number"
                placeholder="508.KLV" value="{{ old('name', $agent->plate_number ?? '') }}">
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="colorInputEdit">
            <label for="color" class="control-label">{{__("COLOR")}}</label>
            <input type="text" class="form-control" id="color" name="color" placeholder="Color" value="{{ old('name', $agent->color ?? '') }}">
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
</div> --}}
<div class="row">
    <div class="col-md-6">
        <div class="form-group" id="plate_numberInputEdit">
            <label for="plate_number" class="control-label">{{__("LICENCE NUMBER")}}</label>
            <input type="text" class="form-control" id="plate_number" name="plate_number"
                placeholder="508.KLV" value="{{ old('name', $agent->plate_number ?? '') }}">
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
</div>
<div class="row">
    @if(!empty($driver_registration_documents) && count($driver_registration_documents) > 0)
    @foreach($driver_registration_documents as $driver_registration_document)
    @php
    $field_value = "";
    if(!empty($agent_docs) && count($agent_docs) > 0){
        foreach($agent_docs as $key => $agent_doc){
            if($driver_registration_document->name == $agent_doc->label_name){
                $field_value = $agent_doc->file_name;
            }
        }
    }
    @endphp
    <div class="col-md-6">
        <div class="form-group" id="{{$driver_registration_document->name}}Input">
            <label for="" class="control-label d-flex align-items-center justify-content-between">{{$driver_registration_document->name ? ucwords($driver_registration_document->name)  : ''}} 
                @if(strtolower($driver_registration_document->file_type) == 'pdf' && (!empty($field_value)))
                <a href="{{ Storage::disk('s3')->url($field_value) }}" download target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>
                @endif
            </label>
            @if(strtolower($driver_registration_document->file_type) == 'text' || strtolower($driver_registration_document->file_type) == 'date')
            <input type="text" class="form-control" id="input_file_logo_{{$driver_registration_document->id}}" name="{{$driver_registration_document->name}}" placeholder="Enter Text" value="{{ $field_value }}" {{ (!empty($driver_registration_document->is_required))?'required':''}}>
            @else
            @if(strtolower($driver_registration_document->file_type) == 'image')
            <a href="{{ (!empty($field_value)) ? $imgproxyurl.Storage::disk('s3')->url($field_value) : '' }}" class="btn btn-blue" target="_blank">{{__("View")}}</a>
            <input type="file" data-plugins="dropify" name="{{$driver_registration_document->name}}" accept="image/*" data-default-file="{{ (!empty($field_value)) ? $imgproxyurl.Storage::disk('s3')->url($field_value) : '' }}" class="dropify" />
            @elseif(strtolower($driver_registration_document->file_type) == 'pdf')
            <input type="file" data-plugins="dropify" name="{{$driver_registration_document->name}}" accept=".pdf" class="dropify" />
            @endif
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
            @endif
        </div>
    </div>
    @endforeach
    @endif
</div>