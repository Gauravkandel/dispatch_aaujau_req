<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Prescription</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style type="text/css">
        * {
            padding: 0px;
            margin: 0px;
            font-family: 'Open Sans', sans-serif;
        }

        .wrapper {
            background: #eee;
            padding: 6vh 6vw;
            min-height: 100vh;
        }

        .container {
            background: #fff;
            padding: 20px;
            max-width: 730px;
            margin: 0 auto;
            border-radius: 4px;
            background-repeat: repeat;
            width: 600px;
        }
    </style>
</head>

<body>
    <section class="wrapper">
        <div class="container" style="background: #fff;border-radius: 10px;padding: 50px 0 0;">

            <table style="width: 100%;text-align: center;">
                <thead style="padding: 0 30px;">
                    <tr>
                        <th style="padding: 0 0 30px 30px;">
                            <img style="width: 100px;" src="{{$client_logo}}" alt="">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: left;padding: 0 30px;">
                            <p style="font-size: 14px; margin-bottom: 15px;"><b>{{__('Hi ')}} {{$customer_name.','}} </b></p>
                            <p style="width: 85%;max-width: 100%; font-size: 14px;line-height: 30px; margin-bottom: 15px;">{{$content}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px 30px 20px;">
                            <div class="driver_deatil">
                                <div class="left-icon" style="width: 60px;height: 60px;overflow: hidden;border-radius: 50%;display: inline-block;vertical-align: middle;">
                                    <img src="{{$agent_profile}}" alt=""
                                        style="width: 100%;height: 100%;object-fit: cover;">
                                </div>
                                <div class="right_text" style="text-align: left;display: inline-block;vertical-align: middle;padding: 0 0 0 15px;">
                                    <h4 style="font-weight: 600;font-size: 20px;color: #3c4854;"><b>{{$agent_name}}</b></h4>
                                    <p style="font-size: 14px;text-transform: uppercase;font-weight: 600;">{{$number_plate}}</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tfoot style="background:#fafafa">
                    <tr>
                        <td style="padding: 20px 30px;font-size: 14px;">
                            {{__('© Copyright 2021. All Rights Reserved.')}}
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </section>


</body>

</html>
