<div id="create-appoinment-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Create Appoinment Task</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body px-3 py-0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-1" class="control-label">NAME</label>
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
                            <label for="field-3" class="control-label">START TIME</label>
                            <input type="text" id="datetime-datepicker" class="form-control"
                                placeholder="Date and Time">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-3" class="control-label">END TIME</label>
                            <input type="text" id="datetime-datepicker" class="form-control"
                                placeholder="Date and Time">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-3" class="control-label">DESCRIPTION</label>
                            <textarea class="form-control" id="example-textarea" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <p class="text-muted mt-3 mb-2">ASSIGN {{strtoupper(getAgentNomenclature())}}</p>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="card-box border p-1 rounded">
                                        <div class="radio radio-primary form-check-inline">
                                            <input type="radio" id="onfoot" value="onfoot" name="radioInline" checked>
                                            <label for="onfoot"> Assign Automatically </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 offset-md-1">
                                    <div class="card-box border p-1 rounded">
                                        <div class="radio radio-success form-check-inline">
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