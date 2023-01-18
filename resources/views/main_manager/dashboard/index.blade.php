@extends('main_manager.layout.main_manager_layout')

@section('title',"داشبورد مدیرعامل")

@section('css')
    <style>
        .top_h4_title {
            font-size: 12pt;
            margin-top: 14px;
            font-weight: bold;
        }

        .my_img_left {
            width: 100%;
            max-width: 130px;
            border-radius: 15px !important;
            box-shadow: 0 0 15px rgba(0, 0, 0, .2);
            padding: 0;
        }

        .mw-200px {
            max-width: 150px !important;
        }

        .stroke-green {
            stroke: #00a523;
        }

        .stroke-red {
            stroke: #ff0001;
        }

        .stroke-yellow {
            stroke: #ffe200;
        }

        .my_under_donut li {
            display: block;
            width: 100%;
        }

        .my_under_donut li a {
            display: block;
            width: 100%;
            height: 100%;
            padding: 5px 7px;
        }

        .my_under_donut li a i {
            display: inline-block;
            width: 25px;
            height: 12px;
            border-radius: 3px;
            float: right;
            margin-top: 4px;
            margin-left: 7px;
        }

        .my_under_donut li a span {
            display: inline-block;
            float: right;
            font-size: 10pt;
            color: #333;
        }

        .weekly_tbl td {
            vertical-align: top !important;
            border: 1px solid #eee !important;
        }

        .weekly_tbl_span {
            font-size: 11pt;
            color: #333;
            font-weight: bold;
        }

        #barChart2_loader {
            position: absolute;
            z-index: 9999;
            right: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .7);
            display: none;
        }

        #barChart2_loader img {
            position: absolute;
            right: 0;
            left: 0;
            top: 0;
            bottom: 0;
            margin: auto;
            width: 120px;
            height: auto;
        }

        #chart2Data_span {
            position: absolute;
            right: 0;
            left: 0;
            top: 0;
            bottom: 0;
            margin: auto;
            font-size: 20pt;
            font-weight: bold;
            z-index: 9999;
            max-width: max-content;
            max-height: max-content;
            color: #959595;
        }

        #chart2Data_company_data {
            display: none;
            text-align: center;
        }

        #chart2Data_company_img {
            width: 75px;
            height: auto;
            margin: 0 auto 10px auto;
        }
    </style>

@endsection

