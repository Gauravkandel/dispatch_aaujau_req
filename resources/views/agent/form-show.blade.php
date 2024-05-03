@php
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp
<div class="row mb-2">
    <div class="col-md-4">
        <div class="form-group" id="profile_pictureInputEdit">
        <a href="{{Storage::disk('s3')->url($agent->profile_picture)}}" target="_blank"><img src="{{isset($agent->profile_picture) ? $imgproxyurl.Storage::disk('s3')->url($agent->profile_picture) : Phumbor::url(URL::to('/asset/images/no-image.png')) }}" style="width:240px;height:120px;"></a>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
        <p class="text-muted text-center mt-2 mb-0">{{__('Profile Pic')}}</p>
    </div>
    <div class="offset-md-2 col_md-6">
        <span>{{__('Live OTP')}}</span>
        <h4>{{isset($otp)?$otp: __('View OTP after Logging in the '.getAgentNomenclature().' App') }}</h4>
    </div>

</div>
<span class="show_all_error invalid-feedback"></span>
<div class="row">
    <div class="col-md-6">
        <div class="form-group" id="nameInputEdit">
            <label for="name" class="control-label">{{__('NAME')}}</label>
            <input type="text" class="form-control" id="name" placeholder="{{__('John Doe')}}" name="name" value="{{ old('name', $agent->name ?? '') }}" readonly>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="phone_numberInputEdit">
            <label for="phone_number" class="control-label">{{__('CONTACT NUMBER')}}</label>
            <div class="input-group">

                <input type="tel" name="phone_number" class="form-control xyz" id="phone" placeholder="{{__('Enter mobile number')}}" value="{{ $agent->phone_number }}" readonly>
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
            <label for="type" class="control-label">{{__('TYPE')}}</label>
            <select class="form-control" data-style="btn-light" name="type" id="type" disabled>
                <option value="Employee" @if ($agent->type == 'Employee') selected @endif
                    >{{__('Employee')}}</option>
                <option value="Freelancer" @if ($agent->type == 'Freelancer') selected @endif
                    >{{__('Freelancer')}}</option>

            </select>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="team_idInputEdit">
            <label for="team_id" class="control-label">{{__('ASSIGN TEAM')}}</label>
            <select class="form-control" data-style="btn-light" name="team_id" id="team_id" disabled>
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
            <p class="text-muted mt-3 mb-2">{{__('TRANSPORT ICON')}}</p>
            <div class="radio radio-blue form-check-inline click1 cursors">
                <input type="radio" id="onfoot" value="1" act="edit" name="vehicle_type_id" readonly="readonly" @if ($agent->vehicle_type_id == '1') checked @else disabled='disabled'
                @endif >
                <img id="foot_edit" src="{{ $agent->vehicle_type_id == '1' ? asset('assets/icons/walk_blue.png') : asset('assets/icons/walk.png') }}">
            </div>
            <div class="radio radio-primery form-check-inline click1 cursors">
                <input type="radio" id="bycycle" value="2" name="vehicle_type_id" act="edit" readonly="readonly" @if ($agent->vehicle_type_id == '2')
                checked @else disabled='disabled' @endif >
                <img id="cycle_edit" src="{{ $agent->vehicle_type_id == '2' ? asset('assets/icons/cycle_blue.png') : asset('assets/icons/cycle.png') }}">
            </div>
            <div class="radio radio-info form-check-inline click1 cursors">
                <input type="radio" id="motorbike" value="3" name="vehicle_type_id" act="edit" readonly="readonly" @if ($agent->vehicle_type_id == '3') checked @else disabled='disabled' @endif>
                <img id="bike_edit" src="{{ $agent->vehicle_type_id == '3' ? asset('assets/icons/bike_blue.png') : asset('assets/icons/bike.png') }}">
            </div>
            <div class="radio radio-danger form-check-inline click1 cursors">
                <input type="radio" id="car" value="4" name="vehicle_type_id" act="edit" readonly="readonly" @if ($agent->vehicle_type_id == '4') checked @else disabled='disabled'
                @endif>
                <img id="cars_edit" src="{{ $agent->vehicle_type_id == '4' ? asset('assets/icons/car_blue.png') : asset('assets/icons/car.png') }}">
            </div>
            <div class="radio radio-warning form-check-inline click1 cursors">
                <input type="radio" id="truck" value="5" name="vehicle_type_id" act="edit" readonly="readonly" @if ($agent->vehicle_type_id == '5') checked @else disabled='disabled' @endif>
                <img id="trucks_edit" src="{{ $agent->vehicle_type_id == '5' ? asset('assets/icons/truck_blue.png') : asset('assets/icons/truck.png') }}">
            </div>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group" id="nameInputEdit">
            <label for="name" class="control-label">{{__('Tags')}}</label>
            <input type="text" class="form-control" id="tags" placeholder="" name="tags" value="{{isset($tagIds) ? implode(',', $tagIds) : ''}}" readonly>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
       
    </div>
