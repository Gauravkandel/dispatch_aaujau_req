<div class="row">

    <div class="col-lg-4 col-sm-6 mb-lg-0 mb-3">
        <div class="form-group" id="nameInputEdit">
            {!! Form::label('title', __('Name'),['class' => 'control-label']) !!}
            {!! Form::text('name', $customer->name, ['class' => 'form-control']) !!}

            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6 mb-lg-0 mb-3">
        <div class="form-group" id="emailInputEdit">
            {!! Form::label('title', __('Email'),['class' => 'control-label']) !!}
            {!! Form::email('email', $customer->email, ['class' => 'form-control']) !!}
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6 mb-lg-0 mb-3">
        <div class="form-group" id="phone_numberInputEdit">
            {!! Form::label('title', __('Phone Number'),['class' => 'control-label']) !!}<br/>
            <div class="input-group">
                {!! Form::text('phone_number', $customer->phone_number, ['class' => 'form-control phone_number', 'id'=>'phone_number']) !!}
                
            </div>
            <input type="hidden" id="dialCode" name="dialCode" value="{{$customer->dial_code}}">
            <input type="hidden" id="countryCode" name="countryCode" value="{{getCountryCode($customer->dial_code)}}">
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
        <input type="hidden" id="customer_id" val_id="{{ $customer->id }}" url="{{route('customer.update', $customer->id)}}">
    </div>


</div>


<div class="editApp"> <?php $i = 1; ?>
    {!! Form::label('title', __('Address'),['class' => 'control-label']) !!}
    @foreach($customer->location as $loc)

    <div class="row address addEditAddress addressrow{{$i}}" id="edit{{$i}}">
        <div class="col-lg-4 col-md-3 mb-lg-0 mb-3">
            <div class="form-group">
                <input type="text" id="add{{$i}}-flat" name="flat_no[]" value="{{$loc->flat_no}}" class="form-control" placeholder="{{__('House / Apartment/ Flat number')}}"">
                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
            </div>
        </div>
        <div class="col-lg-4 col-md-3 mb-lg-0 mb-3">
            <div class="form-group" id="">
                <input type="text" name="short_name[]" class="form-control" placeholder="{{__('Short Name')}}" value="{{$loc->short_name}}">
                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
            </div>
        </div>
        <div class="col-lg-4 col-md-3 mb-lg-0 mb-3">
            <div class="form-group input-group" id="location">
                <input type="text" id="edit{{$i}}-input" name="address[]" class="form-control" placeholder="{{__('Address')}}" value="{{$loc->address}}">
                <div class="input-group-append">
                    <button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="edit{{$i}}"> <i class="mdi mdi-map-marker-radius"></i></button>
                </div>
                <input type="hidden" name="latitude[]" id="edit{{$i}}-latitude" value="{{$loc->latitude}}" />
                <input type="hidden" name="longitude[]" id="edit{{$i}}-longitude" value="{{$loc->longitude}}" />
                <input type="hidden" name="location_id[]" value="{{$loc->id}}" />
                <span class="invalid-feedback" role="alert" id="location">
                    <strong></strong>
                </span>
            </div>
        </div>
        <div class="col-lg-4 col-md-3 mb-lg-0 mb-3">
            <div class="form-group">
                <input type="text" id="edit{{$i}}-email" name="address_email[]" class="form-control" placeholder="{{__('Email')}}" value="{{$loc->email}}">
                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
            </div>
        </div>
        <div class="col-lg-4 col-md-3 mb-lg-0 mb-3">
            <div class="form-group">
                <input type="text" id="edit{{$i}}-phone_number" name="address_phone_number[]" class="form-control" placeholder="{{__('Phone Number')}}" value="{{$loc->phone_number}}">
                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
            </div>
        </div>
        <div class="col-lg-4 col-md-3 mb-lg-0 mb-3">
            <div class="form-group delete_btn d-flex align-items-center" id="">
                <input type="text" id="edit{{$i}}-postcode" name="post_code[]" class="form-control" placeholder="{{__('Post Code')}}" value="{{$loc->post_code}}">
                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
                <button type="button" class="btn btn-primary-outline action-icon" onclick="deleteAddress({{$loc->id}},{{$i}})"> <i class="mdi mdi-delete"></i></button>
            </div>
        </div>
        {{-- <div class="col-sm-6 mb-lg-0 mb-3">
            <div class="form-group">
                <label for="">Due After</label>
                <input type="time" id="edit{{$i}}-due_after" name="due_after[]" class="form-control" placeholder="Due After" value="{{$loc->due_after}}">
                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
            </div>
        </div>
        <div class="col-sm-6 mb-lg-0 mb-3">
            <div class="form-group">
                <label for="">Due Before</label>
                <input type="time" id="edit{{$i}}-due_before" name="due_before[]" class="form-control" placeholder="Due Before" value="{{$loc->due_before}}">
                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
            </div>
        </div> --}}

    </div>
    <?php $i++; ?>
    @endforeach
    <div id="editAddress-map-container" style="width:100%;height:400px; display: none;">
        <div style="width: 100%; height: 100%" id="address-map"></div>
    </div>
</div>

<div class="row">
    <div class="col-12 text-center" id="edit_add">
        <a href="#"  class="btn btn-success btn-rounded waves-effect waves-light editInput" >{{__('Add More Address')}}</a>
    </div>
</div>
