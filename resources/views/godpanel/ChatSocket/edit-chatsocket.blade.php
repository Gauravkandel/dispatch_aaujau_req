<div class="modal-header border-bottom">
    <h4 class="modal-title">{{ __('Edit Socket') }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
@if(!empty($chatSocket))
{{ Form::open(array('id' => 'chatSocket_form', 'method' => 'post', 'enctype' => 'multipart/form-data', 'route' => array('chatsocket.upDateSocket', $chatSocket->id))) }}
    @csrf
    <div class="modal-body" >
        <div class="row">
            <div class="col-md-12">

                <div class="row">
                <div class="col-md-12">
                    <div class="form-group" id="nameInput">
                        {!! Form::label('title', __('Title'),['class' => 'control-label']) !!}
                        {!! Form::text('title', $chatSocket->title, ['class'=>'form-control', 'required'=>'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group" id="nameInput">
                        {!! Form::label('domain_url', __('Socket'),['class' => 'control-label']) !!}
                        {!! Form::text('domain_url', $chatSocket->domain_url, ['class'=>'form-control', 'required'=>'required']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
                                
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('status', __('Status'),['class' => 'control-label']) !!} 
                            <div class="mt-md-1">
                                <input type="checkbox" data-plugin="switchery" name="status" class="form-control status chk_box1" data-color="#43bee1" {{($chatSocket->status == 1) ? 'checked' : ''}}>
                            </div>
                        </div>
                    </div>
                   

                </div>
            </div>
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="submit" class="btn btn-info waves-effect waves-light submitAddSubscriptionForm">{{ __('Submit') }}</button>
    </div>
    
{{ Form::close() }}
@else
    <div class="modal-body" >
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger">
                    <ul class="m-0">
                        Something went wrong.
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endif