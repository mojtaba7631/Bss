<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description"
          content="Oculux Bootstrap 4x admin is super flexible, powerful, clean &amp; modern responsive admin dashboard with unlimited possibilities.">
    <meta name="keywords"
          content="admin template, Oculux admin template, dashboard template, flat admin template, responsive admin template, web app, Light Dark version">
    <meta name="author" content="GetBootstrap, design by: puffintheme.com">

    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/animate-css/vivify.min.css')}}">

    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/c3/c3.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.css')}}">
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/toastr/toastr.min.css')}}">

    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/dropify/css/dropify.min.css')}}">

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="{{asset('public-admin/assets/css/site2.min.css')}}">
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/summernote/dist/summernote.css')}}"/>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/chartist/css/chartist.css')}}">
    <link rel="stylesheet" href="{{asset('public-admin/assets/css/persianDatePicker.css')}}">
    <link rel="stylesheet" href="{{asset('public-admin/assets/css/sweet.css')}}">


    @yield('css')
</head>
<body class="theme-cyan font-iransans light_version rtl">
@include('sweet::alert')
@include('head_discourse.layout.page_loader')
@include('head_discourse.layout.top_navbar')
{{--@include('employer.layout.rightbar')--}}
@include('head_discourse.layout.side_bar')
@yield('content')


<!-- Javascript -->

<script src="{{asset('public-admin/assets/js/jquery-min.js')}}"></script>
<script src="{{asset('public-admin/assets/bundles/libscripts.bundle.js')}}"></script>
<script src="{{asset('public-admin/assets/bundles/vendorscripts.bundle.js')}}"></script>

<script src="{{asset('public-admin/assets/bundles/c3.bundle.js')}}"></script>
<script src="{{asset('public-admin/assets/bundles/chartist.bundle.js')}}"></script>
<script src="{{asset('public-admin/assets/bundles/knob.bundle.js')}}"></script>
<script src="{{asset('public-admin/assets/vendor/toastr/toastr.min.js')}}"></script>

<script src="{{asset('public-admin/assets/vendor/dropify/js/dropify.js')}}"></script>
<script src="{{asset('public-admin/assets/js/pages/forms/dropify.js')}}"></script>

<script src="{{asset('public-admin/assets/vendor/summernote/dist/summernote.js')}}"></script>
<script src="{{asset('public-admin/assets/bundles/mainscripts.bundle.js')}}"></script>
<script src="{{asset('public-admin/assets/js/script.js')}}"></script>
<script src="{{asset('public-admin/assets/js/persian-date.min.js')}}"></script>
<script src="{{asset('public-admin/assets/js/persian-datepicker.min.js')}}"></script>
<script src="{{asset('public-admin/assets/js/sweet.js')}}"></script>

{{--@include('sweetalert::alert')--}}
@yield('js')
</body>
</html>
