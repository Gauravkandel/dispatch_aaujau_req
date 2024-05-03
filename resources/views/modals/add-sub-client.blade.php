<div id="add-sub-client-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Client</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="submit_sub_client" method="POST" enctype="multipart/form-data" action="{{route('subclient.store')}}">
                @csrf
                <div class="modal-body px-3 py-0">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="nameInput">
                                <label for="name" class="control-label">NAME</label>
                                <input type="text" class="form-control" id="name" placeholder="John Doe" name="name" required>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="phone_numberInput">
                                <label for="phone_number" class="control-label">Email</label>
                                <div class="input-group">
                                    <input type="email" name="email" class="form-control" id="email" placeholder="jondoe@gmail.com" required>
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="phone_numberInput">
                                <label for="phone_number" class="control-label">CONTACT NUMBER</label>
                                <div class="input-group">
                                    <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="Enter mobile number" maxlength="14" required>
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="typeInput">
                                {!! Form::label('title', 'Status',['class' => 'control-label']) !!}
                                <select name="status" id="" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">In-active</option>
                                </select>
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
</div>