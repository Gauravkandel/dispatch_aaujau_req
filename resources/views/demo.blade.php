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
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
            <script>document.write(new Date().getFullYear())</script> &copy; All rights reserved by <a href="https://royoapps.com/" class="text-white-50">Royo Apps</a> 
        </footer>

        @include('layouts.shared.footer-script')
    </body>
    
</html>


    
