@extends('deputy_plan_program.layout.deputy_plan_program_layout')
@section('title',"فرم درخواست مرخصی")
@section('css')
    <style>
        .card-header {
            padding: 24px !important;
            margin-bottom: 15px;
            background-color: #fff !important;
            box-shadow: 0 0 15px rgb(0 0 0 / 20%);
            color: #000 !important;
        }

        .fnt_leave {
            font-size: 15px;
        }

        .clock {
            display: none;
        }

        .day_leave {
            display: none;
        }

        .my_inp_100 {
            width: 100px !important;
            display: inline-block;
            text-align: center;
        }
        .my_inp_140{
            width: 140px;
            display: inline-block;
            text-align: center;
        }

        .my_inp_200 {
            width: 112px;
            display: inline-block;
            text-align: center;
        }

        .my_select {
            width: 200px;
            display: inline-block;
        }

        .my_container {
            border: 3px solid #70bac4;
            padding: 15px;
            border-radius: 10px;
        }

        .my_select_theme {
            background: #e4e4e4;
            border-radius: 10px;
            color: #868686;
        }

        .my_btn {
            background: #70bac4;
            border-radius: 10px;
            color: #ffffff;
        }
    </style>
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>فرم درخواست مرخصی</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#">نما</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    فرم درخواست مرخصی
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right hidden-xs">
                        <a href="{{route('leave_deputy_index')}}" class="btn btn-sm btn-danger"
                           title="">
                            <i class="fa fa-arrow-right mr-4"></i>
                            بازگشت به لیست مرخصی ها
                        </a>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>
                                تقاضای مرخصی
                            </h5>
                        </div>
                        <form action="{{route('leave_deputy_store')}}" enctype="multipart/form-data"
                              method="post">
                            @csrf
                            <div class="card-body">
                                <div class="container-fluid my_container">
                                    <div class="row">
                                        <div class="col-12 fnt_leave">
                                            اینجانب {{$user_info['name'] . ' ' . $user_info['family']}} به
                                            سمت {{$role_title}} در
                                            مرکز نوسازی تقاضای مرخصی <select
                                                class="form-control my_select my_select_theme" id="type_select_leave"
                                                name="type_select_leave">
                                                <option value="0">ساعتی یا روزانه</option>
                                                <option value="1">ساعتی</option>
                                                <option value="2">روزانه</option>
                                            </select> دارم
                                        </div>
                                        <div class="col-12">
                                            <div class="clock fnt_leave mt-5">
                                                <p class="alert alert-info">لطفا تعداد ساعت و روز مرخصی را تعیین
                                                    فرمایید</p>
                                                <div class="row justify-content-center text-center">
                                                    <div class="col-12 col-md-4 ">
                                                        به مدت <input type="text"
                                                                      class="form-control my_inp_200 focus my_select_theme mb-3"
                                                                      name="hour_leave_count">
                                                        ساعت
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        از ساعت <input type="text"
                                                                       class="form-control my_inp_200 focus my_select_theme mb-3"
                                                                       name="start_hour">
                                                        روز <input type="text"
                                                                   class="form-control datePicker my_inp_200 my_select_theme mb-3"
                                                                   name="start_day">
                                                    </div>
                                                    <div class="col-12 col-md-4 ">
                                                        تا ساعت <input type="text"
                                                                       class="form-control my_inp_200 focus my_select_theme mb-3"
                                                                       name="end_hour">
                                                        روز <input type="text"
                                                                   class="form-control datePicker my_inp_200 my_select_theme mb-3"
                                                                   name="end_day">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="day_leave fnt_leave mt-5">
                                                <p class="alert alert-info">لطفا تعداد روز مرخصی را تعیین فرمایید</p>
                                                <div class="row">
                                                    <div class="col-12 col-md-4 text-center">
                                                        به مدت <input type="text"
                                                                      class="form-control my_inp_140 my_select_theme focus mb-3"
                                                                      name="day_leave_count">
                                                        روز
                                                    </div>
                                                    <div class="col-12 col-md-4 text-center">
                                                        از روز <input type="text"
                                                                      class="form-control  datePicker my_inp_140 my_select_theme mb-3"
                                                                      name="start_day_daily">
                                                    </div>
                                                    <div class="col-12 col-md-4 text-center">
                                                        تا روز <input type="text"
                                                                      class="form-control datePicker my_inp_140 my_select_theme mb-3"
                                                                      name="end_day_daily">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-right mt-4">
                                        <button type="submit" class="btn my_btn">ثبت درخواست</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(".dropify").dropify();


        var type_select_leave = $('#type_select_leave').val();
        if (type_select_leave == 1) {
            $('.clock').show();
        }

        $('#type_select_leave').change(function () {
            var val_type_leave = $(this).val();

            if (val_type_leave == 1) {
                $('.clock').show();
                $('.day_leave').hide();
            } else if (val_type_leave == 0) {
                $('.clock').hide();
                $('.day_leave').hide();
            } else if (val_type_leave == 2) {
                $('.day_leave').show();
                $('.clock').hide();
            }
        });

        $('.datePicker').persianDatepicker({
            format: "YYYY/MM/DD",
            viewMode: 'year',
            initialValue: false,
        });
    </script>
@endsection