</div>
{{-- <div class="row mt-3">
    <div class="col-md-6">
        <div class="form-group" id="make_modelInputEdit">
            <input type="hidden" id="agent_id" val_id="{{ $agent->id }}" url="{{route('agent.update', $agent->id)}}">
            <label for="make_model" class="control-label">{{__('TRANSPORT DETAILS')}}</label>
            <input type="text" class="form-control" id="make_model" placeholder="Year, Make, Model" name="make_model" value="{{ old('name', $agent->make_model ?? '') }}" readonly>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="make_modelInput1">
            <label for="make_model" class="control-label">{{__('UID')}}</label>
            <input type="text" class="form-control" id="uid" placeholder="897abd" name="uid" value="{{ $agent->uid}}" readonly>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group" id="plate_numberInputEdit">
            <label for="plate_number" class="control-label">{{__('LICENCE NUMBER')}}</label>
            <input type="text" class="form-control" id="plate_number" name="plate_number" placeholder="508.KLV" value="{{ old('name', $agent->plate_number ?? '') }}" readonly>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="colorInputEdit">
            <label for="color" class="control-label">{{__('COLOR')}}</label>
            <input type="text" class="form-control" id="color" name="color" placeholder="Color" value="{{ old('name', $agent->color ?? '') }}" readonly>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
</div> --}}
<div class="row">
    <label for="" class="control-label"></label>

    @foreach($agent_docs as $key=>$agent_doc)
    
    <div class="col-md-6">

        @if(strtolower($agent_doc->file_type) == 'text'  || strtolower($agent_doc->file_type) == 'date')
        <div class="form-group">
    
            <label for="" class="control-label">{{$agent_doc->label_name}}</label>

            <input type="text" class="form-control" id="" name="file" placeholder="{{__('Enter Text')}}" value="{{ old('name', $agent_doc->file_name ?? '') }}" readonly>
        </div>
        @elseif(strtolower($agent_doc->file_type) == 'pdf')
        <div class="form-group">

            <label for="" class="control-label">{{$agent_doc->label_name}}</label>
            <div class="file file--upload">
                <label for="">
                    <span class="update_pic pdf-icon">
                        <a href="{{Storage::disk('s3')->url($agent_doc->file_name)}}" target="_blank"><img src="{{URL::asset('/assets/images/pdf.png')}}"></a>
                    </span>
                </label>
                <div class="invalid-feedback" id=""><strong></strong></div>
            </div>
        </div>
        @elseif(strtolower($agent_doc->file_type) == 'image')
        <div class="form-group">

            <label for="" class="control-label">{{$agent_doc->label_name}}</label>
            <div class="file file--upload">

                <a href="{{Storage::disk('s3')->url($agent_doc->file_name)}}" target="_blank"><img src="{{isset($agent_doc->file_name) ? $imgproxyurl.Storage::disk('s3')->url($agent_doc->file_name) : Phumbor::url(URL::to('/asset/images/no-image.png')) }}" style="width:240px;height:120px;"></a>
                <!-- @if(strtolower($agent_doc->file_type) == 'image')
            <input id="" type="file" name="file" v accept="image/*" data-rel="">
            @elseif(strtolower($agent_doc->file_type) == 'pdf')
            <input id="" type="file" name="file" accept=".pdf" data-rel="">
            @endif -->
                <div class="invalid-feedback" id=""><strong></strong></div>
            </div>
        </div>
        @endif
    </div>
    @endforeach
</div>