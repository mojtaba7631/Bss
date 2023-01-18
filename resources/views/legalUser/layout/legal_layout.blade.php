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

    <link rel="icon" href="{{asset('images/favicon.png')}}" type="image/png">
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/animate-css/vivify.min.css')}}">
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/jquery-steps/jquery.steps2.css')}}">


    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/c3/c3.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.css')}}">
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/toastr/toastr.min.css')}}">

    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/dropify/css/dropify.min.css')}}">
    <link rel="stylesheet" href="{{asset('public-admin/assets/css/jalalidatepicker.min.css')}}">

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="{{asset('public-admin/assets/css/site2.min.css')}}">
{{--    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/table-dragger/table-dragger.min.css')}}">--}}

    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/summernote/dist/summernote.css')}}"/>

    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/chartist/css/chartist.css')}}">

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    @yield('css')
</head>
<body class="theme-cyan font-iransans light_version rtl">
@include('sweet::alert')
@include('legalUser.layout.page_loader')
@include('legalUser.layout.top_navbar')
{{--@include('legalUser.layout.rightbar')--}}
@include('legalUser.layout.side_bar')

@yield('content')

<!-- Javascript -->

<script src="{{asset('public-admin/assets/js/jquery-min.js')}}"></script>
<script src="{{asset('public-admin/assets/js/sweetalert.js')}}"></script>
<script src="{{asset('public-admin/assets/bundles/libscripts.bundle.js')}}"></script>
<script src="{{asset('public-admin/assets/bundles/vendorscripts.bundle.js')}}"></script>
{{--<script src="{{asset('public-admin/assets/vendor/table-dragger/table-dragger.min.js')}}"></script>--}}

<script src="{{asset('public-admin/assets/vendor/toastr/toastr.min.js')}}"></script>

<script src="{{asset('public-admin/assets/vendor/jquery-validation/jquery.validate.js')}}"></script>
<!-- Jquery Validation Plugin Css -->
<script src="{{asset('public-admin/assets/vendor/jquery-steps/jquery.steps.js')}}"></script>
<!-- JQuery Steps Plugin Js -->
<script src="{{asset('public-admin/assets/js/pages/forms/form-wizard.js')}}"></script>

<script src="{{asset('public-admin/assets/vendor/dropify/js/dropify.js')}}"></script>
<script src="{{asset('public-admin/assets/js/pages/forms/dropify.js')}}"></script>

<script src="{{asset('public-admin/assets/bundles/mainscripts.bundle.js')}}"></script>

<script src="{{asset('public-admin/assets/js/jalalidatepicker.min.js')}}"></script>

<script src="{{asset('public-admin/assets/js/contract_custom.js')}}"></script>

<script src="{{asset('public-admin/assets/vendor/summernote/dist/summernote.js')}}"></script>

<script src="{{asset('public-admin/assets/bundles/chartist.bundle.js')}}"></script>

<script src="{{asset('public-admin/assets/js/script.js')}}"></script>

@yield('js')
</body>
</html>
