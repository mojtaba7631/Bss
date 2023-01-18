@extends('employer.layout.employer_layout')
@section('title',"داشبورد کارفرما")
@section('css')
    <style>
        .top_h4_title {
            font-size: 15pt;
            margin-top: 14px;
        }

        .my_img_left {
            width: 100%;
            max-width: 130px;
            border-radius: 15px !important;
            box-shadow: 0 0 15px rgba(0, 0, 0, .2);
            padding: 0;
        }
    </style>
@endsection

@section('js')
<script>
        function getCookie(user) {
            var cookieArr = document.cookie.split(";");
            for(var i = 0; i < cookieArr.length; i++) {
                var cookiePair = cookieArr[i].split("=");
                if(user == cookiePair[0].trim()) {
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
        // horizontal bar chart
        var dataHorizontalBar = {
            labels: [
                @foreach($contractors as $contractor)
                    @if ($contractor['type'] == 1)
                    "{{$contractor['co_name']}}",
                @endif
                @endforeach
            ],

            series: [
                // each columns data
                [
                        @foreach($getDataForChart1[0] as $num)
                    {
                        meta: "کل",
                        value: {{$num}},
                    },
                    @endforeach
                ],
                [
                        @foreach($getDataForChart1[1] as $num)
                    {
                        meta: "پرداخت شده",
                        value: {{$num}},
                    },
                    @endforeach
                ],
                [
                        @foreach($getDataForChart1[2] as $num)
                    {
                        meta: "بدهی",
                        value: {{$num}},
                    },
                    @endforeach
                ],
            ]
        };
        new Chartist.Bar('#chart_horizontal_1', dataHorizontalBar, {
            height: "300px",
            axisY: {
                offset: 100
            },
            plugins: [
                Chartist.plugins.tooltip({
                    currency: ' م ر ',
                })
            ]
        });

        // donut chart
        var dataDonut1 = {
            labels: [
                @foreach($getDataForChart2['labels'] as $label)
                    "{{$label}}",
                @endforeach
            ],

            series: [
                // each columns data
                [
                    @foreach($getDataForChart2['data'] as $num)
                    {{$num}},
                    @endforeach
                ],
            ]
        };
        var chart_donut_1 = new Chartist.Pie('#chart_donut_1', dataDonut1, {
            donut: true,
            height: "300px",
            showLabels: true,
            plugins: [
                Chartist.plugins.tooltip({
                    display: true,
                    currency: ' م ر ',
                }),

                Chartist.plugins.legend({
                    display: true,
                    position: 'bottom'
                }),
            ]
        });

        // horizontal bar chart
        var dataLine1 = {
            labels: [
                @foreach($contractors as $contractor)
                    @if ($contractor['type'] == 1)
                    "{{$contractor['co_name']}}",
                @endif
                @endforeach
            ],

            series: [
                // each columns data
                [
                        @foreach($getDataForChart1[0] as $num)
                    {
                        meta: "کل",
                        value: {{$num}},
                    },
                    @endforeach
                ],
                [
                        @foreach($getDataForChart1[1] as $num)
                    {
                        meta: "پرداخت شده",
                        value: {{$num}},
                    },
                    @endforeach
                ],
                [
                        @foreach($getDataForChart1[2] as $num)
                    {
                        meta: "بدهی",
                        value: {{$num}},
                    },
                    @endforeach
                ],
            ]
        };
        new Chartist.Line('#chart_line_1', dataLine1, {
            height: "300px",
            axisY: {
                offset: 100
            },
            plugins: [
                Chartist.plugins.tooltip({
                    currency: ' م ر ',
                })
            ]
        });
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
            <div class="row clearfix">
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="body">
                            <div class="d-flex align-items-center">
                                <div class="icon-in-bg bg-indigo text-white rounded-circle"><i
                                        class="fa fa-briefcase"></i></div>
                                <div class="ml-4">
                                    <span>تعداد پروژه های جاری</span>
                                    <h4 class="mb-0 font-weight-medium top_h4_title">{{$total_opened_project_count}} عدد</h4>
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
                                        class="fa fa-credit-card"></i></div>
                                <div class="ml-4">
                                    <span>مبالغ پروژه های جاری</span>
                                    <h4 class="mb-0 font-weight-medium top_h4_title">{{@number_format($total_opened_project_sum)}} ریال</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="body">
                            <div class="d-flex align-items-center">
                                <div class="icon-in-bg bg-orange text-white rounded-circle">
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="ml-4">
                                    <span>تعداد پروژه های تسویه شده</span>
                                    <h4 class="mb-0 font-weight-medium top_h4_title">{{$total_closed_project_count}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="body">
                            <div class="d-flex align-items-center">
                                <div class="icon-in-bg bg-success text-white rounded-circle"><i
                                        class="fa fa-lock"></i></div>
                                <div class="ml-4">
                                    <span>مبالغ پروژه های تسویه شده</span>
                                    <h4 class="mb-0 font-weight-medium top_h4_title">{{@number_format($total_closed_project_sum)}} ریال</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-12">
                    <div class="card">
                        <div class="header">
                            <h2>نمودار مبلغ پروژه های محول شده</h2>
                            <ul class="header-dropdown dropdown">
                                <li>
                                    <a href="javascript:void(0);" class="full-screen">
                                        <i class="icon-frame"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="body">
                            <div id="chart_horizontal_1" class="ct-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="header">
                            <h2>نمودار مبلغ پروژه های محول شده</h2>
                            <ul class="header-dropdown dropdown">
                                <li>
                                    <a href="javascript:void(0);" class="full-screen">
                                        <i class="icon-frame"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="body">
                            <div id="chart_line_1" class="ct-chart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="header">
                            <h2>نمودار سهم شرکت ها از همیاران پیشرفت</h2>
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


                        <div class="body">
                            <table class="table table-hover table-custom spacing5">
                                <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th class="text-left">نام و نام خانوادگی</th>
                                    <th>تعداد پروژه ها</th>
                                    <th>مبلغ پروژه ها (ریال)</th>
                                </tr>
                                </thead>

                                <tbody>

                                @foreach($contractors as $contractor)
                                    @if($contractor['type'] == 1)
                                        <tr>
                                            <td>
                                                <span>01</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avtar-pic w35 bg-red" data-toggle="tooltip"
                                                         data-placement="top"
                                                         title="" data-original-title="{{$contractor['co_name']}}">

                                                        <img class="w-100" src="{{asset($contractor['image'])}}">

                                                    </div>
                                                    <div class="ml-3">
                                                        <a href="#" title="">
                                                            {{$contractor['co_name']}}
                                                        </a>
                                                        <p class="mb-0">
                                                            {{$contractor['mobile']}}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>{{$contractor['project_count']}}</td>

                                            <td>{{@number_format($contractor['project_total_sum'])}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
