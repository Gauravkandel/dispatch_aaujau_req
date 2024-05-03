<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>Complete Checkout</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" href="{{ asset('telinput/css/demo.css') }}" type="text/css">
</head>
<body>
<section class="section-b-space">
    <div class="container-fluid">
        <div class="payment_response">

        </div>
    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
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
</script>
</body>
</html>
