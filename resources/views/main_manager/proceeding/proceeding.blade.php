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

    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/font-awesome/css/font-awesome.min.css')}}">
    <title>صورتجلسه</title>

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

        .my_download_btn {
            position: fixed;
            left: 0;
            top: 30vh;
        }

        .fa {
            font-family: 'FontAwesome' !important;
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
            margin: 15mm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .subpage {
            padding: 1cm;
            border: 5px #000 solid;
            height: 257mm;
            outline: 2cm #fff solid;
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

        .signature .stamp_img {
            display: block !important;
            width: 110px;
            height: 160px;
            margin: auto;
            text-align: center;

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
                border: 5px #000 solid;
                height: 257mm;
                outline: 2cm #fff solid;
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
            @if($project_info->type == 0)
                <p>
                    در تاریخ <b>{{$report_info['jalali_report_date']}}</b> طرفین قرارداد،
                    <b>موسسه همیاران پیشرفت شبکه ایران </b>
                    با نمایندگی ناظر پروژه {{$supervisor_info['name'] . " " . $supervisor_info['family']}}
                    و مجری پروژه <b>{{$user_name}}</b>
                    {{$project_info['name'] . " " . $project_info['family']}} حضور بهم رسانده و فاز
                    {{$phase_info['phase_number'] == 0 ? ' پیش پرداخت ' : $phase_info['phase_number']}}
                    از کار، از جانب مجری تحویل ناظر پروژه گردید.
                </p>
            @elseif($project_info->type == 1)
                <p>
                    در تاریخ <b>{{$report_info['jalali_report_date']}}</b> طرفین قرارداد،
                    <b>موسسه همیاران پیشرفت شبکه ایران </b>
                    با نمایندگی ناظر پروژه <b>{{$supervisor_info['name'] . " " . $supervisor_info['family']}}</b>
                    و مجری پروژه <b>{{$user_name}}</b>
                    با عنوان {{$project_info['name'] . " " . $project_info['family']}} حضور بهم رسانده و فاز
                    {{$phase_info['phase_number'] == 0 ? ' پیش پرداخت ' : $phase_info['phase_number']}}
                    از کار، از جانب مجری تحویل ناظر پروژه گردید.
                </p>
            @endif

            <div class="clearfix">
                <div class="signature float-right">
                    <p>کارفرما</p>
                    @if(file_exists($employer_info['Signature_img']) and !is_dir($employer_info['Signature_img']))
                        @php
                            $src = $employer_info['Signature_img'];
                        @endphp
                    @else
                        @php
                            $src = 'placeholder/signature_placeholder.png';
                        @endphp
                    @endif
                    <img src="{{asset($src)}}">
                </div>

                <div class="signature float-left">
                    <p>مجری پروژه</p>
                    @if(file_exists($project_info['Signature_img']) and !is_dir($project_info['Signature_img']))
                        @php
                            $src = $project_info['Signature_img'];
                        @endphp
                    @else
                        @php
                            $src = 'placeholder/signature_placeholder.png';
                        @endphp
                    @endif
                    <img src="{{asset($src)}}">
                </div>

                @if($project_info['type'] > 0)
                    <div class="signature float-left">
                        <p>مجری پروژه</p>
                        @if(file_exists($project_info['stamp_img']) and !is_dir($project_info['stamp_img']))
                            @php
                                $src = $project_info['stamp_img'];
                            @endphp
                        @else
                            @php
                                $src = 'placeholder/stamp_placeholder.png';
                            @endphp
                        @endif
                        <img src="{{asset($src)}}" class="stamp_img">
                    </div>
                @endif
            </div>

            <br>
            <hr>
            <br>

            <h4>
                <b>مدیرعامل محترم</b>
            </h4>

            <p>
                با سلام احترام با توجه به تحویل مرحله
                <b> {{$phase_info['phase_number'] == 0 ? ' پیش پرداخت ' : $phase_info['phase_number']}} </b>
                قرارداد {{$phase_info['title']}}
                از جانب آقای/خانم/شرکت <b> {{$user_name}} </b>
                خواهشمند است نسبت به پرداخت مبلغ <b> {{@number_format($payment_info['price'])}} </b>
                ریال از حق الزحمه مجری دستور مقتضی مبذول فرمایید.
            </p>

            <div class="clearfix">
                <div class="signature float-left">
                    <p>ناظر پروژه</p>
                    @if(file_exists($supervisor_info['Signature_img']) and !is_dir($supervisor_info['Signature_img']))
                        @php
                            $src = $supervisor_info['Signature_img'];
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

            <h4>
                <b>مدیر مالی محترم</b>
            </h4>

            <p>
                با سلام، پرداخت مطابق صورتجلسه فوق، بلامانع می باشد.
            </p>

            <div class="clearfix">
                <div class="signature float-left">
                    <p>مدیرعامل</p>
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

        </div>
    </div>
</div>

<a href="{{route('mainManager_getProceedingWord', ['payment_id' => $payment_info['id']])}}"
   class="btn btn-success my_download_btn">
    <i class="fa fa-download ml-2"></i>
    <span>دانلود صورت جلسه</span>
</a>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{asset('public-admin/assets/js/number_to_persian.js')}}"></script>

<script>

</script>
</body>
</html>
