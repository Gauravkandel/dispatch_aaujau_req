<html>

<head>
  <title>{{__('CcAvenue Payment Flow')}}</title>
</head>

<body>
    <form method="post"  id="ccavenue" name="redirect" action="{{$url}}"> 
        <input type=hidden name=encRequest value="{{$encrypted_data}}">
        <input type=hidden name=access_code value="{{$access_code}}">
    </form>
      
    <div style="display: block;margin:15% 45%"><img src="{{asset('loader.webp')}}"></div>
      
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
 document.getElementById("ccavenue").submit();
</script>
</body>
</html>