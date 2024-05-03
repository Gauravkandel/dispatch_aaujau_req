@php
  //  $image = Cache::get('clientdetails');
    $image = App\Model\Client::first();
 //   $image->name = __('Royo');
@endphp
<meta charset="utf-8" />
<title>{{$title ?? ' '}} | {{$image->name??__('Royo')}}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="{{__('Powered by')}}  {{$image->name??__('Royo')}} {{__('Dispatch. Fleet Management and Last Mile Delivery solution.')}}" name="description" />
<meta content=" {{$image->name??__('Royo')}} {{__('Apps')}}" name="author" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!-- App favicon -->
<!-- uol : {{$favicon}}-->
<link rel="shortcut icon" href="{{$favicon ?? ''}}">
