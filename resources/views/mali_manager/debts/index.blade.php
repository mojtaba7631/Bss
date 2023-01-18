@extends('mali_manager.layout.mali_layout')
@section('title')
    کارتابل بدهی ها
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

        .margin_icon {
            margin-right: 10px;
            border: none;
            background: none;
            cursor: pointer;
        }
    </style>
@endsection
@section('js')
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
        })
    </script>
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>کارتابل بدهی ها</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">کارتابل بدهی ها</li>
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
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <a class="btn btn-success" href="{{route('maliManager_getRemindingExcel')}}">
                                        <i class="fa fa-download mr-2"></i>
                                        <span>دانلود اکسل بدهی</span>
                                    </a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-custom spacing8 text-center">
                                    <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th class="text-left">مجری</th>
                                        <th>تعداد کل پروژه</th>
                                        <th>تعداد پروژه های جاری</th>
                                        <th>تعداد پروژه های تسویه شده</th>
                                        <th>تعهد بابت مجموع قراردادها (ریال)</th>
                                        <th>بدهی بابت مجموع قراردادها (ریال)</th>
                                    </tr>
                                    </thead>
                                        @if(!$searched)
                                            @php $row = (($user_info->currentPage() - 1) * $user_info->perPage() ) + 1; @endphp
                                        @else
                                            @php $row = 1; @endphp
                                        @endif
                                    <tbody>
                                    @foreach($user_info as $user)

                                        <tr>
                                            <td>
                                                {{convertToPersianNumber($row)}}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avtar-pic w35 bg-red" data-toggle="tooltip"
                                                         data-placement="top"
                                                         title="" data-original-title="">

                                                        <img class="w-100" src="{{asset($user->image)}}">

                                                    </div>
                                                    <div class="ml-3">
                                                        @if($user->type === 1)
                                                            <p>{{$user->co_name}}</p>
                                                        @elseif($user->type === 0)
                                                            <p>{{$user->name . ' ' . $user->family}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{$user->pro_count}}
                                            </td>
                                            <td>
                                                {{$user->current_projects}}
                                            </td>
                                            <td>
                                                {{$user->settled_projects}}
                                            </td>
                                            <td>
                                                <p>{{number_format($user->total_price)}}</p>
                                            </td>
                                            <td>
                                                <p>{{number_format($user->payment_total)}}</p>
                                            </td>
                                        </tr>
                                        @php $row++ @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                    @if(!$searched)
                                        {{$user_info->links()}}
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
