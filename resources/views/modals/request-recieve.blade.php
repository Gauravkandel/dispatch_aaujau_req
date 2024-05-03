<div id="request-receive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Request Receive</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body px-3 py-0">
                <ul class="nav nav-tabs nav-bordered nav-justified">
                    <li class="nav-item">
                        <a href="#home-b2" data-toggle="tab" aria-expanded="false" class="nav-link">
                            SMS
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#profile-b2" data-toggle="tab" aria-expanded="true" class="nav-link active">
                            Email
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#messages-b2" data-toggle="tab" aria-expanded="false" class="nav-link">
                            Webhook
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" id="home-b2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="field-3" class="control-label">TAG</label>
                                    <select class="selectpicker" data-style="btn-light">
                                        <option>Insert a Tag</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="field-3" class="control-label">MESSAGE</label>
                                    <textarea class="form-control" id="example-textarea" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane active" id="profile-b2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="field-3" class="control-label">TAG</label>
                                    <select class="selectpicker" data-style="btn-light">
                                        <option>Insert a Tag</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="field-3" class="control-label">MESSAGE</label>
                                    <textarea class="form-control" id="example-textarea" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="messages-b2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="field-3" class="control-label">TAG</label>
                                    <select class="selectpicker" data-style="btn-light">
                                        <option>Insert a Tag</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="field-3" class="control-label">MESSAGE</label>
                                    <textarea class="form-control" id="example-textarea" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-info waves-effect waves-light">Update</button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->