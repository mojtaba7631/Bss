@extends('mali_manager.layout.mali_layout')

@section('title')
 جدول واریز
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
        var input_type = $("input[name=type][type=radio]");
        var check = $("#check");
        var following_code_txt = $("#following_code_txt");
        var following_code_val = $("#following_code_val");

        input_type.change(function () {
            if (check.is(':checked')) {
                following_code_txt.text("شماره چک :");
            } else {
                following_code_txt.text("شماره پیگیری :");
            }
        });

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
                        <h1>جدول واریز</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">جدول واریز</li>
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
                                            <input value="" name="project_unique_code_search" type="text" class="form-control"
                                                   placeholder="لطفا کد پروژه را وارد کنید">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                                        <div class="input-group">
                                            <input value="" name="title" type="text" class="form-control"
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
                            <div class="card">
                                @if(!empty($projectsGroupByProject))
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
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-custom spacing8 text-center">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>کد پروژه</th>
                                                <th>مجری</th>
                                                <th>ناظر</th>
                                                <th>عنوان پروژه</th>
                                                <th>قیمت کل</th>
                                                <th>مجموع پرداختی</th>
                                                <th>مانده</th>
                                                <th>وضعیت</th>
                                                {{--                                            <th class="w100">مشاهده جزئیات</th>--}}
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @php
                                                $row = 1;
                                            @endphp
                                            @foreach($projectsGroupByProject as $project)
                                                @if(strlen($project['subject']) > 10)
                                                    @php
                                                        $dots = "...";
                                                    @endphp
                                                @else
                                                    @php
                                                        $dots = "";
                                                    @endphp
                                                @endif
                                                @if(strlen($project['p_title']) > 55)
                                                    @php
                                                        $dots_t = "...";
                                                    @endphp
                                                @else
                                                    @php
                                                        $dots_t = "";
                                                    @endphp
                                                @endif
                                                <tr>
                                                    <td> {{convertToPersianNumber($row)}}</td>
                                                    <td>
                                                <span class="project_unique_code">
                                                    @if($project['project_unique_code'] == null)
                                                        ---
                                                    @else
                                                        {{$project['project_unique_code']}}
                                                    @endif
                                                </span>
                                                    </td>

                                                    <td>
                                                        {{$project['user_info_name']}}
                                                    </td>

                                                    <td>
                                                        {{$project['supervisor']}}
                                                    </td>

                                                    <td>
                                                        <a href="{{route('maliManager_financial_detail',['project'=>$project['project_id']])}}">
                                                            <p class="title_subject_standard">{{$project['p_title'] }}</p>
                                                        </a>
                                                    </td>

                                                    <td>
                                                        {{convertToPersianNumber(number_format($project['total_price']))}}
                                                    </td>

                                                    <td>
                                                        {{convertToPersianNumber(number_format($project['payed']))}}
                                                    </td>

                                                    <td>
                                                        {{convertToPersianNumber(number_format($project['reminding']))}}
                                                    </td>

                                                    <td>
                                                     <span class="{{$project['status_css']}}">
                                                         <?= $project['s_title'] ?>
                                                     </span>
                                                    </td>
                                                    {{--                                                <td>--}}
                                                    {{--                                                    <a href="{{route('tarhoBarname_financial_detail',['project'=>$project['project_id']])}}"--}}
                                                    {{--                                                       class="my_a" title="جزئیات پرداخت">--}}
                                                    {{--                                                        <i class="fa fa-eye"></i>--}}
                                                    {{--                                                    </a>--}}
                                                    {{--                                                </td>--}}
                                                </tr>
                                                @php $row++ @endphp
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="alert alert-danger mb-0">
                                                موردی یافت نشد
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
                                    <label id="following_code_txt">شماره چک :</label>
                                    <input required type="text" id="following_code_val" name="following_code_val" class="form-control">
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