@section('js')
    <script>
        function getCookie(user) {
            var cookieArr = document.cookie.split(";");
            for (var i = 0; i < cookieArr.length; i++) {
                var cookiePair = cookieArr[i].split("=");
                if (user == cookiePair[0].trim()) {
                    return decodeURIComponent(cookiePair[1]);
                }
            }
            return null;
        }

        if (getCookie('cookie_alert') == null) {
            alert('سلام ، لطفا ابتدا ctrl+F5 را زده و سپس کار خود را شروع کنید');
            var cookie_alert = document.cookie = "cookie_alert = 1 ";
        }
    </script>
    <script src="{{asset('public-admin/assets/bundles/morrisscripts.bundle.js')}}"></script>
    <script>
        //Bar chart 2
        var dataBarChart2 = {
            labels: [],

            series: []
        };
        var chart_bar_2 = new Chartist.Bar('#chart_bar_2', dataBarChart2, {
            height: "325px",
            showLabels: true,
            plugins: [
                Chartist.plugins.tooltip({
                    display: true,
                    currency: '',
                }),

                // Chartist.plugins.legend({
                //     display: true,
                //     position: 'bottom'
                // }),
            ]
        });

        // donut chart
        var dataDonut1 = {
            {{--labels: [--}}
                {{--    @foreach($color_chart_data['labels'] as $label)--}}
                {{--        "{{$label}}",--}}
                {{--    @endforeach--}}
                {{--],--}}

            series: [
                // each columns data
                    @foreach($color_chart_data['data'] as $key => $num)
                {
                    value: {{$num}},
                    className: "{{$color_chart_data['class'][$key]}}"
                },
                @endforeach
            ]
        };
        var chart_donut_1 = new Chartist.Pie('#chart_donut_1', dataDonut1, {
            donut: true,
            height: "300px",
            showLabels: true,
            plugins: [
                Chartist.plugins.tooltip({
                    display: true,
                    currency: '',
                }),

                // Chartist.plugins.legend({
                //     display: true,
                //     position: 'bottom'
                // }),
            ]
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

        function select_employer_chart(tag) {
            var barChart2_loader = $("#barChart2_loader");
            var chart2Data_company_data = $('#chart2Data_company_data');
            var chart2Data_company_img = $('#chart2Data_company_img');
            var chart2Data_company_total_value = $('#chart2Data_company_total_value');
            var chart2Data_company_total_count = $('#chart2Data_company_total_count');
            var chart2Data_company_total_payed = $('#chart2Data_company_total_payed');
            var chart2Data_company_total_remind = $('#chart2Data_company_total_remind');

            barChart2_loader.slideDown(390);

            var employer_id = $(tag).find('option:selected').val();

            if (employer_id) {
                var url = "{{route('main_manager_getBarChart2Data')}}";
                var data = {
                    _token: '{{csrf_token()}}',
                    employer_id: employer_id
                };

                $.post(url, data, function (res) {

                    $('#chart2Data_span').hide();
                    chart2Data_company_data.show();

                    chart2Data_company_img.attr('src', res['employer_info']['img']);
                    chart2Data_company_total_value.text(putComma(res['employer_info']['total_value']));
                    chart2Data_company_total_count.text(res['employer_info']['total_count']);
                    chart2Data_company_total_payed.text(putComma(res['employer_info']['total_payed']));
                    chart2Data_company_total_remind.text(putComma(res['employer_info']['total_remind']));

                    chart_bar_2.update(
                        {
                            labels: res['labels'],
                            series: res['values'],
                        }
                    );

                    barChart2_loader.slideUp(390);
                }, 'json');
            } else {
                $('#chart2Data_span').show();
                chart2Data_company_data.hide();

                chart_bar_2.update(
                    {
                        labels: [],
                        series: [],
                    }
                );

                barChart2_loader.slideUp(390);
            }
        }
    </script>
@endsection

@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>داشبورد</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">داشبورد</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row clearfix justify-content-between">
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="body">
                            <div class="d-flex align-items-center">
                                <div class="icon-in-bg bg-blush text-white rounded-circle"><i
                                        class="fa fa-briefcase"></i></div>
                                <div class="ml-4">
                                    <span>پروژه های جاری</span>
                                    <h4 class="mb-0 font-weight-medium top_h4_title">{{$total_opened_project_count}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="body">
                            <div class="d-flex align-items-center">
                                <div class="icon-in-bg bg-azura text-white rounded-circle"><i
                                        class="fa fa-dollar"></i></div>
                                <div class="ml-4">
                                    <span>کل مبالغ پروژه های جاری</span>
                                    <h4 class="mb-0 font-weight-medium top_h4_title">{{@number_format($total_opened_project_sum)}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="body">
                            <div class="d-flex align-items-center">
                                <div class="icon-in-bg bg-success text-white rounded-circle">
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="ml-4">
                                    <span>مبالغ تسویه شده</span>
                                    <h4 class="mb-0 font-weight-medium top_h4_title">{{@number_format($total_all_payed_sum)}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="body">
                            <div class="d-flex align-items-center">
                                <div class="icon-in-bg bg-danger text-white rounded-circle"><i
                                        class="fa fa-warning"></i></div>
                                <div class="ml-4">
                                    <span>کل مبالغ بدهی ها</span>
                                    <h4 class="mb-0 font-weight-medium top_h4_title">{{@number_format($total_have_to_pay_sum)}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="header">
                            <h2>وضعیت مالی پروژه ها بر اساس مجری</h2>
                            <ul class="header-dropdown dropdown">
                                <li>
                                    <a href="javascript:void(0);" class="full-screen">
                                        <i class="icon-frame"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="body position-relative">

                            <div id="barChart2_loader">
                                <img src="{{asset('placeholder/loader_gif.svg')}}">
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-3 col-lg-2">
                                    <div class="row">

                                        <div class="col-12">
                                            <label>انتخاب مجری</label>
                                            <select onchange="select_employer_chart(this)" class="form-control">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($chart_employers as $employer)
                                                    <option value="{{$employer['employer_id']}}">
                                                        {{$employer['co_name']}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12 mt-4" id="chart2Data_company_data">
                                            <img id="chart2Data_company_img">

                                            <p class="clearfix">
                                                <b class="float-left">ارزش کل پروژه ها:</b>
                                                <span class="float-right font-weight-bold" id="chart2Data_company_total_value"></span>
                                            </p>

                                            <p class="clearfix">
                                                <b class="float-left">تعداد کل پروژه ها:</b>
                                                <span class="float-right font-weight-bold" id="chart2Data_company_total_count"></span>
                                            </p>

                                            <p class="clearfix">
                                                <b class="float-left">تسویه شده:</b>
                                                <span class="float-right font-weight-bold" id="chart2Data_company_total_payed"></span>
                                            </p>

                                            <p class="clearfix">
                                                <b class="float-left">بدهی:</b>
                                                <span class="float-right font-weight-bold" id="chart2Data_company_total_remind"></span>
                                            </p>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-12 col-md-9 col-lg-10 position-relative">
                                    <span id="chart2Data_span">لطفا ابتدا مجری را انتخاب نمایید</span>
                                    <div id="chart_bar_2"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="header">
                            <h2>نمایش شکست هفتگی پروژه ها</h2>
                            <ul class="header-dropdown dropdown">
                                <li>
                                    <a href="javascript:void(0);" class="full-screen">
                                        <i class="icon-frame"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="body">
                            <div class="table-responsive">
                                <table class="weekly_tbl table table-hover table-custom spacing5">
                                    <tr>
                                        <td><b>شنبه</b></td>
                                        <td><b>یکشنبه</b></td>
                                        <td><b>دوشنبه</b></td>
                                        <td><b>سه شنبه</b></td>
                                        <td><b>چهارشنبه</b></td>
                                        <td><b>پنجشنبه</b></td>
                                        <td><b>جمعه</b></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            @foreach($weekly[0] as $project)
                                                <a href="{{route('tarhoBarname_contract_signed_minot', ['project' => $project['project_id']])}}">
                                                    <span class="weekly_tbl_span">
                                                        {{ intval($project['project_id'] + 1000) }}
                                                    </span>
                                                    <br>
                                                    <p class="{{$project->status_css}}">
                                                        <?= $project->s_title ?>
                                                    </p>
                                                </a>

                                                <hr>
                                            @endforeach
                                        </td>

                                        <td>
                                            @foreach($weekly[1] as $project)
                                                <a href="{{route('tarhoBarname_contract_signed_minot', ['project' => $project['project_id']])}}">
                                                    <span class="weekly_tbl_span">
                                                        {{ intval($project['project_id'] + 1000) }}
                                                    </span>
                                                    <br>
                                                    <p class="{{$project->status_css}}">
                                                        <?= $project->s_title ?>
                                                    </p>
                                                </a>

                                                <hr>
                                            @endforeach
                                        </td>

                                        <td>
                                            @foreach($weekly[2] as $project)
                                                <a href="{{route('tarhoBarname_contract_signed_minot', ['project' => $project['project_id']])}}">
                                                    <span class="weekly_tbl_span">
                                                        {{ intval($project['project_id'] + 1000) }}
                                                    </span>
                                                    <br>
                                                    <p class="{{$project->status_css}}">
                                                        <?= $project->s_title ?>
                                                    </p>
                                                </a>

                                                <hr>
                                            @endforeach
                                        </td>

                                        <td>
                                            @foreach($weekly[3] as $project)
                                                <a href="{{route('tarhoBarname_contract_signed_minot', ['project' => $project['project_id']])}}">
                                                     <span class="weekly_tbl_span">
                                                        {{ intval($project['project_id'] + 1000) }}
                                                    </span>
                                                    <br>
                                                    <p class="{{$project->status_css}}">
                                                        <?= $project->s_title ?>
                                                    </p>
                                                </a>

                                                <hr>
                                            @endforeach
                                        </td>

                                        <td>
                                            @foreach($weekly[4] as $project)
                                                <a href="{{route('tarhoBarname_contract_signed_minot', ['project' => $project['project_id']])}}">
                                                    <span class="weekly_tbl_span">
                                                        {{ intval($project['project_id'] + 1000) }}
                                                    </span>
                                                    <br>
                                                    <p class="{{$project->status_css}}">
                                                        <?= $project->s_title ?>
                                                    </p>
                                                </a>

                                                <hr>
                                            @endforeach
                                        </td>

                                        <td>
                                            @foreach($weekly[5] as $project)
                                                <a href="{{route('tarhoBarname_contract_signed_minot', ['project' => $project['project_id']])}}">
                                                    <span class="weekly_tbl_span">
                                                        {{ intval($project['project_id'] + 1000) }}
                                                    </span>
                                                    <br>
                                                    <p class="{{$project->status_css}}">
                                                        <?= $project->s_title ?>
                                                    </p>
                                                </a>

                                                <hr>
                                            @endforeach
                                        </td>

                                        <td>
                                            @foreach($weekly[6] as $project)
                                                <a href="{{route('tarhoBarname_contract_signed_minot', ['project' => $project['project_id']])}}">
                                                     <span class="weekly_tbl_span">
                                                        {{ intval($project['project_id'] + 1000) }}
                                                    </span>
                                                    <br>
                                                    <p class="{{$project->status_css}}">
                                                        <?= $project->s_title ?>
                                                    </p>
                                                </a>

                                                <hr>
                                            @endforeach
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="header">
                            <h2>کارفرما ها</h2>
                            <ul class="header-dropdown dropdown">
                                <li>
                                    <a href="javascript:void(0);" class="full-screen">
                                        <i class="icon-frame"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-custom spacing5">
                                    <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>نام پروژه</th>
                                        <th>نام مرکز</th>
                                        <th>مبلغ پروژه</th>
                                        <th>مبلغ پرداخت شده</th>
                                        <th>مبلغ بدهی</th>
                                    </tr>
                                    </thead>

                                    <tbody>


                                    @php $row = (($employer_projects->currentPage() - 1) * $employer_projects->perPage() ) + 1; @endphp

                                    @foreach($employer_projects as $employer)
                                        @php
                                            $name = $employer['name'] . ' ' . $employer['family'];
                                        @endphp
                                        <tr>
                                            <td>
                                                <span>{{$row}}</span>
                                            </td>

                                            <td>
                                                <p class="title_subject_standard mw-200px">{{$employer['title']}}</p>
                                            </td>

                                            <td>{{$employer['center_name']}}</td>

                                            <td>{{@number_format($employer['contract_cost'])}}</td>

                                            <td>{{@number_format($employer['payed'])}}</td>

                                            <td>{{@number_format($employer['reminding'])}}</td>
                                        </tr>

                                        @php
                                            $row++;
                                        @endphp
                                    @endforeach
                                    </tbody>
                                </table>


                                {{$employer_projects->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="header">
                            <h2>وضعیت تحویل پروژه مجری ها</h2>
                            <ul class="header-dropdown dropdown">
                                <li>
                                    <a href="javascript:void(0);" class="full-screen">
                                        <i class="icon-frame"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="body">
                            <div id="chart_donut_1" class="ct-chart"></div>

                            <ul class="my_under_donut">
                                <li>
                                    <a class="clearfix"
                                       href="{{route('mainManager_colorDonutChartDetail', ['color' => 'green'])}}">
                                        <i style="background: #00a523"></i>
                                        <span>پروژه های بدون دیر کرد</span>
                                    </a>
                                </li>

                                <li>
                                    <a class="clearfix"
                                       href="{{route('mainManager_colorDonutChartDetail', ['color' => 'yellow'])}}">
                                        <i style="background: #ffe200"></i>
                                        <span>کم تر از دو هفته دیر کرد</span>
                                    </a>
                                </li>

                                <li>
                                    <a class="clearfix"
                                       href="{{route('mainManager_colorDonutChartDetail', ['color' => 'red'])}}">
                                        <i style="background: #ff0001;"></i>
                                        <span>بیش از دو هفته دیر کرد</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
