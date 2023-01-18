<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="داشبورد مدیریت پروژه">
    <meta name="author" content="طراحی شده توسط موسسه جهت">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/animate-css/vivify.min.css')}}">
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/jquery-steps/jquery.steps.css')}}">

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="{{asset('public-admin/assets/css/site.min.css')}}">

    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/dropify/css/dropify.min.css')}}">
    <link rel="stylesheet" href="{{asset('public-admin/assets/css/persian-datepicker.min.css')}}">

    @yield('css')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<style>
    #bg_video {
        position: fixed;
        right: 0;
        bottom: 0;
        min-width: 100%;
        min-height: 100%;
    }

    .f {
        height: 100px;
        position: fixed;
        top: 0;
        right: 0;
        z-index: 9999;
        width: 100vw;
        background: rgba(0, 0, 0, .2);
        padding-bottom: 36px;
        box-shadow: 0 0 15px rgb(0 0 0 / 30%);
    }

    .a_reg {
        font-size: 13px;
        position: absolute;
        left: 75px;
        top: 34px;
    }

    .a_log {
        font-size: 18px;
        position: absolute;
        left: 10px;
        top: 37px;
        color: #000000;
        cursor: pointer;
    }

    .a_log:hover {
        text-decoration: none;
    }

    .i_a {
        position: absolute;
        top: 8px;
        right: -18px;
    }

    .img_mini_logo {
        width: 50px;
        height: 50px;
        margin-top: 23px;
    }

    .span_sty {
        font-size: 18px;
        position: absolute;
        right: 72px;
        top: 29px;
        color: #000000;
    }

    .span_sty_tiny {
        font-size: 13px;
        position: absolute;
        right: 69px;
        top: 51px;
        color: #000000;
    }

    @media (max-width: 679px) {
        .a_reg {
            font-size: 13px;
            position: absolute;
            left: 48px;
            top: 34px;
        }

        .span_sty {
            font-size: 18px;
            position: absolute;
            right: 85px;
            top: 29px;
            color: #000000;
        }

        .span_sty_tiny {
            font-size: 13px;
            position: absolute;
            right: 82px;
            top: 51px;
            color: #000000;
        }

        .img_mini_logo {
            width: 50px;
            height: 50px;
            margin-top: 23px;
            margin-right: 15px;
        }
    }
</style>
<body class="theme-cyan font-iransans rtl light_version">
<video id="bg_video" muted="muted" class="position-absolute" loop="loop" src="{{asset('back_video.mp4')}}"></video>

<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
        <div class="bar4"></div>
        <div class="bar5"></div>
    </div>
</div>
<div class="f">
    <div class="row " dir="ltr">
        <div class="col-12 col-md-3"></div>
        <div class="col-12 col-md-3">
            @if(!auth()->check())
                {{--                <a href="{{route('register')}}" class="btn btn-warning a_reg">ثبت نام</a>--}}
            @else
                <a href="{{route('login')}}"
                   class="btn btn-warning a_reg">{{auth()->user()->name ?? auth()->user()->co_name}}</a>
            @endif
        </div>
        <a href="{{route('home')}}" style="display: contents">
            <div class="col-12 col-md-3">
                <strong class="span_sty">سامانه نما</strong>
                <span class="span_sty_tiny">نرم افزار مدیریت قراردادها</span>
                <img class="img_mini_logo" src="{{asset('images/hamyaran.png')}}">
            </div>
        </a>

        <div class="col-12 col-md-3">
        </div>
    </div>
    <div class="auth-main particles_js">

        <div class="auth_div vivify popIn">

            @yield('content')

        </div>
    </div>

    <div id="my_loader">
        <img src="{{asset('placeholder/reg_loader.svg')}}">
    </div>

    <script src="{{asset('public-admin/assets/js/jquery-min.js')}}"></script>
    <script src="{{asset('public-admin/assets/bundles/libscripts.bundle.js')}}"></script>
    <script src="{{asset('public-admin/assets/bundles/vendorscripts.bundle.js')}}"></script>

    <script src="{{asset('public-admin/assets/vendor/jquery-validation/jquery.validate.js')}}"></script>
    <!-- Jquery Validation Plugin Css -->
    <script src="{{asset('public-admin/assets/vendor/jquery-steps/jquery.steps.js')}}"></script>
    <!-- JQuery Steps Plugin Js -->
    <script src="{{asset('public-admin/assets/js/pages/forms/form-wizard.js')}}"></script>
    <script src="{{asset('public-admin/assets/vendor/dropify/js/dropify.js')}}"></script>
    <script src="{{asset('public-admin/assets/js/pages/forms/dropify.js')}}"></script>
    <script src="{{asset('public-admin/assets/bundles/mainscripts.bundle.js')}}"></script>
    <script src="{{asset('public-admin/assets/js/custom.js')}}"></script>
    <script src="{{asset('public-admin/assets/vendor/summernote/dist/summernote.js')}}"></script>
    <script src="{{asset('public-admin/assets/js/persian-date.min.js')}}"></script>
    <script src="{{asset('public-admin/assets/js/persian-datepicker.min.js')}}"></script>
    <script src="{{asset('public-admin/assets/js/script.js')}}"></script>
@yield('js')
@include('sweet::alert')

</body>
</html>
