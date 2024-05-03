<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared.title-meta', ['title' => "Log In"])

    @include('layouts.shared.head-css')
</head>

<body class="authentication-bg authentication-bg-pattern">

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <div class="auth-logo">
                                    <a href="#" class="logo logo-dark text-center">
                                        <span class="logo-lg">
                                            <img src="{{asset('assets/images/logo-dark.png')}}" alt="" height="40">
                                        </span>
                                    </a>

                                    <a href="#" class="logo logo-light text-center">
                                        <span class="logo-lg">
                                            <img src="{{asset('assets/images/logo-light.png')}}" alt="" height="40">
                                        </span>
                                    </a>
                                </div>
                                <h5>God Panel</h5>
                                <p class="text-muted mb-4 mt-3">Enter your email address and password to access admin panel.</p>
                            </div>

                            <form action="{{route('god.login')}}" method="POST" novalidate>
                                @csrf

                                <div class="form-group mb-3">
                                    <label for="emailaddress">Email address</label>
                                    <input class="form-control  @if($errors->has('email')) is-invalid @endif" name="email" type="email" id="emailaddress" required="" value="{{ old('email')}}" placeholder="Enter your email" />

                                    @if($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group mb-3">
                                    <!-- <a href="{{route('login')}}" class="text-muted float-right"><small>Forgot your
                                            password?</small></a> -->
                                    <label for="password">Password</label>
                                    <div class="input-group input-group-merge @if($errors->has('password')) is-invalid @endif">
                                        <input class="form-control @if($errors->has('password')) is-invalid @endif" name="password" type="password" required="" id="pass" placeholder="Enter your password" />
                                        <div class="input-group-append" data-password="false">
                                            <div class="input-group-text">
                                                <span class="fe-eye-off showpassword" id="newcheck"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-sm-left">
                                        @if (\Session::has('Error'))
                                        <span class="text-danger" role="alert">
                                            <strong>{!! \Session::get('Error') !!}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                        <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                    </div>
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary btn-block" type="submit"> Log In </button>
                                </div>



                            </form>

                            

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <!-- <p> <a href="{{route('second', ['auth', 'recoverpw-2'])}}" class="text-white-50 ml-1">Forgot your password?</a></p> -->
                            <!-- <p class="text-white-50">Don't have an account? <a href="{{route('register')}}" class="text-white ml-1"><b>Sign Up</b></a></p> -->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->


    <footer class="footer footer-alt">
        <script>
            // document.write(new Date().getFullYear())
        </script> &copy; All rights reserved by <a href="#" class="text-white-50">Royo Apps</a>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    </footer>

    @include('layouts.shared.footer-script')

</body>

</html>