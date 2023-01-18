@extends('mali_manager.layout.mali_layout')
@section('title')
    جدول قابل پرداخت
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
            text-align: center;
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

        .margin_icon {
            margin-right: 10px;
            border: none;
            background: none;
            cursor: pointer;
        }

        #show_total_selected, #show_selected_count {
            font-size: 12pt;
            font-weight: bold;
        }

        #show_selected_count {
            margin-right: 15px;
        }

        .removeField {
            font-size: 12pt;
            cursor: pointer;
            background: transparent;
            border: none;
            outline: 0 !important;
            height: fit-content;
            line-height: 36px;
        }
    </style>
@endsection

@section('js')
    <script>
        function putComma(Number) {
            Number += '';
            Number = Number.replace(',', '');
            Number = Number.replace(',', '');
            Number = Number.replace(',', '');
            Number = Number.replace(',', '');
            Number = Number.replace(',', '');
            Number = Number.replace(',', '');
            x = Number.split('.');
            y = x[0];
            z = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(y))
                y = y.replace(rgx, '$1' + ',' + '$2');
            return y + z;
        }

        function removeComma(Number) {
            return Number.replace(/,/g, '');
        }

        function setComma(tag) {
            var value = $(tag).val();
            value = removeComma(value);
            value = putComma(value);
            $(tag).val(value);
            return true;
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

        var show_total_selected = $("#show_total_selected");
        var show_selected_count = $("#show_selected_count");
        var check_the_phase = $(".check_the_phase");
        var final_submit = $("#final_submit");
        var alert_modal = $("#alert_modal");

        function change_check_the_phase() {
            var total = 0;
            var selected_count = 0;
            $.each(check_the_phase, function (i, tag) {
                if (check_the_phase.eq(i).is(':checked')) {
                    total += parseInt(check_the_phase.eq(i).attr('data-price'));
                    selected_count++;
                }
            });

            show_total_selected.text('جمع مبالغ انتخاب شده: ' + putComma(total));
            show_selected_count.text('تعداد موارد انتخاب شده: ' + selected_count);

            if (selected_count > 0) {
                final_submit.prop('disabled', false);
            } else {
                final_submit.prop('disabled', true);
            }
        }

        function send_it() {
            var selected_count = 0;
            var period_name = $("#period_name");
            if (!period_name.val()) {
                alert_modal.find('.modal-body').find('p').remove();
                alert_modal.find('.modal-body').html('<p class="alert alert-danger">عنوان دوره پرداخت الزامی است</p>');
                alert_modal.modal('show');
                return false;
            }

            final_submit.prop('disabled', true);
            var phases_id = [];
            $.each(check_the_phase, function (i, tag) {
                if (check_the_phase.eq(i).is(':checked')) {
                    phases_id.push(parseInt(check_the_phase.eq(i).val()));
                    selected_count++;
                }
            });

            if (selected_count < 1) {
                alert_modal.find('.modal-body').find('p').remove();
                alert_modal.find('.modal-body').html('<p class="alert alert-danger">انتخاب حداقل یک مورد الزامی است الزامی است</p>');
                alert_modal.modal('show');
                return false;
            }

            var period_bank = removeComma($("#period_bank").val());

            var period_total = 0;
            $.each(check_the_phase, function (i, tag) {
                if (check_the_phase.eq(i).is(':checked')) {
                    period_total += parseInt(check_the_phase.eq(i).attr('data-price'));
                }
            });

            var field_topics = $(".field_topic");
            var field_values = $(".field_value");
            var fields = [];
            $.each(field_topics, function (i, tag) {
                fields.push({
                    title: field_topics.eq(i).val(),
                    value: field_values.eq(i).val(),
                });
            });

            var url = '{{route('maliManager_payable_add')}}';
            var data = {
                _token: '{{csrf_token()}}',
                phases_id: phases_id,
                period_name: period_name.val(),
                bank: parseInt(period_bank),
                total: parseInt(period_total),
                fields: JSON.stringify(fields),
            };
            $.post(url, data, function (res) {
                console.log(res);
                final_submit.prop('disabled', false);

                if (!res['error']) {
                    alert_modal.find('.modal-body').find('p').remove();
                    alert_modal.find('.modal-body').html('<p class="alert alert-success">'+ res['errorMsg'] +'</p>');
                    alert_modal.modal('show');

                    setTimeout(function () {
                        // location.reload();
                    }, 2000)
                }
            }, 'json');
        }

        function add_new_field() {
            var f = '<div class="new_field_row row mt-4">' +
                ' <div class="col-12 col-sm-6 col-md-4">' +
                '<label>عنوان ستون</label>' +
                '<input type="text" class="form-control field_topic">' +
                '</div>' +
                '<div class="col-12 col-sm-6 col-md-4">' +
                '<label>مقدار ستون</label>' +
                '<input type="text" class="form-control field_value">' +
                '</div>' +
                '<button onclick="removeField(this)" class="fa fa-times-circle text-danger removeField mt-4"></button>' +
                '</div>';

            var field_before = $("#fields_before");

            field_before.parent().find('#final_submit_row').before(f);
        }

        function removeField(tag) {
            $(tag).parent().remove();
        }
    </script>
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>جدول قابل پرداخت</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">جدول قابل پرداخت</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
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
                                            <th>مرکز</th>
                                            <th>مجری</th>
                                            <th>ناظر</th>
                                            <th>موضوع</th>
                                            <th>مبلغ کل</th>
                                            <th>مجموع پرداختی</th>
                                            <th>مانده</th>
                                            <th>فاز</th>
                                            <th>مبلغ فاز</th>
                                            <th>انتخاب</th>
                                        </tr>
                                        </thead>
                                        @php $row = 1; @endphp
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
                                                    {{$payment['employer']['center_name']}}
                                                </td>

                                                <td>
                                                    {{$payment['user']['name']}}
                                                    <br>
                                                    {{$payment['user']['family']}}
                                                </td>

                                                <td>
                                                    {{$payment['supervisor']['name']}}
                                                    <br>
                                                    {{$payment['supervisor']['family']}}
                                                </td>

                                                <td>
                                                    <p class="title_subject_standard">{{$payment->title}}</p>
                                                </td>

                                                <td>
                                                    <p class="title_subject_standard">{{@number_format($payment->contract_cost)}}</p>
                                                </td>

                                                <td>
                                                    {{convertToPersianNumber(number_format($payment['payed']))}}
                                                </td>

                                                <td>
                                                    {{convertToPersianNumber(number_format($payment['reminding']))}}
                                                </td>

                                                <td>
                                                    @if($payment->phase_number == 0)
                                                        پیش پرداخت
                                                    @else
                                                        {{$payment->phase_number}}
                                                    @endif
                                                </td>

                                                <td>
                                                    <p class="title_subject_standard">{{@number_format($payment->phase_cost)}}</p>
                                                </td>

                                                <td>
                                                    <input data-price="{{$payment->phase_cost}}"
                                                           onchange="change_check_the_phase()" class="check_the_phase"
                                                           type="checkbox" value="{{$payment->phase_id}}">
                                                </td>
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
                                            موردی برای پرداخت یافت نشد
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($payments->all())
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <p id="show_total_selected" class="d-inline-block">
                                            جمع مبالغ انتخاب شده: 0
                                        </p>
                                        <p id="show_selected_count" class="d-inline-block">
                                            تعداد موارد انتخاب شده: 0
                                        </p>
                                    </div>
                                </div>

                                <div id="fields_before" class="row mt-4">
                                    <div class="col-12 col-md-6">
                                        <label>عنوان دوره پرداخت</label>
                                        <input class="form-control" id="period_name" type="text">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label>موجودی بانک دوره پرداخت</label>
                                        <input oninput="setComma(this)" class="form-control" id="period_bank" type="text">
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <button onclick="add_new_field()" class="btn btn-success mt-4 w-100">
                                            <i class="fa fa-plus mr-2"></i>
                                            افزودن فیلد
                                        </button>
                                    </div>
                                </div>

                                <div id="final_submit_row" class="row mt-4">
                                    <div class="col-12 col-md-2">
                                        <button id="final_submit" disabled="disabled" onclick="send_it()"
                                                class="btn btn-success mt-4 w-100">
                                            <i class="fa fa-send mr-2"></i>
                                            ارسال به طرح و برنامه
                                        </button>
                                    </div>
                                </div>

                                <div class="row mb-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal" id="alert_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal body -->
                <div class="modal-body"></div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">متوجه شدم</button>
                </div>
            </div>
        </div>
    </div>
@endsection
