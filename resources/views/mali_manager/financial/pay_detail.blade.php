@extends('mali_manager.layout.mali_layout')

@section('title')
    جدول پرداخت
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('public-admin/assets/css/persian-datepicker.min.css')}}">
    <style>
        .light_version .table tr td, .light_version .table tr th {
            background: none;
        }

        .light_version .table.table-custom tbody tr:nth-child(odd) {
            background: #e1f3ff !important;
        }

        a:hover {
            color: #007bff !important;
        }

        .text-small {
            font-size: 9pt !important;
        }

        .persian_char_code {
            display: inline-block;
            border: 2px solid #36c3ff;
            padding: 7px 10px;
            text-align: center;
            border-radius: 5px;
            margin-top: 2px;
        }

        .pccp {
            width: fit-content;
            margin: 0 auto;
        }

        .pccp:after {
            content: "";
            display: block;
            clear: both;
        }

        .pcc {
            float: left;
            margin: 5px;
        }

        .range-from-example, .range-to-example {
            position: absolute;
            right: 0;
            left: 0;
            margin: auto;
            top: 54px;
            max-width: 200px;
            display: none;
        }

        .date_calender {
            cursor: pointer;
            margin-top: 10px;
        }

        #start_date_show_text,
        #end_date_show_text {
            cursor: default;
        }

        .range-from-example, .range-to-example {
            z-index: 99999;
        }

        .datepicker-plot-area .datepicker-day-view .month-grid-box .header .header-row-cell,
        .datepicker-plot-area .datepicker-day-view .table-days td span,
        .datepicker-plot-area .datepicker-navigator .pwt-btn-switch,
        .datepicker-plot-area .datepicker-year-view .year-item, .datepicker-plot-area .datepicker-month-view .month-item {
            font-family: "IRANSansDN", sans-serif;
        }
    </style>
@endsection



