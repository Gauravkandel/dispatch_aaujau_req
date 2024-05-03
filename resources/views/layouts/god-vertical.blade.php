<!DOCTYPE html>
    <html lang="en">

    <head>
        @include('layouts.shared/title-meta', ['title' => $title])
        @include('layouts.shared/head-css', ["demo" => "creative"])
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="{{asset('assets/js/vendor.min.js')}}"></script>
    </head>

    <body @yield('body-extra')>
        <!-- Begin page -->
        <div id="wrapper">
            @include('layouts.shared/god-topbar')

            @include('layouts.shared/god-left-sidebar')

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">                    
                    @yield('content')
                </div>
                <!-- content -->

                @include('layouts.shared/footer')

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->

        @include('layouts.shared/right-sidebar')

        @include('layouts.shared/footer-script')
        
    </body>
</html>