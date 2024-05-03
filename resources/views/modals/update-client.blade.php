<div id="update-client-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Update {{getAgentNomenclature()}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="StoreClient" method="post" action="{{url('client', $data->id ?? '')}}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body px-3 py-0">
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <input type="file" data-plugins="dropify"
                                data-default-file="{{isset($data->logo) ? asset('clients/'.$data->logo.'') : ''}}"
                                name="logo" />
                            <p class="text-muted text-center mt-2 mb-0">Upload Logo</p>
                        </div>
                    </div>

                    <div class=" row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="control-label">NAME</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="John Doe"
                                    value="{{$data->name ?? '' }}">
                                @if($errors->update->has('name'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->update->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="control-label">EMAIL</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter email address" value="{{$data->email ?? '' }}">
                                @if($errors->update->has('email'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->update->first('email') }}</strong>
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
                                        placeholder="Enter mobile number" value="{{$data->phone_number ?? '' }}">
                                </div>
                                @if($errors->update->has('phone_number'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->update->first('phone_number') }}</strong>
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
                                    placeholder="Enter Path" value="{{$data->database_path ?? '' }}">
                                @if($errors->update->has('database_path'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->update->first('database_path') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="database_name" class="control-label">DATABASE NAME</label>
                                <input type="text" class="form-control" name="database_name" id="database_name"
                                    placeholder="Enter database name" value="{{$data->database_name ?? '' }}">
                                @if($errors->update->has('database_name'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->update->first('database_name') }}</strong>
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
                                    placeholder="Enter database username" value="{{$data->database_username ?? '' }}">
                                @if($errors->update->has('database_username'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->update->first('database_username') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="database_password" class="control-label">DATABASE PASSWORD</label>
                                <input type="text" class="form-control" name="database_password" id="database_password"
                                    placeholder="Enter database password" value="{{$data->database_password ?? '' }}">
                                @if($errors->update->has('database_password'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->update->first('database_password') }}</strong>
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
                                    placeholder="Enter company name" value="{{$data->company_name ?? '' }}">
                                @if($errors->update->has('company_name'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->update->first('company_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_address" class="control-label">COMPANY ADDRESS</label>
                                <input type="text" class="form-control" id="company_address" name="company_address"
                                    placeholder="Enter company address" value="{{$data->company_address ?? '' }}">
                                @if($errors->update->has('company_address'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->update->first('company_address') }}</strong>
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
                                    placeholder="Enter custom domain" value="{{$data->custom_domain ?? '' }}">
                                @if($errors->update->has('custom_domain'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->update->first('custom_domain') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-info waves-effect waves-light">Update</button>
                </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->