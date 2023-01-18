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

    <title>Print</title>
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

        .img_logo {
            width: 100px;
            height: 100px;
        }

        .img_logo_sen {
            width: 600px;
            max-width: 100%;
            height: auto;
        }

        .hr_style {
            height: 15px !important;
            background: #0b5b97;
        }

        .attachment_tbl td, .attachment_tbl th {
            border: 2px solid #000;
            text-align: center;
            vertical-align: middle;
        }

        * {
            font-family: "web-shabnam", sans-serif !important;
        }

        .table-bordered thead td, .table-bordered thead th {
            border-bottom: 1px solid #000 !important;
        }

        .letter_title {
            font-size: 12pt;
        }

        .letter_content {
            text-align: justify;
        }

        .border_ {
            box-shadow: 0 0 15px rgba(0, 0, 0, .2);
            min-height: 95vh;
            margin-top: 20px;
            margin-bottom: 20px;
            width: 197mm;
        }

        .signature_img {
            width: 100%;
        }

        @media print {
            .btn_print {
                display: none;
                margin: auto;
            }
        }
    </style>
</head>
<body class="font-shabnam">

<div class="container border_">
    <div class="row">
        <div class="col-12">
            <hr class="hr_style">
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 text-center">
            <img src="{{asset('placeholder/hamyaran.png')}}" class="img_logo">
        </div>

        <div class="col-md-6">
            <img src="{{asset('placeholder/hamyaran_sen.png')}}" class="img_logo_sen">
        </div>

        <div class="col-md-3">
            <div class="row mt-3 text-center">
                <div class="col-12 mb-2">
                    تاریخ : ..........
                </div>
                <div class="col-12 mb-2">
                    شماره : ..........
                </div>
            </div>
        </div>
    </div>

    <div class="row text-right mt-5 px-4">
        <div class="col-12">
            <h5 class="letter_title">
                <b>موضوع: </b>
                {{$letter_info['title']}}
            </h5>
        </div>
    </div>

    <div class="row text-right mt-4 px-4">
        <div class="col-12">
            <div class="letter_content">
                {!! $letter_info['content'] !!}
            </div>
        </div>
    </div>

    <div class="row justify-content-end">
        <div class="col-12 col-sm-6 col-md-3">
            @if(file_exists($letter_info['Signature_img']) and !is_dir($letter_info['Signature_img']))
                @php
                    $src = asset($letter_info['Signature_img']);
                @endphp
            @else
                @php
                    $src = asset('placeholder/signature_placeholder.png');
                @endphp
            @endif
            <img class="signature_img" src="{{$src}}">
        </div>

        @if($letter_info['type'] == 1)
            <div class="col-12 col-sm-6 col-md-3">
                @if(file_exists($letter_info['stamp_img']) and !is_dir($letter_info['stamp_img']))
                    @php
                        $src = asset($letter_info['stamp_img']);
                    @endphp
                @else
                    @php
                        $src = asset('placeholder/stamp_placeholder.png');
                    @endphp
                @endif
                <img class="signature_img" src="{{$src}}">
            </div>
        @endif
    </div>

    @if($letter_info['sent'] == 0)
        <form method="post" action="{{route('maliManager_letter_final_submit', ['letter_id' => $letter_info['letter_id']])}}" class="row mt-4 pb-4">
            @csrf
            <div class="col-12 text-center">
                <button class="btn btn-success">
                    ارسال
                </button>
            </div>
        </form>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{asset('public-admin/assets/js/number_to_persian.js')}}"></script>

<script>

</script>
</body>
</html>
