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
    <link rel="stylesheet" href="{{asset('public-admin/assets/css/jalalidatepicker.min.css')}}">

    @yield('css')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</head>
<body class="theme-cyan font-montserrat rtl">
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
<div class="pattern">
    <span class="red"></span>
    <span class="indigo"></span>
    <span class="blue"></span>
    <span class="green"></span>
    <span class="orange"></span>
</div>
<div class="auth-main particles_js">
    <div class="auth_div vivify popIn">
        @yield('content')

    </div>
    <div id="particles-js"></div>
</div>

<div id="my_loader">
    <img src="{{asset('placeholder/reg_loader.svg')}}">
</div>

<script src="{{asset('public-admin/assets/js/jquery-min.js')}}"></script>
<script src="{{asset('public-admin/assets/bundles/libscripts.bundle.js')}}"></script>
<script src="{{asset('public-admin/assets/bundles/vendorscripts.bundle.js')}}"></script>
<script src="{{asset('public-admin/assets/bundles/mainscripts.bundle.js')}}"></script>

<script src="{{asset('public-admin/assets/vendor/jquery-validation/jquery.validate.js')}}"></script>
<!-- Jquery Validation Plugin Css -->
<script src="{{asset('public-admin/assets/vendor/jquery-steps/jquery.steps.js')}}"></script>
<!-- JQuery Steps Plugin Js -->
<script src="{{asset('public-admin/assets/js/pages/forms/form-wizard.js')}}"></script>

<script src="{{asset('public-admin/assets/vendor/dropify/js/dropify.js')}}"></script>
<script src="{{asset('public-admin/assets/js/pages/forms/dropify.js')}}"></script>

<script src="{{asset('public-admin/assets/js/jalalidatepicker.min.js')}}"></script>

<script src="{{asset('public-admin/assets/js/custom.js')}}"></script>
@yield('js')
@include('sweet::alert')

</body>
</html>
