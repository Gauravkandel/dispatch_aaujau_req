@extends('layouts.god-vertical', ['title' => 'Options'])

@section('css')
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">
                        @if (isset($client)) Update
                        @else
                            Create @endif Client @if (isset($client)) :
                                {{ $client->name ?? '' }} @endif
                    </h4>
                </div>
            </div>
        </div>

        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-sm-12">
                            <div class="text-sm-left">
                                @if (\Session::has('error'))
                                    <div class="alert alert-danger">
                                        <span>{!! \Session::get('error') !!}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if (isset($client))
                            <form id="UpdateClient" method="post" action="{{ route('client.update', $client->id) }}"
                                enctype="multipart/form-data">
                                @method('PUT')
                            @else
                                <form id="StoreClient" method="post" action="{{ route('client.store') }}"
                                    enctype="multipart/form-data">
                        @endif
                        @csrf
                        @if (empty($client))

                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <input type="file" data-plugins="dropify" name="logo"
                                        data-default-file="{{ isset($client->logo) ? Storage::disk('s3')->url($client->logo) : '' }}" />
                                    <p class="text-muted text-center mt-2 mb-0">Upload Logo</p>
                                </div>
                            </div>
                            <div class=" row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="control-label">NAME</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{ old('name', $client->name ?? '') }}" placeholder="John Doe" required>
                                        @if ($errors->has('name'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="control-label">EMAIL</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email', $client->email ?? '') }}"
                                            placeholder="Enter email address" required>
                                        @if ($errors->has('email'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
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
                                                value="{{ old('phone_number', $client->phone_number ?? '') }}"
                                                placeholder="Enter mobile number" required>
                                        </div>
                                        @if ($errors->has('phone_number'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('phone_number') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="control-label">PASSWORD</label>
                                        @if (isset($client))
                                            <input type="text" class="form-control" id="password" name="password"
                                                value="{{ old('password', isset($client->confirm_password) ? Crypt::decryptString($client->confirm_password) : '********') }}"
                                                placeholder="Enter password">
                                        @else
                                            <input type="password" class="form-control" id="password" name="password"
                                                value="" placeholder="Enter password" required>
                                        @endif
                                        @if ($errors->has('password'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="database_name" class="control-label">DATABASE NAME</label>

                                        @if (isset($client))
                                            <input type="text" class="form-control" name="database_name"
                                                id="database_name"
                                                value="{{ old('database_name', $client->database_name ?? '') }}"
                                                placeholder="Please Enter One String Example:-'mydatabase' " readonly>
                                        @else
                                            <input type="text" class="form-control" name="database_name"
                                                id="database_name"
                                                value="{{ old('database_name', $client->database_name ?? '') }}"
                                                placeholder="Please Enter One String Example:-'mydatabase'" required>
                                        @endif
                                        @if ($errors->has('database_name'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('database_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_name" class="control-label">COMPANY NAME</label>
                                        <input type="text" class="form-control" name="company_name" id="company_name"
                                            value="{{ old('company_name', $client->company_name ?? '') }}"
                                            placeholder="Enter company name">
                                        @if ($errors->has('company_name'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('company_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="languages">Socket Url </label>
                                        <select class="form-control" id="socket_url" name="socket_url">
                                            <option class="" value="" data-id="">Disable chat</option>
                                            @if (isset($ChatSocketUrl))
                                                @foreach ($ChatSocketUrl as $socketUrl)
                                                    <option
                                                        @if (isset($client)) @if ($client->socket_url == $socketUrl->domain_url) selected="selected" @endif
                                                        @endif class=""
                                                        value="{{ $socketUrl->domain_url }}"
                                                        data-id="{{ $socketUrl->id }}">{{ $socketUrl->domain_url }}
                                                    </option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            @if (empty($client))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_address" class="control-label">COMPANY ADDRESS</label>
                                        <input type="text" class="form-control" id="company_address"
                                            name="company_address"
                                            value="{{ old('company_address', $client->company_address ?? '') }}"
                                            placeholder="Enter company address">
                                        @if ($errors->has('company_address'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('company_address') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="custom_domain" class="control-label">CUSTOM DOMAIN (*Make sure you already
                                        pointed to our ip ({{ \env('IP') }}) from your domain.)</label>

                                    <div class="domain-outer d-flex align-items-center">
                                        <div class="domain_name">https://</div>
                                        <input type="text" name="custom_domain" id="custom_domain"
                                            placeholder="dummy.com" class="form-control"
                                            value="{{ old('custom_domain', $client->custom_domain ?? '') }}">
                                    </div>


                                    @if ($errors->has('custom_domain'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('custom_domain') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sub_domain" class="control-label">SUB DOMAIN</label>
                                    <div class="domain-outer d-flex align-items-center">
                                        <div class="domain_name">https://</div>
                                        <input type="text" name="sub_domain" id="sub_domain"
                                            placeholder="Enter Sub domain" class="form-control"
                                            value="{{ old('sub_domain', $client->sub_domain ?? '') }}">
                                        <div class="domain_name">{{ \env('SUBDOMAIN') }}</div>
                                    </div>



                                    @if ($errors->has('sub_domain'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('sub_domain') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info waves-effect waves-light">Submit</button>
                            </div>

                        </div>
                        </form>


                        @if (isset($client))
                            <!-- update Socket URL  -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>{{ __('Socket URL') }}</h3>
                                    <form method="post" action="{{ route('client.socketUpdateAction', $client->id) }}"
                                        enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="socket_url">Socket Url </label>
                                                    <select class="form-control" id="socket_url" name="socket_url">
                                                        <option class="" value="" data-id="">Disable chat
                                                        </option>
                                                        @if (isset($ChatSocketUrl))
                                                            @foreach ($ChatSocketUrl as $socketUrl)
                                                                <option
                                                                    @if (isset($client)) @if ($client->socket_url == $socketUrl->domain_url) selected="selected" @endif
                                                                    @endif class=""
                                                                    value="{{ $socketUrl->domain_url }}"
                                                                    data-id="{{ $socketUrl->id }}">{{ $socketUrl->domain_url }}
                                                                </option>
                                                            @endforeach
                                                        @endif

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="row">
                                                    <button type="submit"
                                                        class="btn btn-info waves-effect waves-light">{{ __('Submit') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <!-- Migrate Client  -->
                            <div class="row">
                                <div class="col-12">
                                    <h3>{{ __('Migrate Client') }}</h3>
                                    <form method="post" action="{{ route('client.exportdb', $client->database_name) }}"
                                        enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="languages">Stage </label>
                                                    <select class="form-control" id="dump_into" name="dump_into">
                                                        <option value="DEV">DEV</option>
                                                        <option value="STAG">STAG</option>
                                                        <option value="PROD">PROD</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="row">
                                                    <button type="submit"
                                                        class="btn btn-info waves-effect waves-light">{{ __('Submit') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
    <!-- Page js-->
    <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
    <script src="{{ asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var update_status_chat = "{{ route('client.socketUpdateAction', ':id') }}";

            var loc = "{{ route('client.index') }}";
            $('#side-menu').find('a').each(function() {
                if ($(this).attr('href') == loc) {
                    $(this).toggleClass('active');
                    $(this).parent().toggleClass('menuitem-active');
                }
            });
            var elems = Array.prototype.slice.call(document.querySelectorAll('.chk_box'));
            elems.forEach(function(html) {
                var switchery = new Switchery(html);
            });
        });
    </script>
@endsection
