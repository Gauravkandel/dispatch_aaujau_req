<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>{{__('Payment Checkout')}}</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/font-awesome.min.css')}}"> --}}
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" href="{{ asset('telinput/css/demo.css') }}" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/waitMe.min.css')}}">
    <style>
        .spinner-overlay .page-spinner .circle-border {
            background: linear-gradient(0deg, rgba(0, 0, 0, 0.5) 33%, rgba(255, 255, 255, 1) 100%);
        }
        @keyframes spin {
            from {
                transform:rotate(0deg);
            }
            to {
                transform:rotate(360deg);
            }
        }
    </style>
</head>
<body>
<section class="section-b-space">
    <div class="container bg-light" style="margin:0 auto;margin-top:50px;">
        
    </div>
</section>
<p class="success-payment-message" />
<div class="spinner-overlay">
    <div class="page-spinner">
        <div class="circle-border">
            <div class="circle-core"></div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
<script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('assets/js/waitMe.min.js')}}"></script>
<script>
    var payment_khalti_url = "{{route('payment.khaltiVerification')}}";
    var payment_khalti_complete_purchase = "{{route('payment.khaltiCompletePurchaseApp')}}";
    let payment_return_url = "{{route('payment.gateway.return.response')}}/?gateway=khalti";
    let khalti_api_key = "{{ $khalti_api_key }}";
    let queryString = window.location.search;
    let path = window.location.pathname;
    let urlParams = new URLSearchParams(queryString);
    let order_number = '';
    let subscription_id = '';
    let total_amount = 0;
    let auth_token = '';
    let action = '';
    var ajaxData = [];
    
    @if(isset($data['amount']))
        total_amount = parseFloat("{{ $data['amount'] }}");
        ajaxData.push({name: 'amount', value: total_amount});
    @endif
    @if(isset($data['order']))
        order_number = "{{ $data['order'] }}";
        payment_return_url = payment_return_url + '&order=' + order_number;
        ajaxData.push({name: 'order_id', value: order_number});
    @endif
    @if(isset($data['action']))
        action = "{{ $data['action'] }}";
        payment_return_url = payment_return_url + '&action=' + action;
        ajaxData.push({name: 'payment_form', value: action });
    @endif
    @if(isset($data['subscription_id']))
        subscription_id = "{{ $data['subscription_id'] }}";
        ajaxData.push({name: 'subscription_id', value: subscription_id });
    @endif
    @if(isset($data['auth_token']))
        auth_token = "{{ $data['auth_token'] }}";
        ajaxData.push({name: 'auth_token', value: auth_token });
    @endif

    let product_id_arr_string = 'WALLET_R9N6O5';
    let product_name_arr_string = 'WALLET PRODUCT';
    var client_company_name = "{{getClientDetail()->company_name}}";
    
    var khaltipay_options = {
        // replace the publicKey with yours
        "publicKey": khalti_api_key,
        "currency": "NPR",
        "name": client_company_name,
        "productIdentity": product_id_arr_string,
        "productName": product_name_arr_string,
        "productName": product_name_arr_string,
        "productUrl": "https://www.yohopartner.com/",
        "eventHandler": {
            onSuccess (payload) {
                //startLoader('body','We are processing your transaction...');
                spinnerJS.showSpinner();
                console.log(payload,'payload');
                console.log(ajaxData,'ajaxData');
                // hit merchant api for initiating verfication
                ajaxData.push({name: 'amount', value: payload.amount});
                ajaxData.push({name: 'mobile', value: payload.mobile});
                ajaxData.push({name: 'product_identity', value: payload.product_identity});
                ajaxData.push({name: 'token', value: payload.token});
                ajaxData.push({name: 'payment_id', value: payload.idx});

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: payment_khalti_url,
                    type: 'POST',
                    data: ajaxData,
                    success: function(data)
                    {
                        khaltiPayCompletePayment(data);
                        spinnerJS.hideSpinner();
                    },
                    error: function(data)
                    {
                        spinnerJS.hideSpinner();
                        console.log("PAY onSuccess Success error");
                        //redirext to error page
                    }
                });
            },
            onError (error) {
                spinnerJS.hideSpinner();
                console.log('OnError'+error);
                //redirect as needed
            },
            onClose () {
                spinnerJS.hideSpinner();
                console.log('widget is closing');
                //redirect as needed
            }
        }
    }

    var khaltipay = new KhaltiCheckout(khaltipay_options);
    khaltipay.show({amount: (total_amount*100).toFixed(0)});

    
    const startLoader = function(element) {
        // check if the element is not specified
        if (typeof element == 'undefined') {
            element = "body";
        }
        // set the wait me loader
        $(element).waitMe({
            effect: 'bounce',
            text: 'Please Wait..',
            bg: 'rgba(255,255,255,0.7)',
            //color : 'rgb(66,35,53)',
            color: '#EFA91F',
            sizeW: '20px',
            sizeH: '20px',
            source: ''
        });
    }
    
    const stopLoader = function(element) {
        // check if the element is not specified
        if (typeof element == 'undefined') {
            element = 'body';
        }
        // close the loader
        $(element).waitMe("hide");
    }

    function khaltiPayCompletePayment(data) {
        // console.log('khaltiPayCompletePayment '+JSON.stringify(data));
        $.ajax({
            type: "POST",
            dataType: 'json',
            async: false,
            url: payment_khalti_complete_purchase,
            data: data,
            success: function(response) {
                if (response.status == "Success") {
                    payment_return_url = payment_return_url + '&status=200&transaction_id=' + response.data;
                } else {
                    payment_return_url = payment_return_url + '&status=0';
                }
                location.href = payment_return_url;
            },
            error: function(error) {
                payment_return_url = payment_return_url + '&status=0';
                location.href = payment_return_url;
            }
        });
    }

</script>

</body>
</html>
