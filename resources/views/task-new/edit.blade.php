<div class="row">
    <div class="col-md-12">
        <div class="form-group" id="nameInputEdit">
            {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
            {!! Form::text('name', $customer->name, ['class' => 'form-control']) !!}
            
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>

        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group" id="emailInputEdit">
            {!! Form::label('title', 'Email',['class' => 'control-label']) !!}
            {!! Form::email('email', $customer->email, ['class' => 'form-control']) !!}
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group" id="phone_numberInputEdit">
            {!! Form::label('title', 'Phone Number',['class' => 'control-label']) !!}
            {!! Form::text('phone_number', $customer->phone_number, ['class' => 'form-control']) !!}
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
        <input type="hidden" id="customer_id" val_id="{{ $customer->id }}" url="{{route('customer.update', $customer->id)}}">

    </div>
</div>
<div class="editApp"> <?php $i = 1; ?>
    {!! Form::label('title', 'Address',['class' => 'control-label']) !!} 
    @foreach($customer->location as $loc)

    <div class="row address addEditAddress" id="edit{{$i}}">
        <div class="col-md-4">
            <div class="form-group" id=""> 
                <input type="text" name="short_name[]" class="form-control" placeholder="Short Name" value="{{$loc->short_name}}">
                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group input-group" id="location">
                <input type="text" id="edit{{$i}}-input" name="address[]" class="form-control" placeholder="Address" value="{{$loc->address}}">
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
        <div class="col-md-3">
            <div class="form-group" id="">
                <input type="text" name="post_code[]" class="form-control" placeholder="Post Code" value="{{$loc->post_code}}">
                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
            </div>
        </div>
    </div>
    <?php $i++; ?>
    @endforeach
    <div id="editAddress-map-container" style="width:100%;height:400px; display: none;">
        <div style="width: 100%; height: 100%" id="address-map"></div>
    </div>
</div>

<div class="row">
    <div class="col-12 text-center" id="edit_add">
        <a href="#"  class="btn btn-success btn-rounded waves-effect waves-light editInput" >Add More Address</a>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        
    </div>
    <div class="col-md-7">
        
    </div>
</div>
