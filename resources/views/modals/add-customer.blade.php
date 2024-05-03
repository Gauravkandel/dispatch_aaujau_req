<div id="add-customer-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Add Customer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="submitCustomer">
                @csrf
                <div class="modal-body px-3 py-0">
                   
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="nameInput">
                                {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
        
                            </div>
                        </div>
        
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="make_modelInput">
                                {!! Form::label('title', 'Email',['class' => 'control-label']) !!}
                                {!! Form::email('email', null, ['class' => 'form-control']) !!}
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="make_modelInput">
                                <div class="input-group">
                                    {!! Form::label('title', 'Phone Number',['class' => 'control-label']) !!}
                                    {!! Form::text('phone_number', null, ['class' => 'form-control phone_number']) !!}
                                    <input type="hidden" id="dialCode" name="dialCode" value="{{getCountryCode()}}">
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="addapp"> 
                           {!! Form::label('title', 'Address',['class' => 'control-label']) !!} 
                           <div class="row address">
                               <div class="col-md-4">
                                   <div class="form-group" id=""> 
                                       <input type="text" name="short_name[]" class="form-control" placeholder="Short Name">
                                       <span class="invalid-feedback" role="alert">
                                           <strong></strong>
                                       </span>
                                   </div>
                               </div>
                               <div class="col-md-4">
                                   <div class="form-group" id="">
                                       <input type="text" name="address[]" class="form-control" placeholder="Address">
                                       <span class="invalid-feedback" role="alert">
                                           <strong></strong>
                                       </span>
                                   </div>
                               </div>
                               <div class="col-md-4">
                                   <div class="form-group" id="">
                                       <input type="text" name="post_code[]" class="form-control" placeholder="Post Code">
                                       <span class="invalid-feedback" role="alert">
                                           <strong></strong>
                                       </span>
                                   </div>
                               </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-4">
           
                           </div>
                           <div class="col-md-8" id="adds">
                               <a href="#"  class="btn btn-success btn-rounded waves-effect waves-light" >Add More Address</a>
                           </div>
                       </div>
           
                       
           
           
           
                   </div>


                   
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-info waves-effect waves-light">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->