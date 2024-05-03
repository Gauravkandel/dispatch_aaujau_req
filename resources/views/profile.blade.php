@extends('layouts.vertical', ['title' => 'Profile'])

@section('css')
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.18/css/intlTelInput.css'>
    <style>
        .input-group {
            position: relative;
            display: block;
            flex-wrap: nowrap;
            align-items: end;
            width: 100%;
        }

        .iti {
            width: 100%;
        }

        .choose-btn {
            display: block;
        }

        .choose-btn .icon {
            margin-right: 10px;
            line-height: 35px;
            z-index: 99;
        }

        .g-btn .icon.alAppleIcon svg path {
            fill: #333;
        }

        .choose-btn {
            margin-bottom: 15px;
            background: linear-gradient(90deg, #73BE42 0%, rgba(244, 221, 98, 0.75) 54.17%, #248341 100%);
            box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            width: 100%;
            display: flex;
            color: #fff;
            padding: 5px 0;
            float: left;
            position: relative;
            justify-content: center;
            align-items: center;
        }

        .choose-btn span {
            position: relative;
            z-index: 1
        }

        .g-btn::after {
            content: "";
            position: absolute;
            height: 96%;
            width: 99%;
            background: #fff;
            left: 1px;
            top: 1px;
            border-radius: 9px;
        }

        .choose-btn .text {
            z-index: 99;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
            line-height: 1;
        }

        .g-btn .text {
            color: #777;
        }

        .choose-btn .text strong {
            font-size: 16px;
            display: block;
            font-weight: 600;
            letter-spacing: 0;
            text-transform: capitalize;
        }

        .choose-btn:hover {
            text-decoration: none;
        }

        .g-btn .text strong {
            color: #000;
        }
    </style>
@endsection
@php
    $imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
    $is_readonly = '';
    $is_disabled = '';
    if (Auth::user()->is_superadmin != 1) {
        $is_readonly = 'readonly';
        $is_disabled = 'disabled';
    }
@endphp
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ __('Profile') }}</h4>
                </div>
            </div>
        </div>

        <div class="text-sm-left">
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <span>{!! \Session::get('success') !!}</span>
                </div>
            @endif
        </div>

        <div class="text-sm-left">
            @if (\Session::has('error'))
                <div class="alert alert-error">
                    <span>{!! \Session::get('error') !!}</span>
                </div>
            @endif
        </div>
        <!-- end page title -->
        <!-- <div class="row">
                                    <div class="col-xl-11 col-md-offset-1">
                                        <div class="card-box" id="test">
                                            <h4 class="header-title">Organization details</h4>
                                            <p class="sub-header">
                                                View and edit your organization's profile details.
                                            </p>

                                            <div class="row mb-2">
                                                <div class="col-md-6">
                                                    <img src="{{ asset('assets/images/users/user-3.jpg') }}"
                                                        class="rounded-circle img-thumbnail avatar-xl" alt="profile-image">
                                                </div>
                                            </div>
                                            <form id="submitForm">
                                                @csrf
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3" id="nameInput">
                                                            <label for="name">NAME</label>
                                                            <input type="text" name="name" id="name" placeholder="ABC Deliveries"
                                                                class="form-control">
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong></strong>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3" id="emailInput">
                                                            <label for="email">EMAIL</label>
                                                            <input type="text" name="email" id="email" placeholder="abc@gmail.com"
                                                                class="form-control">
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong></strong>
                                                            </span>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3" id="countryInput">
                                                            <label for="country">COUNTRY</label>
                                                            <select class="form-control" id="country">
                                                                <option value="india">India</option>
                                                                <option value="india">Australia</option>
                                                                <option value="india">Dubai</option>
                                                            </select>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong></strong>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3" id="timezoneInput">
                                                            <label for="timezone">TIMEZONE</label>
                                                            <select class="form-control" id="timezone">
                                                                <option value="Asia/Calcutta">(GMT+5:30) Asia/Calcutta</option>

                                                            </select>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong></strong>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-2">
                                                        <div class="form-group mb-0 text-center">
                                                            <button class="btn btn-primary btn-block" type="submit"> Update </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="show_all_error invalid-feedback"></span>
                                            </form>
                                        </div>
                                    </div>
                                </div> -->

        <div class="row">
            <form id="UpdateClient" method="post" action="{{ route('profile.update', Auth::user()->code) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="col-md-8" style="width: 100vw">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">{{ __('Organization details') }}</h4>
                            <p class="sub-header">
                                {{ __("View and edit your organization's profile details.") }}
                            </p>
                            <div class="row mb-3">
                                <div class="col-md-7">
                                    <div class="row d-flex align-items-center mb-3">
                                        <div class="col-md-4 upload_box">
                                            <input type="file" data-plugins="dropify" name="logo"
                                                data-default-file="{{ isset(Auth::user()->logo) ? Storage::disk('s3')->url(Auth::user()->logo) : '' }}" />
                                            <p class="text-muted text-center mt-2 mb-0">{{ __('Upload Light Logo') }} </p>
                                        </div>
                                        <div class="col-md-4 upload_box">
                                            <input type="file" data-plugins="dropify" name="dark_logo"
                                                data-default-file="{{ isset(Auth::user()->dark_logo) ? Storage::disk('s3')->url(Auth::user()->dark_logo) : '' }}" />
                                            <p class="text-muted text-center mt-2 mb-0">{{ __('Upload Dark Logo') }} </p>
                                        </div>
                                        <div class="col-md-4 upload_box">
                                            <div id="favicon_container">
                                                <input type="file" class="dropify" data-plugins="dropify"
                                                    name="favicon"
                                                    data-default-file="{{ isset($preference->favicon) ? Storage::disk('s3')->url($preference->favicon) : '' }}" />
                                                <p class="text-muted text-center mt-2 mb-0">{{ __('Upload favicon') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <a class="choose-btn g-btn"
                                                href="https://apps.apple.com/us/app/royo-dispatcher/id1546990347"
                                                target="_blank">
                                                <span class="icon alAppleIcon"><svg width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip0_193_116)">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M13.0724 1.85586C13.9617 0.814184 15.4496 0.0506692 16.69 0.000151195C16.7918 -0.00386192 16.8822 0.0724659 16.8936 0.175233C17.0374 1.48666 16.5619 2.95073 15.6215 4.09156C14.728 5.17195 13.4196 5.84293 12.2068 5.84293C12.1217 5.84285 12.0362 5.83955 11.9525 5.83302C11.8599 5.82586 11.785 5.7548 11.7728 5.66273C11.5782 4.19126 12.3166 2.73143 13.0724 1.85586ZM4.71012 20.9263C2.4664 17.6866 1.17654 12.3434 3.20552 8.82248C4.27254 6.96668 6.20796 5.79486 8.25622 5.76472C8.2766 5.76432 8.2973 5.76409 8.31831 5.76409C9.19923 5.76409 10.0314 6.09426 10.7655 6.38565L10.7661 6.3859C11.3149 6.6036 11.7888 6.7916 12.154 6.7916C12.4791 6.7916 12.9505 6.60574 13.4963 6.39053C14.287 6.07876 15.2711 5.69075 16.2985 5.69075C16.4301 5.69075 16.5611 5.69712 16.688 5.70979C17.5627 5.74741 19.7353 6.06004 21.1528 8.13442C21.1827 8.17817 21.1937 8.23231 21.1832 8.28432C21.1726 8.33642 21.1415 8.38198 21.0969 8.4107L21.0777 8.42266C20.6659 8.67745 18.6123 10.0832 18.6386 12.7379C18.667 16.0053 21.3694 17.2041 21.6774 17.3301L21.6918 17.3363C21.7832 17.3782 21.8282 17.4822 21.7963 17.5775L21.7895 17.5988C21.6222 18.135 21.1261 19.5381 20.1372 20.9832L20.1371 20.9835C19.1895 22.3675 18.1154 23.9362 16.3661 23.9688C15.5507 23.9841 14.9981 23.745 14.4622 23.5132L14.4577 23.5112L14.4574 23.5111C13.9123 23.2752 13.3486 23.0313 12.4654 23.0313C11.5369 23.0313 10.9449 23.2835 10.3726 23.5274L10.3719 23.5277C9.8623 23.7447 9.33514 23.9692 8.60576 23.9983C8.5757 23.9994 8.54635 24 8.51708 24C6.964 24 5.8301 22.5461 4.71012 20.9263Z"
                                                                fill="black" />
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_193_116">
                                                                <rect width="24" height="24" fill="white" />
                                                            </clipPath>
                                                        </defs>
                                                    </svg></span>
                                                <span class="text"> Available on <strong>App Store</strong></span>
                                                <!-- <img class="w-100" src="{{ asset('assets/images/iosstore.png') }}" alt="image" >  -->
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <a class="choose-btn g-btn"
                                                href="https://play.google.com/store/apps/details?id=com.codebew.deliveryagent&hl=en_US&gl=US"
                                                target="_blank">
                                                <span class="icon"><svg width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M12.7496 10.875L15.8996 7.5L3.67461 0.9C3.59961 0.825 3.44961 0.825 3.22461 0.75L12.7496 10.875Z"
                                                            fill="#00F076" />
                                                        <path
                                                            d="M17.2496 15.75L21.6746 13.35C22.1996 13.05 22.4996 12.6 22.4996 12C22.4996 11.4 22.1996 10.875 21.6746 10.65L17.2496 8.25L13.7246 12L17.2496 15.75Z"
                                                            fill="#FFC900" />
                                                        <path
                                                            d="M1.8 1.42499C1.575 1.64999 1.5 1.94999 1.5 2.24999V21.75C1.5 22.05 1.575 22.35 1.8 22.65L11.7 12L1.8 1.42499Z"
                                                            fill="#00D6FF" />
                                                        <path
                                                            d="M12.7496 13.125L3.22461 23.25C3.37461 23.25 3.52461 23.175 3.67461 23.1L15.8996 16.5L12.7496 13.125Z"
                                                            fill="#FF3A44" />
                                                    </svg></span>
                                                <span class="text"> Available on <strong>Google Play</strong></span>
                                                <!-- <img class="w-100" src="{{ asset('assets/images/playstore.png') }}" alt="image"  > -->
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="control-label col-12">{{ __('Short Code') }}</label><br />
                                        <h1 class="control-label col-12" style="font-size: 3rem;">
                                            {{ Auth::user()->code }}</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name" class="control-label">{{ __('NAME') }}</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{ old('name', Auth::user()->name ?? '') }}" placeholder="John Doe">
                                        @if ($errors->has('name'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email" class="control-label">{{ __('EMAIL') }}</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email', Auth::user()->email ?? '') }}"
                                            placeholder="{{ __('Enter email address') }}" {{ $is_readonly }}>
                                        @if ($errors->has('email'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="phone_number"
                                            class="control-label">{{ __('CONTACT NUMBER') }}</label>
                                        <div class="input-group w-100">
                                            <input style="width:100%;" type="text" class="form-control"
                                                name="phone_number" id="phone_number"
                                                value="{{ old('phone_number', Auth::user()->phone_number ?? '') }}">
                                        </div>
                                        @if ($errors->has('phone_number'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('phone_number') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="company_address"
                                            class="control-label">{{ __('COMPANY ADDRESS') }}</label>
                                        <input type="text" class="form-control" id="company_address"
                                            name="company_address"
                                            value="{{ old('company_address', Auth::user()->company_address ?? '') }}"
                                            placeholder="{{ __('Enter company address') }}">
                                        @if ($errors->has('company_address'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('company_address') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="company_name" class="control-label">{{ __('COMPANY NAME') }}</label>
                                        <input type="text" class="form-control" name="company_name" id="company_name"
                                            value="{{ old('company_name', Auth::user()->company_name ?? '') }}"
                                            placeholder="Enter company name" {{ $is_readonly }}>
                                        @if ($errors->has('company_name'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('company_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3" id="countryInput">
                                        <label for="country">{{ __('COUNTRY') }}</label>
                                        @if ($errors->has('country'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('country') }}</strong>
                                            </span>
                                        @endif
                                        <select class="form-control" id="country" name="country"
                                            value="{{ old('country', $client->id ?? '') }}"
                                            placeholder="{{ __('Country') }}">
                                            @foreach ($countries as $code => $country)
                                                <option value="{{ $country->id }}"
                                                    @if (Auth::user()->country_id == $country->id) selected @endif {{ $is_disabled }}>
                                                    {{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3" id="timezoneInput">
                                        <label for="timezone">{{ __('TIMEZONE') }}</label>
                                        @if ($errors->has('timezone'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('timezone') }}</strong>
                                            </span>
                                        @endif
                                        <select class="form-control" id="timezone" name="timezone"
                                            value="{{ old('timezone', $client->timezone ?? '') }}"
                                            placeholder="{{ __('Timezone') }}">
                                            @foreach ($tzlist as $tz)
                                                {{-- <option value="{{ $tz }}" @if (Auth::user()->timezone == $tz) selected @endif>{{ $tz }}</option> --}}
                                                <option value="{{ $tz->id }}"
                                                    @if (Auth::user()->timezone == $tz->id) selected @endif {{ $is_disabled }}>
                                                    {{ $tz->timezone . ' (' . $tz->diff_from_gtm . ')' }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit"
                                        class="btn btn-blue waves-effect waves-light">{{ __('Update') }}</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="col-md-8" style="width: 100vw">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('client.password.update') }}">
                            @csrf
                            <h4 class="header-title">{{ __('Change Password') }}</h4>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="old_password">{{ __('Old Password') }}</label>
                                        <div class="input-group input-group-merge ">
                                            <input class="form-control " name="old_password" type="password"
                                                required="" id="old_password"
                                                placeholder={{ __('Enter your old password') }}>
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($errors->has('old_password'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('old_password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="password">{{ __('New Password') }}</label>
                                        <div class="input-group input-group-merge ">
                                            <input class="form-control " name="password" type="password" required=""
                                                id="password" placeholder={{ __('Enter your password') }}>
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="confirm_password">{{ __('Confirm Password') }}</label>
                                        <div class="input-group input-group-merge ">
                                            <input class="form-control " name="password_confirmation" type="password"
                                                required="" id="confirm_password"
                                                placeholder={{ __('Enter your confirm password') }}>
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>

                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-blue btn-block" type="submit"> {{ __('Update') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>





    </div> <!-- container -->
@endsection

@section('script')
    <script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
    <!-- Page js-->
    <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
    <script src="{{ asset('assets/js/storeClients.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.18/js/intlTelInput.min.js"></script>

    <script>
        var input = document.querySelector("#phone_number");
        var iti = window.intlTelInput(input, {
            separateDialCode: true,
            preferredCountries: ["{{ getCountryCode() }}"],
            initialCountry: "{{ getCountryCode() }}",
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.18/js/utils.js",
        });

        $('.intl-tel-input').css('width', '100%');


        $(function() {
            $('#phone_number').focus(function() {
                $('#phone_number').css('color', '#6c757d');
            });
        });
        $(function() {
            var height = $('#favicon_container').height();
            $('#favicon_container').css('width', height + 'px');
        });
    </script>
@endsection
