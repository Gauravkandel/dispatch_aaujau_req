<div id="pickup-delivery-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Create Pickup & Delivery Task</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body px-3 py-0">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <p class="text-muted mb-2">SELECT TASK TYPE</p>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card-box border mb-1 p-1 rounded">
                                        <div class="radio radio-blue form-check-inline">
                                            {{-- <input type="radio" id="pickup" value="pickup" name="radioInline" checked> --}}
                                            {{-- <label for="pickup">Pickup </label> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-box border mb-1 p-1 rounded">
                                        <div class="radio radio-blue form-check-inline">
                                            <input type="radio" id="delivery" value="delivery" name="radioInline">
                                            <label for="delivery">Delivery </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-box border mb-1 p-1 rounded">
                                        <div class="radio radio-blue form-check-inline">
                                            <input type="radio" id="pickup_delivery" value="pickup_delivery"
                                                name="radioInline">
                                            <label for="pickup_delivery">Pickup & Delivery </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h4 class="modal-title mb-2">Pickup details</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-1" class="control-label">PICKUP FROM</label>
                            <input type="text" class="form-control" id="field-1" placeholder="John Doe">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-2" class="control-label">CONTACT NUMBER</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">+91</span>
                                </div>
                                <input type="text" class="form-control" id="field-2" placeholder="Enter mobile number">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-3" class="control-label">ADDRESS</label>
                            <input type="text" class="form-control" id="field-3" placeholder="Enter Address">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-3" class="control-label">PICKUP BEFORE</label>
                            <input type="text" id="datetime-datepicker" class="form-control"
                                placeholder="Date and Time">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-3" class="control-label">NOTES</label>
                            <textarea class="form-control" id="example-textarea" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <h4 class="modal-title mb-2">Delivery details</h4>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-1" class="control-label">DELIVER TO</label>
                            <input type="text" class="form-control" id="field-1" placeholder="John Doe">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-2" class="control-label">CONTACT NUMBER</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">+91</span>
                                </div>
                                <input type="text" class="form-control" id="field-2" placeholder="Enter mobile number">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-3" class="control-label">ADDRESS</label>
                            <input type="text" class="form-control" id="field-3" placeholder="Enter Address">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-3" class="control-label">DELIVERY BEFORE</label>
                            <input type="text" id="datetime-datepicker" class="form-control"
                                placeholder="Date and Time">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-3" class="control-label">NOTES</label>
                            <textarea class="form-control" id="example-textarea" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <h4 class="modal-title mb-2">Other Details</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-3" class="control-label">QUANTITY</label>
                            <select class="selectpicker" data-style="btn-light">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <p class="text-muted mb-2">REQUIRMENTS</p>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card-box border mb-1 p-1 rounded">
                                        <div class="checkbox checkbox-blue form-check-inline">
                                            <input type="checkbox" id="onfoot" value="onfoot" name="radioInline"
                                                checked>
                                            <label for="onfoot"> Signature </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-box border mb-1 p-1 rounded">
                                        <div class="checkbox checkbox-blue form-check-inline">
                                            <input type="checkbox" id="bycycle" value="bycycle" name="radioInline">
                                            <label for="bycycle"> Photo </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-box border mb-1 p-1 rounded">
                                        <div class="checkbox checkbox-blue form-check-inline">
                                            <input type="checkbox" id="bycycle" value="bycycle" name="radioInline">
                                            <label for="bycycle"> Notes </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <p class="text-muted mb-2">ASSIGN {{strtoupper(getAgentNomenclature())}}</p>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="card-box border p-1 rounded">
                                        <div class="radio radio-blue form-check-inline">
                                            <input type="radio" id="onfoot" value="onfoot" name="radioInline" checked>
                                            <label for="onfoot"> Assign Automatically </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 offset-md-1">
                                    <div class="card-box border p-1 rounded">
                                        <div class="radio radio-blue form-check-inline">
                                            <input type="radio" id="bycycle" value="bycycle" name="radioInline">
                                            <label for="bycycle"> i'll assign later </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-info waves-effect waves-light">Create</button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->