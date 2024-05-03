<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Route Pdf</title>
    <style>
        body{width:100%;overflow-x:hidden;background:#fff;font-family:'Open Sans',sans-serif}
        a{text-decoration:none;color:#000;outline:0}
        a:hover{text-decoration:none;color:#000;outline:0}
        a:focus{text-decoration:none;outline:0}.clear{clear:both}
        img-fluid{max-width:100%;height:auto}
        ul{margin: 0;padding: 0;}
        ul li{list-style:none}
        .p-0{padding:0!important}
        .border-0{border:0}
        .container{width:750px;max-width:100%;display:block;margin:20px auto;background:#fff;padding:30px 0}
        .w-100{width:100%;display:inline-block}
        .pull-right{float:right}
        .text-center{text-align:center}
        .bold-text{font-weight:600}
        .row{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap}
        .text-right{text-align:right}
        p{font-size:14px;font-weight:600;color:#85889f}
        table{border-collapse:separate;text-indent:initial;border-spacing:0}
        td,th{text-align:left;padding:8px;font-weight:unset}
        .page-break{page-break-inside:avoid}
        p{font-size:14px;font-weight:400;color:#85889f;margin: 0;}
        .col-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}
        .col-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}
        .col-sm-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}
        .col-sm-5{-ms-flex:0 0 41.666667%;flex:0 0 41.666667%;max-width:41.666667%}
        .col-sm-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}
        .pdf-path-address li{list-style: none;}
        .address_box p b{color: #000;text-transform: capitalize;}
        .address_box p:nth-child(2){margin: 5px 0;}
        h2{margin: 0;font-size: 18px;}
        .address_box{margin: 10px 0 0;}
        .pdf-path-address{margin: 30px 0 25px;}
        .pdf-path-address li{margin: 0 0 5px;}        
        .pdf-path-address li span {font-size: 14px;font-weight: 400;color: #85889F;margin: 0;}
    </style>
</head>

<body>
    <!-- PDF Section Start Form Here -->
    <div class="container">       
        

        <div class="row">
            <div class="col-12">
                <?php
                    if($agent_name=="")
                    {
                        $pathtitle = "Route for : ".$date;
                    }else{
                        $pathtitle = "Route for : ".ucfirst($agent_name).", ".$date;
                    }
                    ?>
                <ul class="pdf-path-address">
                    <li><b>{{$pathtitle}}</b> </li>
                    <?php
                        for ($i=0; $i < count($path); $i++) { ?>
                            <li><span>{{ $path[$i] }}</span></li>
                        <?php }
                    ?>                   
                </ul>
            </div>
        </div>      
        <div class="row">
            <div class="col-12">
                <div class="address_box" style="border:1px solid rgba(0,0,0,.125);border-radius:4px;padding:16px">
                    <?php 
                        foreach ($route as $singleroute) { ?>
                            <p>{!! $singleroute->turn !!}</p>
                            {{-- <p><b>Distance :</b> <span>{{$singleroute->distance}}</span> </p> --}}
                            {{-- <p><b>Time :</b> <span>{{$singleroute->duration}}</span> </p>                             --}}
                        <?php }                   
                    ?>
                </div>            
            </div>
        </div>


</body>

</html>
       