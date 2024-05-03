<div id="task-accounting-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="header-title mb-0">{{__('Pay Details')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="add_customer" action="" method="">
                @csrf
                <div class="modal-body px-3 py-0">
                    
                    <div class="row">

                        <div class="col-md-12">
                            <div class="">

                                <div class="row">
                                    
                                        <div class="col-md-3">
                                            <div class="form-group pay-detail-box copyin1" id="">
                                                {!! Form::label('title', __('Base Price'),['class' => 'control-label']) !!} <br>
                                                <span id="base_price"></span>
                                            </div>
                                        </div> 


                                        <div class="col-md-3">
                                            <div class="form-group pay-detail-box copyin1" id="">
                                                {!! Form::label('title', __('Duration Price'),['class' => 'control-label']) !!} <br>
                                                <span id="duration_price"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group pay-detail-box copyin1" id="">
                                                {!! Form::label('title', __('Distance Price'),['class' => 'control-label']) !!} <br>
                                                <span id="distance_fee"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group pay-detail-box copyin1" id="">
                                                <label class="control-label">{{ __(getAgentNomenclature()) }} Type</label> <br>
                                                
                                                <span id="driver_type"></span>
                                            </div>
                                        </div>
                                       
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            {!! Form::label('title', __('Base Distance'),['class' => 'control-label']) !!} <br>
                                            <span id="base_distance"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            {!! Form::label('title', __('Actual Distance'),['class' => 'control-label']) !!} <br>
                                            <span id="actual_distance"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            {!! Form::label('title', __('Billing Distance'),['class' => 'control-label']) !!} <br>
                                            <span id="billing_distance"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            {!! Form::label('title', __('Distance Cost'),['class' => 'control-label']) !!} <br>
                                            <span id="distance_cost"></span>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            {!! Form::label('title', __('Base Duration'),['class' => 'control-label']) !!} <br>
                                            <span id="base_duration"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            {!! Form::label('title', __('Actual Duration'),['class' => 'control-label']) !!} <br>
                                            <span id="actual_duration"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            {!! Form::label('title', __('Billing Duration'),['class' => 'control-label']) !!} <br>
                                            <span id="billing_duration"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            {!! Form::label('title', __('Duration Cost'),['class' => 'control-label']) !!} <br>
                                            <span id="duration_cost"></span>
                                        </div>
                                    </div>
                                </div>

                                

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            {!! Form::label('title', __('Order Cost'),['class' => 'control-label']) !!}
                                            <h5 id="order_cost"></h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            {!! Form::label('title', __(getAgentNomenclature().' Cost'),['class' => 'control-label']) !!}
                                            <h5 id="driver_cost"></h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            {!! Form::label('title', __('Employee Commission %'),['class' => 'control-label']) !!} <br>
                                            <span id="agent_commission_percentage"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id=""> 
                                            {!! Form::label('title', __('Employee Commission Fixed'),['class' => 'control-label']) !!} <br>
                                            <span id="agent_commission_fixed"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            {!! Form::label('title', __('Freelancer Commission%'),['class' => 'control-label']) !!} <br>
                                            <span id="freelancer_commission_percentage"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group pay-detail-box copyin1" id="">
                                            {!! Form::label('title', __('Freelancer Commission Fixed'),['class' => 'control-label']) !!} <br>
                                            <span id="freelancer_commission_fixed"></span>
                                        </div>
                                    </div>
                                </div>
                                
 
                            </div>
                           
                        </div>
                    </div>
                </div>
                {{-- <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light">{{__('Submit')}}</button>
                </div> --}}
            </form>
        </div>
    </div>
</div>

