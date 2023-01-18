@extends('tarhoBarname_manager.layout.tarhoBarname_layout')

@section('title')
    {{$period_detail['title']}}
@endsection

@section('css')
    <style>
        .light_version .table tr td, .light_version .table tr th {
            background: none;
        }

        .table.table-custom th {
            background: #07383e !important;
            border-top: none !important;
            border-radius: 0 !important;
            color: #fff;
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

        .actions_box {
            display: none;
            width: max-content;
            height: 60px;
            background: #fff;
            box-shadow: 0 0 15px rgb(0 0 0 / 20%);
            position: absolute;
            right: -94px;
            margin: auto;
            top: 31px;
            line-height: 44px;
            border-radius: 7px;
            padding: 0px 20px !important;
        }

        .actions_box.show_ab {
            display: block;
        }

        .actions_btn {
            cursor: pointer;
            font-size: 25px;
        }

        .fixed_on_scroll_res {
            position: absolute;
            left: 26px;
            top: 229px;
            z-index: 99999;
        }
    </style>
@endsection

@section('js')
    <script>
        $(window).scroll(function () {
            var scroll_top = $(window).scrollTop();
            var fixed_on_scroll_res = $("#fixed_on_scroll_res");
            if (scroll_top > 230) {
                fixed_on_scroll_res.addClass('fixed_on_scroll_res');
            } else {
                fixed_on_scroll_res.removeClass('fixed_on_scroll_res');
            }
        });

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

        var alert_modal = $("#alert_modal");

        function final_send(tag) {
            $(tag).prop('disabled', true);

            var record_ids = $('.record_id');

            var final = [];
            $.each(record_ids, function (i, val) {
                final.push({
                    phase_id: record_ids.eq(i).val(),
                    price: removeComma(record_ids.eq(i).next().val()),
                });
            });

            var url = '{{route('tarhoBarname_send_to_mali')}}';
            var data = {
                _token: '{{csrf_token()}}',
                items: JSON.stringify(final),
                period_id: {{$period_detail['id']}},
            };
            $.post(url, data, function (res) {
                if (res['error']) {
                    alert_modal.find('.modal-body').find('p').remove();
                    alert_modal.find('.modal-body').html('<p class="alert alert-danger">' + res['errorMsg'] + '</p>');
                    alert_modal.modal('show');

                } else {
                    alert_modal.find('.modal-body').find('p').remove();
                    alert_modal.find('.modal-body').html('<p class="alert alert-success">' + res['errorMsg'] + '</p>');
                    alert_modal.modal('show');
                }

                setTimeout(function () {
                    window.location.href = '{{route("tarhoBarname_financial_index")}}';
                    return false;
                }, 1900)
            }, 'json');
        }

        function calculate_prices(tag) {

            var this_val = parseInt(removeComma($(tag).val()));
            var remind = parseInt(removeComma($("#inp_bank").val()));
            var my_val_inp = $(".my_val_inp");

            if (this_val <= remind) {
                for (var i = 0; i < my_val_inp.length; i++) {
                    remind -= parseInt(removeComma(my_val_inp.eq(i).val()));
                }

                $("#show_remind_1").text(putComma(remind));
                $("#show_remind_2").text(putComma(remind));
            } else {
                alert_modal.find('.modal-body').find('p').remove();
                alert_modal.find('.modal-body').html('<p class="alert alert-danger">مبلغ وارد شده باید از موجودی بانک کمتر باشد.</p>');
                alert_modal.modal('show');

                $(tag).val(0);
                calculate_prices(tag);
            }

            setComma(tag);
        }
    </script>
@endsection

@section('content')
    <div id="main-content">
        <div class="container-fluid">

            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>{{$period_detail['title']}}</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item"><a href="{{route('tarhoBarname_payable_index')}}">کارتابل
                                        واریز ها</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{$period_detail['title']}}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div id="fixed_on_scroll_res" class="row clearfix">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table spacing8 table-hover table-custom">
                                <tr>
                                    <th>جمع کل</th>
                                    <th>موجودی بانک</th>
                                    <th>مانده</th>
                                </tr>

                                <tr>
                                    <td>
                                        {{@number_format($period_detail['total'])}}
                                        <input value="{{$period_detail['total']}}" type="hidden" id="inp_total">
                                        <input value="{{$period_detail['bank']}}" type="hidden" id="inp_bank">
                                    </td>

                                    <td>{{@number_format($period_detail['bank'])}}</td>

                                    <td>
                                       <span id="show_remind_2">
                                           {{@number_format($period_detail['bank'])}}
                                       </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h5 style="font-size: 12pt">
                                        {{$period_detail['title']}}
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="table-responsive mt-4">
                                        <table class="table spacing8 table-hover table-custom">
                                            <tr>
                                                <th>عنوان</th>
                                                <th>مقدار</th>
                                            </tr>
                                            @foreach($fields as $field)
                                                <tr>
                                                    <td>{{$field->title}}</td>
                                                    <td>{{$field->value}}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table spacing8 table-hover table-custom">
                                    <tr>
                                        <th style="width: 5px">#</th>
                                        <th>مرکز</th>
                                        <th>مجری</th>
                                        <th>ناظر</th>
                                        <th>موضوع</th>
                                        <th>فاز</th>
                                        <th>مبلغ کل</th>
                                        <th>مجموع پرداختی</th>
                                        <th>مانده کل پروژه</th>
                                        <th>مبلغ فاز</th>
                                        <th>مبلغ قابل پرداخت</th>
                                    </tr>

                                    @php $row = 1; @endphp
                                    @foreach($records as $record)
                                        <tr>
                                            <td>{{$row++}}</td>
                                            <td>{{$record['employer']['center_name']}}</td>
                                            <td>{{$record['user']['name'] . ' ' . $record['user']['family']}}</td>
                                            <td>{{$record['supervisor']['name'] . ' ' . $record['supervisor']['family']}}</td>
                                            <td>{{$record['title']}}</td>
                                            <td>
                                                @if($record['phase_number'] == 0)
                                                    پیش پرداخت
                                                @else
                                                    {{$record['phase_number']}}
                                                @endif
                                            </td>
                                            <td>{{@number_format($record['contract_cost'])}}</td>
                                            <td>{{@number_format($record['payed'])}}</td>
                                            <td>{{@number_format($record['reminding'])}}</td>
                                            <td>{{@number_format($record['phase_cost'])}}</td>
                                            <td>
                                                @if($record['sent_to_tarh'] == 1)
                                                    <input type="hidden" class="record_id"
                                                           value="{{$record['phase_id']}}">
                                                    <input oninput="calculate_prices(this)" type="text"
                                                           class="my_val_inp form-control bg-white text-dark text-center"
                                                           value="0">
                                                @else
                                                    0
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="9"></td>
                                        <th>جمع کل</th>
                                        <td>{{@number_format($period_detail['total'])}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="9"></td>
                                        <th>موجودی بانک</th>
                                        <td>{{@number_format($period_detail['bank'])}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="9"></td>
                                        <th>مانده</th>
                                        <td>
                                            <span class="show_remind_span" id="show_remind_1">
                                                {{@number_format($period_detail['bank'])}}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12 text-right">
                                    <button onclick="final_send(this)" class="btn btn-success">
                                        <i class="fa fa-check mr-2"></i>
                                        ارسال برای مالی
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
