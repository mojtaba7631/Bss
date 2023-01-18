@extends('legalUser.layout.legal_layout')
@section('title',"داشبورد مجری حقوقی")

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

        });

        // donut chart
        var dataDonut1 = {
            labels: [
                @foreach($getDataForChart['labels'] as $label)
                    "{{$label}}",
                @endforeach
            ],

            series: [
                // each columns data
                [
                    @foreach($getDataForChart['data'] as $num)
                    {{$num}},
                    @endforeach
                ],
            ]
        };
        var chart_donut_1 = new Chartist.Pie('#chart_donut_1', dataDonut1, {
            height: "300px",
            showLabels: true,
            plugins: [
                Chartist.plugins.tooltip({
                    display: true,
                    currency: 'پروژه ',
                }),

                Chartist.plugins.legend({
                    display: true,
                    position: 'bottom'
                }),
            ]
        });

        // monthly_price_chart
        var dataHorizontalBar = {
            labels: [
                @foreach($monthly_price_chart as $label)
                    "{{$label['monthName']}}",
                @endforeach
            ],

            series: [
                // each columns data
                [
                        @foreach($monthly_price_chart as $item)
                    {
                        meta: "پرداخت شده ها",
                        value: {{$item['payed']}},
                    },
                    @endforeach
                ],
                [
                        @foreach($monthly_price_chart as $item)
                    {
                        meta: "قابل پرداخت",
                        value: {{$item['waiting_for_pay']}},
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
                                    <h4 class="mb-0 font-weight-medium top_h4_title">{{$total_opened_project_count}}
                                        عدد</h4>
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
                                    <h4 class="mb-0 font-weight-medium top_h4_title">{{@number_format($total_opened_project_sum)}}
                                        ریال</h4>
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
                                    <h4 class="mb-0 font-weight-medium top_h4_title">{{@number_format($total_closed_project_sum)}}
                                        ریال</h4>
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
                            <h2>نمودار دریافت مبلغ</h2>
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
                            <h2>پروژه های من</h2>
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
                                <table class="table table-hover table-custom spacing5">
                                    <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th class="text-left">عنوان پروژه</th>
                                        <th>مبلغ پروژه (ریال)</th>
                                        <th>تاریخ پایان</th>
                                        <th>وضعیت</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    @php $row = 1; @endphp
                                    @foreach($projects as $project)
                                        <tr>
                                            <td>
                                                <span>{{$row}}</span>
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ml-3">
                                                        <p class="mb-0">
                                                        <span class="project_unique_code">
                                                            @if($project->project_unique_code == null)
                                                                ---
                                                            @else
                                                                {{$project->project_unique_code}}
                                                            @endif
                                                        </span>
                                                        </p>
                                                        @if($project['p_status'] < 7)
                                                            <p style="font-size: 11pt; margin: 15px 15px 5px" class="title_subject_standard">
                                                                {!! $project['p_title'] !!}
                                                            </p>
                                                        @else
                                                            <a class="title_subject_standard" href="{{route('legal_contract_signed_minot', ['project' => $project->project_id])}}">
                                                                {{$project['p_title']}}
                                                            </a>
                                                        @endif

                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                {{@number_format($project['contract_cost'])}}
                                            </td>

                                            <td>
                                                {{$project['end_date_jalali']}}
                                            </td>
                                            <td>
                                            <span class="{{$project->status_css}}">
                                                {!! $project['s_title'] !!}
                                            </span>
                                            </td>
                                        </tr>
                                        @php $row++; @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="header">
                            <h2>نمودار بر اساس وضعیت</h2>
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
        </div>
    </div>
@endsection
