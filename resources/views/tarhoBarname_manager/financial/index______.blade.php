@extends('tarhoBarname_manager.layout.tarhoBarname_layout')
@section('title')
    جدول پرداخت
@endsection
@section('css')
    <style>
        .light_version .table tr td, .light_version .table tr th {
            background: none;
        }

        .light_version .table.table-custom tbody tr:nth-child(odd) {
            background: #e1f3ff !important;
        }

        .my_a {
            cursor: pointer;
            color: #948f8f !important;
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
                            <form method="post" action="{{route('tarhoBarname_financial_search')}}">
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
                                    @if($searched)
                                        <div class="col-12 col-sm-6 col-md-2 mb-2">
                                            <button type="submit" class="btn btn-sm btn-primary btn-block">جستجو
                                            </button>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-2 mb-2">
                                            <a type="submit" href="{{route('tarhoBarname_financial_index')}}"
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
                            @if(!empty($projects->all()))
                                <div class="row">
                                    <div class="col-12">
                                        <form action="{{route('tarhoBarname_getPaymentExcel')}}" method="post"
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
                                            <th>عنوان پروژه</th>
                                            <th>موضوع پروژه</th>
                                            <th>فاز</th>
                                            <th>قیمت</th>
                                            <th>مجری</th>
                                            <th>کارفرما</th>
                                            <th>ناظر</th>
                                            <th>فایل گزارش</th>
                                            <th>وضعیت</th>
                                            <th class="w100">اقدام</th>
                                        </tr>
                                        </thead>
                                        @if(!$searched)
                                            @php $row = (($projects->currentPage() - 1) * $projects->perPage() ) + 1; @endphp
                                        @else
                                            @php $row = 1; @endphp
                                        @endif
                                        <tbody>
                                        @foreach($projects as $project)
                                            @if(strlen($project->subject) > 10)
                                                @php
                                                    $dots = "...";
                                                @endphp
                                            @else
                                                @php
                                                    $dots = "";
                                                @endphp
                                            @endif
                                            @if(strlen($project->p_title) > 110)
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
                                                    @if($project->project_unique_code == null)
                                                        ---
                                                    @else
                                                        {{$project->project_unique_code}}
                                                    @endif
                                                </span>
                                                </td>
                                                <td>
                                                    <p class="title_subject_standard">{{$project->p_title}}</p>
                                                </td>
                                                <td>
                                                    <p class="title_subject_standard">{{$project->subject}}</p>
                                                </td>
                                                <td>
                                                    @if($project->phase_number == 0)
                                                        پیش پرداخت
                                                    @else
                                                        {{$project->phase_number}}

                                                    @endif
                                                </td>
                                                <td>
                                                    {{convertToPersianNumber(number_format($project->cost))}}
                                                </td>
                                                <td>
                                                    @if($project['user_info_name']->type == 1)
                                                        {{$project['user_info_name']->co_name}}
                                                    @elseif($project['user_info_name']->type == 0)
                                                        {{$project['user_info_name']->ceo_name . ' ' . $project['user_info_name']->ceo_family}}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$project->employer->name . ' ' . $project->employer->family}}
                                                </td>
                                                <td>
                                                    {{$project->supervisor->name . ' ' . $project->supervisor->family}}
                                                </td>
                                                <td>
                                                    @if($project->file_src != '')
                                                        <a href="{{route('tarhoBarname_reports_download_file',['report' => $project->report_id])}}"
                                                           class="btn btn-warning">
                                                            <i class="fa fa-download mr-2"></i>
                                                            دانلود فایل گزارش فاز {{$project->phase_number}}
                                                        </a>
                                                    @else
                                                        ندارد
                                                    @endif
                                                </td>
                                                <td>
                                                     <span class="{{$project->status_css}}">
                                                         <?= $project->s_title ?>
                                                     </span>
                                                </td>
                                                <td>
                                                    <a href="{{route('tarhoBarname_financial_detail',['project'=>$project->project_id])}}"
                                                       class="my_a" title="جزئیات پرداخت">
                                                        <i class="fa fa-calculator"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @php $row++ @endphp
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @if(!$searched)
                                        {{$projects->links()}}
                                    @endif
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
@endsection
