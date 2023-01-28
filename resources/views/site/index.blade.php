<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('home_assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('home_assets/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('home_assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('home_assets/css/responsive.css')}}">

    <title>سامانه مدیریت قراردادها</title>
</head>
<body>
<video muted="muted" autoplay="autoplay" loop="loop" id="main_bg"
       src="home_assets/img/BG.mp4"></video>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-md-11">
            <div class="row justify-content-md-center justify-content-lg-start">
                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="row mt-5">
                        <div class="col-6 text-center">
                            <a href="/login" class="text-dark text_11pt btn
                                btn-outline-warning px-5 font-weight-bold
                                clearfix d-inline-block menu_btn">
                                <i class="fa fa-sign-in"></i>
                                <span>ورود</span>
                            </a>
                        </div>

                        <div class="col-6 text-center">
                            <a href="/register" class="text_11pt btn btn-warning px-5
                                font-weight-bold clearfix d-inline-block
                                menu_btn">
                                <i class="fa fa-user-plus"></i>
                                <span>ثبت نام</span>
                            </a>
                        </div>

                        <div class="col-12 text-center mt-5">
                            <img src="{{asset('home_assets/img/hamyaran2.png')}}" class="logo mt-0 mt-lg-5" alt="سامانه نما">
                        </div>

                        <div class="col-12 mt-4">
                            <img class="logo_text" src="{{asset('home_assets/img/hamyaran1.png')}}" alt="سامانه نما">
                        </div>
                        <div class="col-12 mt-4 text-center">
                            <span class="support_style">
                                جهت پشتیبانی سامانه لطفا در پیام رسان ایتا یا بله به تلفن همراه نماینده ی جهت تیکت (فقط متن) ارسال نمایید. کاظمی (09128383357)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bl_div">
    <img alt="سامانه نما" src="{{asset('home_assets/img/farman-01.png')}}">
    <img alt="سامانه نما" src="{{asset('home_assets/img/std (1).png')}}">
    <img alt="سامانه نما" src="{{asset('home_assets/img/noavarihaiejtemaii.png')}}">
</div>

<div class="support_box">
    <div class="support_first">
        <img src="{{asset('home_assets/img/support.png')}}" alt="سامانه نما">
    </div>
    <div class="support_second">
        <a href="tel:09128383357">شرکت باتاب ارتباط گستر جهت: 09128383357</a>
    </div>
</div>


<script src="{{asset('home_assets/js/jQuery.js')}}"></script>
<script>
    var support_box = $(".support_box");
    var support_second = $(".support_second");
    support_box.hover(function () {
        support_second.show();
    }, function () {
        support_second.hide();
    })
</script>

</body>
</html>
