@extends('admin.layout.reg_layout')
@section('title',"ورود")

@section('css')
    <style>
        #res_msg {
            display: none;
        }
    </style>


    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            var video = document.getElementById("bg_video");
            video.play();
        })

        var password_inp = $("#password");
        var username_inp = $("#username");
        var remember_checkbox = $("#remember_checkbox");

        $(password_inp, username_inp).on('keyup', function (event) {
            console.log(event);
            if (event.keyCode === 13) {
                myFunction();
            }
        });

        function myVerifyFunction() {
            let _token_ = $('meta[name="csrf-token"]').attr('content');
            let username = username_inp.val();
            let password = password_inp.val();
            let res_msg = $("#res_msg");
            let login_modal = $("#login_modal");

            var data = {
                '_token': _token_,
                'username': username,
                'password': password,
                'remember': remember_checkbox.val(),
            };

            res_msg.slideUp();
            $.post("{{route('verify')}}", data,
                function (result) {
                    if (!result.error && result.status) {
                        window.location = result.redirect;
                    } else if (result.hasManyRole) {
                        //has many role
                        login_modal.modal('show');
                    } else {
                        res_msg.find("p").text(result.message);
                        res_msg.slideDown();
                    }
                }
            );
        }
    </script>
@endsection

@section('content')
    <div class="theme-cyan font-montserrat rtl">
{{--        <input type="hidden" id="_token_value_" value="{{csrf_token()}}">--}}
        <div class="auth-main2">
            <div class="auth_div vivify fadeInTop">
                <div class="card">
                    <div class="body">
                        <div class="login-img">
                            <img class="img-fluid" src="{{asset('placeholder/login/login_img.jpg')}}"/>
                        </div>
                        <form class="form-auth-small">
                            <div class="mb-3">
                                <p class="lead">وارد حساب کاربری خود شوید</p>
                            </div>

                            <div id="res_msg" class="row">
                                <div class="col-12">
                                    <p class="alert alert-danger">
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="username" class="control-label sr-only">شناسه کاربری</label>
                                <input type="text" class="form-control round" id="username" placeholder="شناسه کاربری">
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label sr-only">رمزعبور</label>
                                <input type="password" class="form-control round" id="password"
                                       placeholder="رمزعبور">
                            </div>
                            <div class="form-group clearfix">
                                <label class="fancy-checkbox element-left">
                                    <input type="checkbox" name="remember" id="remember_checkbox">
                                    <span>مرا به خاطر بسپار</span>
                                </label>
                            </div>

                            <button id="login_btn" type="button" onclick="myVerifyFunction()"
                                    class="btn btn-primary btn-round btn-block">
                                ورود
                            </button>

                            <div class="mt-4">
                                <span>حساب کاربری ندارید؟ <a href="{{route('register')}}">ثبت نام</a></span>
                            </div>
                        </form>
                        <div class="pattern">
                            <span class="red"></span>
                            <span class="indigo"></span>
                            <span class="blue"></span>
                            <span class="green"></span>
                            <span class="orange"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="particles-js"></div>
        </div>

        <div class="modal" id="login_modal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">انتخاب نقش</h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-2">
                                    <input type="radio" name="role">
                                    <label>تست</label>
                                </div>

                                <div class="mb-2">
                                    <input type="radio" name="role">
                                    <label>تست</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success">ورود به حساب کاربری</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">انصراف</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
