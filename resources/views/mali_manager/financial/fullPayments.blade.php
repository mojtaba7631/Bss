@extends('mali_manager.layout.mali_layout')
@section('title')
    جدول واریز شده ها
@endsection
@section('css')
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
            /*float: left;*/
            margin: 5px;
        }
    </style>
@endsection
@section('js')
    <script>
        var submit_pay_btn = $("#submit_pay_btn");
        var pay_document = $("#pay_document");
        pay_document.change(function () {
            if (pay_document.val()) {
                submit_pay_btn.prop('disabled', false)
            } else {
                submit_pay_btn.prop('disabled', true)
            }
        });

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
                var final = "<p class='pccp'><span class='pcc mt-3'>" + txt_arr[0] + "</span><br><span class='pcc persian_char_code'>" + txt_arr[1] + "</span><br><span class='pcc mt-3'>" + txt_arr[2] + "</span></p>";
                project_unique_code.eq(i).html(final);
            }

        })

    </script>
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>جدول پرداخت شده ها</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">جدول پرداخت شده ها</li>
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
                            <form method="post" action="{{route('maliManager_fullPayments_search')}}">
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
                                            <a type="submit" href="{{route('maliManager_fullPayments')}}"
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
                                            <th>سند مالی</th>
                                            <th>صورتجلسه</th>
                                            <th>دستور پرداخت</th>
                                        </tr>
                                        </thead>
                                        @if(!$searched)
                                            @php $row = (($payments->currentPage() - 1) * $payments->perPage() ) + 1; @endphp
                                        @else
                                            @php $row = 1; @endphp
                                        @endif
                                        <tbody>
                                        @foreach($payments as $payment)
                                            @if(strlen($payment->subject) > 10)
                                                @php
                                                    $dots = "...";
                                                @endphp
                                            @else
                                                @php
                                                    $dots = "";
                                                @endphp
                                            @endif
                                            @if(strlen($payment->title) > 110)
                                                @php
                                                    $dots_t = "...";
                                                @endphp
                                            @else
                                                @php
                                                    $dots_t = "";
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
                                                    {{$payment->employer->name}}
                                                    <br>
                                                    {{$payment->employer->family}}
                                                </td>
                                                <td>
                                                    {{$payment->supervisor->name}}
                                                    <br>
                                                    {{$payment->supervisor->family}}
                                                </td>
                                                <td>
                                                    @if($payment->payment_file != '')
                                                        <a href="{{route('maliManager_download_file',['peyment' => $payment->payment_id])}}"
                                                           class="btn btn-warning">
                                                            <i class="fa fa-download mr-2"></i>
                                                            دانلود سندمالی
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($payment->pa_status == 1 and intval($payment->phase_number) > 0)
                                                        <a href="{{route('maliManager_proceeding',['payment_id' => $payment->payment_id])}}"
                                                           class="btn btn-info text-small">
                                                            <i class="fa fa-credit-card mr-2"></i>
                                                            صورتجلسه
                                                        </a>
                                                    @else
                                                        ندارد
                                                    @endif
                                                </td>
                                                <td>
{{--                                                    @if($payment->date_receipt != null )--}}
                                                        <a href="{{route('maliManager_payment_command',['payment_id' => $payment->payment_id])}}"
                                                           class="btn btn-info text-small">
                                                            <i class="fa fa-credit-card mr-2"></i>
                                                            دستور پرداخت
                                                        </a>
{{--                                                        @else--}}
{{--                                                        ------------------}}
{{--                                                    @endif--}}
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
                                <td>
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
                                <td colspan="2">
                                    <label>بارگزاری سند مالی</label>
                                    <input id="pay_document" name="payment_src" type="file" class="dropify">
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <p class="alert alert-warning">بارگزاری سند مالی الزامی است</p>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" name="payment_id" id="payment_id_modal">
                    <button disabled="disabled" id="submit_pay_btn" type="submit" class="btn btn-success">پرداخت
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
