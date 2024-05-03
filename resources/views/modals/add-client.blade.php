<div id="add-client-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Add {{getAgentNomenclature()}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="StoreClient" method="post" action="{{url('client')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-3 py-0">
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <input type="file" data-plugins="dropify" name="logo" />
                            <p class="text-muted text-center mt-2 mb-0">Upload Logo</p>
                        </div>
                    </div>

                    <div class=" row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="control-label">NAME</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="John Doe">
                                @if($errors->add->has('name'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->add->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="control-label">EMAIL</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter email address">
                                @if($errors->add->has('email'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->add->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_number" class="control-label">CONTACT NUMBER</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">+91</span>
                                    </div>
                                    <input type="text" class="form-control" name="phone_number" id="phone_number"
                                        placeholder="Enter mobile number">
                                </div>
                                @if($errors->add->has('phone_number'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->add->first('phone_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="control-label">PASSWORD</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Enter password">
                                @if($errors->add->has('password'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->add->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="database_path" class="control-label">DATABASE PATH</label>
                                <input type="text" class="form-control" name="database_path" id="database_path"
                                    placeholder="Enter Path">
                                @if($errors->add->has('database_path'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->add->first('database_path') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="database_name" class="control-label">DATABASE NAME</label>
                                <input type="text" class="form-control" name="database_name" id="database_name"
                                    placeholder="Enter database name">
                                @if($errors->add->has('database_name'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->add->first('database_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="database_username" class="control-label">DATABASE USERNAME</label>
                                <input type="text" class="form-control" name="database_username" id="database_username"
                                    placeholder="Enter database username">
                                @if($errors->add->has('database_username'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->add->first('database_username') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="database_password" class="control-label">DATABASE PASSWORD</label>
                                <input type="text" class="form-control" name="database_password" id="database_password"
                                    placeholder="Enter database password">
                                @if($errors->add->has('database_password'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->add->first('database_password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_name" class="control-label">COMPANY NAME</label>
                                <input type="text" class="form-control" name="company_name" id="company_name"
                                    placeholder="Enter company name">
                                @if($errors->add->has('company_name'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->add->first('company_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_address" class="control-label">COMPANY ADDRESS</label>
                                <input type="text" class="form-control" id="company_address" name="company_address"
                                    placeholder="Enter company address">
                                @if($errors->add->has('company_address'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->add->first('company_address') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="custom_domain" class="control-label">CUSTOM DOMAIN</label>
                                <input type="text" class="form-control" name="custom_domain" id="custom_domain"
                                    placeholder="Enter custom domain">
                                @if($errors->add->has('custom_domain'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->add->first('custom_domain') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-info waves-effect waves-light">Add</button>
                </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->