@section('content')

    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>جدول پرداخت</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">جدول پرداخت</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-12">
                    <div class="card">
                        <div class="body">
                            <form method="post" action="{{route('maliManager_search_index_financial')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                                        <div class="input-group">
                                            <input name="project_unique_code_search" type="text" class="form-control"
                                                   placeholder="لطفا کد پروژه را وارد کنید">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                                        <div class="input-group">
                                            <input name="title" type="text" class="form-control"
                                                   placeholder="لطفا عنوان پروژه را وارد کنید">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                                        <div class="input-group">
                                            <select id="user_id" class="form-select form-control " name="user_id"
                                                    aria-label="Default select example ">
                                                <option selected value="0">لطفا مجری را انتخاب کنید</option>
                                                @foreach($users as $user)
                                                    @if($user->type == 1)
                                                        <option value="{{$user->user_id}}">
                                                            {{$user->co_name}}
                                                        </option>
                                                    @else
                                                        <option value="{{$user->user_id}}">
                                                            {{$user->name . ' ' . $user->family}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                                        <div class="input-group">
                                            <select id="supervisor" class="form-select form-control " name="supervisor"
                                                    aria-label="Default select example ">
                                                <option selected value="0">لطفا ناظر را انتخاب کنید</option>
                                                @foreach($supervisors as $supervisor)

                                                    <option value="{{$supervisor->user_id}}">
                                                        {{$supervisor->name . ' ' . $supervisor->family}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                                        <div class="input-group">
                                            <select id="employer" class="form-select form-control " name="employer"
                                                    aria-label="Default select example ">
                                                <option selected value="0">لطفا کارفرما را انتخاب کنید</option>
                                                @foreach($employers as $employer)

                                                    <option value="{{$employer->user_id}}">
                                                        {{$employer->name . ' ' . $employer->family}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if($searched)
                                        <div class="col-12 col-sm-6 col-md-2 mb-2">
                                            <button type="submit" class="btn btn-sm btn-primary btn-block">جستجو
                                            </button>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-2 mb-2">
                                            <a type="submit" href="{{route('maliManager_financial_index')}}"
                                               class="btn btn-sm btn-danger btn-block"
                                               title="">حذف فیلتر
                                            </a>
                                        </div>
                                    @else
                                        <div class="col-12 col-sm-6 col-md-4 mb-2">
                                            <button type="submit" class="btn btn-sm btn-primary btn-block">جستجو
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if(!empty($payments->all()))
                                <div class="row">
                                    <div class="col-12">
                                        <form action="{{route('maliManager_getPaymentExcel')}}" method="post"
                                              class="d-inline-block">
                                            @csrf
                                            <input type="hidden" name="type" value="payment">
                                            <button class="btn btn-success">
                                                <i class="fa fa-file-excel-o mr-2"></i>
                                                خروجی اکسل
                                            </button>
                                        </form>

                                        <form action="{{route('maliManager_getPaymentExcel')}}" method="post"
                                              class="ml-2 d-inline-block">
                                            @csrf
                                            <input type="hidden" name="type" value="force_payment">
                                            <button class="btn btn-success">
                                                <i class="fa fa-file-excel-o mr-2"></i>
                                                خروجی اکسل جدول فروری
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing8 text-center">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>کد پروژه</th>
                                            <th>عنوان پروژه</th>
                                            <th>فاز</th>
                                            <th>تاریخ اتمام</th>
                                            <th>مبلغ قابل پرداخت(ریال)</th>
                                            <th>کارفرما</th>
                                            <th>ناظر</th>
                                            <th>وضعیت</th>
                                            <th class="w100">اقدام</th>
                                        </tr>
                                        </thead>
                                        @if(!$searched)
                                            @php $row = (($payments->currentPage() - 1) * $payments->perPage() ) + 1; @endphp
                                        @else
                                            @php $row = 1; @endphp
                                        @endif
                                        <tbody>
                                        @foreach($payments as $payment)
                                            @if(strlen($payment->title) > 110)
                                                @php
                                                    $dots = "...";
                                                @endphp
                                            @else
                                                @php
                                                    $dots = "";
                                                @endphp
                                            @endif
                                            <tr>
                                                <td>
                                                    {{convertToPersianNumber($row)}}
                                                </td>
                                                <td>
                                                    <span class="project_unique_code">
                                                        @if($payment->project_unique_code == null)
                                                            ---
                                                        @else
                                                            {{$payment->project_unique_code}}
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <p class="title_subject_standard">{{$payment->title}}</p>
                                                </td>
                                                <td>
                                                    @if($payment->phase_number == 0)
                                                        پیش پرداخت
                                                    @else
                                                        {{$payment->phase_number}}
                                                    @endif
                                                </td>
                                                <td>{{$payment->jalali_end_date}}</td>
                                                <td>
                                                    {{convertToPersianNumber(number_format($payment->price))}}
                                                </td>
                                                <td>
                                                    {{$payment->employer->name . ' ' . $payment->employer->family}}
                                                </td>
                                                <td>
                                                    {{$payment->supervisor->name . ' ' . $payment->supervisor->family}}
                                                </td>
                                                <td>
                                                    <span class="{{$payment->status_css}}">
                                                        <?= $payment->s_title ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <button onclick="open_my_modal({{$payment->payment_id}})"
                                                            class="btn btn-primary text-small">
                                                        <i class="fa fa-credit-card mr-2"></i>
                                                        بارگزاری سند مالی
                                                    </button>
                                                    @if($payment->status == 8 and $payment->f_status == 6 and intval($payment->phase_number) > 0)
                                                        <a href="{{route('maliManager_proceeding',['payment_id' => $payment->payment_id])}}"
                                                           class="btn btn-warning text-small">
                                                            <i class="fa fa-credit-card mr-2"></i>
                                                            صورتجلسه
                                                        </a>
                                                    @endif

                                                </td>
                                            </tr>
                                            @php $row++ @endphp
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @if(!$searched)
                                        {{$payments->links()}}
                                    @endif
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-12">
                                        <p class="alert alert-danger mb-0">
                                            موردی برای پرداخت یافت نشد
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal" id="my_modal">
        <div class="modal-dialog">
            <form action="{{route('maliManager_financial_Payments')}}" method="post" enctype="multipart/form-data"
                  class="modal-content">
            @csrf
            <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">پرداخت نهایی</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table text-center table-bordered">
                            <tr>
                                <th>پروژه :</th>
                                <td style="white-space: unset !important">
                                    <span id="tbl_project_name"></span>
                                </td>
                            </tr>
                            <tr>
                                <th>فاز :</th>
                                <td>
                                    <span id="tbl_project_phase"></span>
                                </td>
                            </tr>
                            <tr>
                                <th>مبلغ :</th>
                                <td>
                                    <span id="tbl_project_price"></span>
                                </td>
                            </tr>
                            <tr>
                                <th>نوع پرداخت :</th>
                                <td>
                                    <div class="fancy-radio">
                                        <label class="mr-5">
                                            <input id="check" name="type" value="1" type="radio" checked>
                                            <span class="radio_span"><i></i>چک</span>
                                        </label>
                                        <label>
                                            <input name="type" value="0" type="radio">
                                            <span class="radio_span"><i></i>حواله</span>
                                        </label>
                                    </div>

                                    <div id="check_info">
                                        <input oninput="check_btn_disable()" type="text" id="sayyad_code_val" name="sayyad_code_val"
                                               class="form-control  mb-2"
                                               placeholder="لطفا شماره 16 رقمی صیاد چک را وارد نمایید">
                                        <input type="text" id="check_code_val" name="check_code_val"
                                               oninput="check_btn_disable()" class="form-control" placeholder="لطفا شماره چک را وارد نمایید">

                                        <div class="mt-4">
                                            <label>تاریخ</label>
                                            <div id="start_date_errors"
                                                 class="error_validate text-danger"></div>
                                            <div>
                                                <i onclick="showDatePicker('#form_date_picker', this)"
                                                   id="start_date_calender" class="fa fa-calendar date_calender"></i>
                                                <span id="start_date_show_text">----------------------</span>
                                                <input type="hidden" id="start_date_inp" name="start_date_inp">
                                            </div>
                                            <div data-show="0" id="form_date_picker"
                                                 class="range-from-example my_dp"></div>
                                        </div>
                                    </div>
                                    <div id="cache_info" style="display: none">
                                        <input type="text" id="following_code_val" name="following_code_val"
                                               oninput="check_btn_disable()" class="form-control" placeholder="لطفا شماره پیگیری را وارد نمایید">
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" name="payment_id" id="payment_id_modal">
                    <button disabled="disabled" id="submit_pay_btn" type="submit" class="btn btn-success">
                        پرداخت
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">انصراف</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="alert_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">متوجه شدم</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('public-admin/assets/js/persian-date.min.js')}}"></script>
    <script src="{{asset('public-admin/assets/js/persian-datepicker.min.js')}}"></script>

    <script>
        var input_type = $("input[name=type][type=radio]");
        var check = $("#check");

        var following_code_val = $("#following_code_val");
        var check_info = $("#check_info");
        var chache_info = $("#cache_info");

        input_type.change(function () {
            if (check.is(':checked')) {
                check_info.show();
                chache_info.hide();
            } else {
                check_info.hide();
                chache_info.show();
            }

            check_btn_disable();
        });

        var submit_pay_btn = $("#submit_pay_btn");
        var pay_document = $("#pay_document");
        var check_code_val = $("#check_code_val");
        var sayyad_code_val = $("#sayyad_code_val");


        function check_btn_disable() {
            var enable = true;

            if(check.is(":checked")) {
                if (!check_code_val.val()) {
                    enable = false
                }
                // if (!sayyad_code_val.val()) {
                //     enable = false
                // }
            } else {
                if (!following_code_val.val()) {
                    enable = false
                }
            }

            submit_pay_btn.prop('disabled', !enable);
        }

        function open_my_modal(payment_id) {
            var my_modal = $('#my_modal');
            var alert_modal = $('#alert_modal');
            var url = "{{ route('maliManager_get_data') }}";
            var data = {
                _token: '{{csrf_token()}}',
                payment_id: payment_id,
            };
            $.post(
                url,
                data,
                function (result) {
                    var error = result['error'];
                    if (!error) {
                        var payment_result = result['payment'];
                        $('#tbl_project_name').text(payment_result['title']);
                        $('#tbl_project_phase').text(payment_result['phase_number']);
                        $('#tbl_project_price').text(payment_result['price']);
                        $('#payment_id_modal').val(payment_id);

                        my_modal.modal('show');
                    } else {
                        alert_modal.find('.modal-body').find('p').remove();
                        alert_modal.find('.modal-body').append('<p class="alert alert-danger">خطایی رخ داده است</p>');
                        alert_modal.modal('show');
                    }
                },
                'json'
            );
        }

        var project_unique_code = $(".project_unique_code");
        $.each(project_unique_code, function (i, val) {
            var txt = project_unique_code.eq(i).text();
            txt = txt.trim();
            if (txt === "---") {
                project_unique_code.eq(i).html('<p style="color: orangered; font-weight: bold">کد اختصاص نشده است</p>');
            } else {
                var txt_arr = txt.split(" ");
                var final = "<p class='pccp'><span class='pcc mt-3'>" + txt_arr[0] + "</span><span class='pcc persian_char_code'>" + txt_arr[1] + "</span><span class='pcc mt-3'>" + txt_arr[2] + "</span></p>";
                project_unique_code.eq(i).html(final);
            }

        })

        function showDatePicker(id, tag) {
            if ($(id).attr('data-show') === '1') {
                $(id).attr('data-show', '0');
                $(tag).removeAttr('class');
                $(tag).addClass('fa fa-calendar date_calender');
                $(id).fadeOut();
            } else {
                $(tag).removeAttr('class');
                $(tag).addClass('fa fa-times text-danger date_calender');
                $(id).attr('data-show', '1');
                $(id).fadeIn(250);
            }

            return true;
        }

        var start_date_show_text = $('#start_date_show_text');
        var start_date_inp = $('#start_date_inp');
        from = $(".range-from-example").persianDatepicker({
            inline: true,
            observer: true,
            altField: '.range-from-example-alt',
            format: "YYYY/MM/DD",
            navigator: {
                text: {
                    btnNextText: '+',
                    btnPrevText: '-',
                }
            },
            autoClose: true,
            initialValue: false,
            onSelect: function (unix) {
                from.touched = true;
                start_date_show_text.text(from.model.inputElement.value);
                start_date_inp.val(from.model.inputElement.value);
                from_selected_date = from.getState().selected.dateObject.State.gregorian;
                $("#start_date_calender").trigger('click');
            }
        });
    </script>
@endsection
