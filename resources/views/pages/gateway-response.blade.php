<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>Gateway Response</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/themify-icons.css')}}">
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/custom.css')}}">
</head>
<body>
<section class="section-b-space">
    <div class="container-fluid">
        <div class="payment_response">
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <span>{!! \Session::get('success') !!}</span>
                </div>
            @endif
            @if (\Session::has('error'))
                <div class="alert alert-danger">
                    <span>{!! \Session::get('error') !!}</span>
                </div>
            @endif
        </div>
    </div>
</section>
<script src="{{asset('front-assets/js/jquery-3.3.1.min.js')}}"></script>
{{-- <script>
    let queryString = window.location.search;
    let path = window.location.pathname;
    let urlParams = new URLSearchParams(queryString);
    if((urlParams.has('gateway')) && (urlParams.get('gateway') != '')) {
        $('.spinner-overlay').show();
        if((urlParams.has('status')) && (urlParams.get('status') == '200')) {
            $('.payment_response').html('<div class="alert mt-2 alert-success"><span>{{__("Thank you for your payment.")}}</span></div>');
        }else{
            $('.payment_response').html('<div class="alert mt-2 alert-danger"><span>{{__("Payment has been cancelled.")}}</span></div>');
        }
    }
</script> --}}
</body>
</html>
