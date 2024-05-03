<div id="add-manager-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Add Manager</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="submitManager" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-3 py-0">
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <div class="form-group" id="profile_pictureInput">
                                <input type="file" data-plugins="dropify" name="profile_picture" />
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                            <p class="text-muted text-center mt-2 mb-0">Profile Pic</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group" id="nameInput">
                                <label for="name" class="control-label">NAME</label>
                                <input type="text" class="form-control" id="name" placeholder="John Doe" name="name">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group" id="emailInput">
                                <label for="email" class="control-label">EMAIL</label>
                                <input type="email" class="form-control" id="email" placeholder="abc@example.com" name="email">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group" id="phone_numberInput">
                                <label for="phone_number" class="control-label">CONTACT NUMBER</label>
                                <div class="input-group">
                                    <input type="text" name="phone_number" class="form-control" id="phone_number">
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="can_create_taskInput">
                                <p class="text-muted mt-3 mb-2">Permission to create task</p>
                                <div class="radio radio-primary form-check-inline">
                                    <input type="radio" id="yes1" value="1" name="can_create_task" checked>
                                    <label for="yes1"> Yes </label>
                                </div>
                                <div class="radio radio-success form-check-inline">
                                    <input type="radio" id="no1" value="0" name="can_create_task">
                                    <label for="no1"> No </label>
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="can_edit_task_createdInput">
                                <p class="text-muted mt-3 mb-2">Permission to edit own tasks</p>
                                <div class="radio radio-primary form-check-inline">
                                    <input type="radio" id="yes2" value="1" name="can_edit_task_created" checked>
                                    <label for="yes2"> Yes </label>
                                </div>
                                <div class="radio radio-success form-check-inline">
                                    <input type="radio" id="no2" value="0" name="can_edit_task_created">
                                    <label for="no2"> No </label>
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="can_edit_allInput">
                                <p class="text-muted mt-3 mb-2">Permission to Edit any tasks</p>
                                <div class="radio radio-primary form-check-inline">
                                    <input type="radio" id="yes3" value="1" name="can_edit_all" checked>
                                    <label for="yes3"> Yes </label>
                                </div>
                                <div class="radio radio-success form-check-inline">
                                    <input type="radio" id="no3" value="0" name="can_edit_all">
                                    <label for="no3"> No </label>
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="can_manage_unassigned_tasksInput">
                                <p class="text-muted mt-3 mb-2">Permission to Edit any tasks</p>
                                <div class="radio radio-primary form-check-inline">
                                    <input type="radio" id="yes4" value="1" name="can_manage_unassigned_tasks" checked>
                                    <label for="yes4"> Yes </label>
                                </div>
                                <div class="radio radio-success form-check-inline">
                                    <input type="radio" id="no4" value="0" name="can_manage_unassigned_tasks">
                                    <label for="no4"> No </label>
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="can_edit_auto_allocationInput">
                                <p class="text-muted mt-3 mb-2">Permission to Edit any tasks</p>
                                <div class="radio radio-primary form-check-inline">
                                    <input type="radio" id="yes5" value="1" name="can_edit_auto_allocation" checked>
                                    <label for="yes5"> Yes </label>
                                </div>
                                <div class="radio radio-success form-check-inline">
                                    <input type="radio" id="no5" value="0" name="can_edit_auto_allocation">
                                    <label for="no5"> No </label>
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light">Add</button>
                </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->