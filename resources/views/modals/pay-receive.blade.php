<div id="pay-receive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">{{__("Pay")}}/{{__("Receive Money")}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="submitpayreceive" enctype="">
                @csrf
                <div class="row pt-2">
                    <div class="login-form setmodal">
                        <ul class="list-inline">
                        <li class="d-inline-block mr-2">
                            <input type="radio" id="teacher" name="payment_type"  value="1" checked>
                            <label for="teacher"><span class="showspan">{{__("Pay")}}</span></label>
                            </li>
                        <li class="d-inline-block mr-2">
                            <input type="radio" id="student"  name="payment_type" value="2">
                            <label for="student"><span class="showspan">{{__("Receive")}}</span></label>
                        </li>
                        
                        </ul>
                    </div>
                </div>
                
                <div class="modal-body px-3 py-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">{{__("Select")}} {{getAgentNomenclature()}}</label>
                                <select name="driver_id" id="selectAgent" class="selectpicker" required>
                                    <option hidden="true"></option>
                                    @foreach ($agents as $item)
                                        @php
                                            $id = $item->id;
                                            $length = strlen($item->id);
                                            if($length < 4){
                                                $id = str_pad($id, 4, '0', STR_PAD_LEFT);
                                            }
                                        @endphp
                                        <option value="{{$item->id}}">{{ $id . ' - ' . $item->name}}</option> 
                                    @endforeach
                                
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-2" class="control-label">{{__("Amount")}}</label>
                                <input name="amount" type="text" class="form-control" id="field-2"
                                    placeholder="3000" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card-box dispaly-cards">
                            {!! Form::label('title', __('Order Earning'),['class' => 'control-label']) !!} <br>
                            <span id="order_earning"></span>
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="card-box dispaly-cards">
                            {!! Form::label('title', __('Cash Collected'),['class' => 'control-label']) !!} <br>
                            <span id="cash_collected"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-box dispaly-cards">
                            {!! Form::label('title', __('Final Balance'),['class' => 'control-label']) !!} <br>
                            <span id="final_balance"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <span class="show_all_error invalid-feedback"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light">{{__("Add")}}</button>
                </div>
            </form>    
        </div>
    </div>
</div><!-- /.modal -->