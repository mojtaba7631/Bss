<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS only -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>دستور پرداخت</title>
    <style>
        @font-face {
            font-family: "web-shabnam";
            src: url("{{asset("public-admin/assets/fonts/shabnam-fd-wl.ttf")}}") format("truetype");
            font-weight: normal;
        }

        body.font-shabnam, body.font-shabnam * {
            font-family: "web-shabnam", sans-serif;
            font-size: 11pt !important;
        }

        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            direction: rtl;
            text-align: right;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm;
            margin: 5mm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .subpage {
            padding: 1cm;
            /*border: 5px #000 solid;*/
            height: 257mm;
            outline: 1cm #fff solid;
        }

        .head {
            font-size: 25px !important;
        }

        .signature {
            width: 4.5cm;
            height: 4.5cm;
            border-radius: 1cm;
            border: 2px solid #000;
            display: inline-block;
            position: relative;
        }


        .signature p {
            position: absolute;
            right: 0;
            left: 0;
            top: 20px;
            text-align: center;
            font-weight: bold;
        }

        .signature img {
            width: 100%;
            height: auto;
        }



        .signature  .stamp_img{
            display: block !important;
            width: 110px;
            height: 160px;
            text-align: center;
            margin:auto;
        }

        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            body.font-shabnam, body.font-shabnam * {
                font-family: "web-shabnam", sans-serif;
                font-size: 11pt !important;
            }

            body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                background-color: #FAFAFA;
                direction: rtl;
                text-align: right;
            }

            * {
                box-sizing: border-box;
                -moz-box-sizing: border-box;
            }

            .page {
                width: 210mm;
                min-height: 297mm;
                padding: 20mm;
                margin: 1mm auto;
                border: 1px #D3D3D3 solid;
                border-radius: 5px;
                background: white;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            }

            .subpage {
                padding: 1cm;
                /*border: 5px #000 solid;*/
                height: 257mm;
                outline: 1cm #fff solid;
                margin-top: -75px !important;
            }

            .signature {
                width: 4.5cm;
                height: 4.5cm;
                border-radius: 1cm;
                border: 2px solid #000;
                display: inline-block;
                position: relative;
            }

            .signature p {
                position: absolute;
                right: 0;
                left: 0;
                top: 20px;
                text-align: center;
                font-weight: bold;
            }

            .signature img {
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>
<body dir="rtl" class="font-shabnam">

<div class="book">
    <div class="page">
        <div class="subpage">
            <h1 class="text-center mb-5"><b style="font-size:24px !important;">موسسه همیاران پیشرفت شبکه ایرانیان</b>
            </h1>
            <h6 class="text-center"><b>برگ درخواست صدور چک / واریز اینترنتی</b></h6>
            <p>
                مدیریت محترم عامل
                <br>
                خواهشمند است دستور فرمایید مبلغ (به عدد) <b>{{@number_format($payment_info->price)}}</b> ریال به حروف
                <b id="price_characters"></b>
                تومان بابت <b>{{$project_info->title}}</b> در وجه <b>{{$user_name}}</b> پرداخت نمایند.

            </p>
            <div class="clearfix">
                <div class="signature float-left">
                    <p>مدیرمالی</p>
                    @if(file_exists($mali_manager['Signature_img']) and !is_dir($mali_manager['Signature_img']))
                        @php
                            $src = $mali_manager['Signature_img'];
                        @endphp
                    @else
                        @php
                            $src = 'placeholder/signature_placeholder.png';
                        @endphp
                    @endif
                    <img src="{{asset($src)}}">
                </div>
            </div>
            <br>
            <hr>
            <h6 class="text-center"><b>دستور پرداخت</b></h6>
            <p>
                امور مالی :
                <br>
                پرداخت مبلغ (به عدد) <b>{{@number_format($payment_info->price)}}</b> ریال، طی یک فقره چک به شماره/شمارگان یا واریز اینترنتی به کد پیگیری <b>{{$payment_info->following_code}}</b> به شرح
                فوق بلامانع می باشد.
            </p>

            <div class="clearfix">
                <div class="signature float-left">
                    <p>مدیر عامل</p>
                    @if(file_exists($main_manager['Signature_img']) and !is_dir($main_manager['Signature_img']))
                        @php
                            $src = $main_manager['Signature_img'];
                        @endphp
                    @else
                        @php
                            $src = 'placeholder/signature_placeholder.png';
                        @endphp
                    @endif
                    <img src="{{asset($src)}}">
                </div>
            </div>
            <br>
            <hr>
            <br>
            <h6 class="text-center"><b>دستور دریافت چک</b></h6>
            <p>
                اصل چک / چک ها به شماره / شمارگان <b>{{$payment_info->following_code}}</b> در تاریخ <b>{{$payment_info->payment_info_jalali}}</b> جمعا به مبلغ <b>{{@number_format($payment_info->price)}}</b> ریال ، تحویل اینجانب <b>{{$user_name}}</b> گردید
            </p>
            <div class="clearfix">
                <div class="signature float-left">
                    <p>تحویل گیرنده</p>
                    @if($project_info->type == 0)
                        @if(file_exists($project_info['Signature_img']) and !is_dir($project_info['Signature_img']))
                            @php
                                $src = $project_info['Signature_img'];
                            @endphp
                        @else
                            @php
                                $src = 'placeholder/signature_placeholder.png';
                            @endphp
                        @endif
                    @else
                        @if(file_exists($project_info['Signature_img']) and !is_dir($project_info['Signature_img']))
                            @php
                                $src = $project_info['Signature_img'];
                            @endphp
                        @else
                            @php
                                $src = 'placeholder/signature_placeholder.png';
                            @endphp
                        @endif

                        @if(file_exists($project_info['stamp_img']) and !is_dir($project_info['stamp_img']))
                            @php
                                $stamp_src = $project_info['stamp_img'];
                            @endphp
                        @else
                            @php
                                $stamp_src = 'placeholder/signature_placeholder.png';
                            @endphp
                        @endif
                    @endif
                    <img src="{{asset($src)}}"  class="stamp_img">
                </div>
                @if($project_info->type == 1)
                    <div class="signature float-right">
                        <img src="{{asset($stamp_src)}}" class="stamp_img">
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{asset('public-admin/assets/js/number_to_persian.js')}}"></script>

<script>
    $("#price_characters").text(Num2persian({{$payment_info->price / 10}}));
</script>
</body>
</html>
