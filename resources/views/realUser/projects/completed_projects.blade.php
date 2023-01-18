@extends('realUser.layout.real_layout')
@section('title')
    پروژه های تکمیل شده
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
            margin-right: 10px;
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

        .actions_box {
            display: none;
            width: max-content;
            height: 66px;
            background: #fff;
            box-shadow: 0 0 15px rgb(0 0 0 / 20%);
            position: absolute;
            right: -232px;
            margin: auto;
            top: 27px;
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
                var final = "<p class='pccp'><span class='pcc mt-3'>" + txt_arr[0] + "</span><br><span class='pcc persian_char_code'>" + txt_arr[1] + "</span><br><span class='pcc mt-3'>" + txt_arr[2] + "</span></p>";
                project_unique_code.eq(i).html(final);
            }
        })

        function show_dots_boxes(tag) {
            var actions_box = document.getElementsByClassName('actions_box');
            setTimeout(function () {
                $(tag).next().toggleClass('show_ab');
            }, 150)
        }

        window.onclick = function (event) {
            var actions_box = document.getElementsByClassName('actions_box');

            if (event.target.matches('.actions_btn')) {
                return false;
            }

            if (!event.target.matches('.actions_btn') && !event.target.matches('.actions_box')) {
                for (var ac = 0; ac < actions_box.length; ac++) {
                    var x = actions_box[ac];
                    if (x.classList.contains('show_ab')) {
                        x.classList.remove('show_ab');
                    }
                }
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
                        <h1>پروژه ها</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">پروژه های تکمیل شده</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right hidden-xs">
                        <a href="{{route('legal_project_add')}}" class="btn btn-sm btn-success">
                            <i class="fa fa-plus mr-4"></i>
                            افزودن پروژه جدید
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-12">
                    <div class="card">
                        <div class="body">
                            <form method="post" action="{{route('real_project_completed_Search')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-3 mb-2">
                                        <div class="input-group">
                                            <input name="project_unique_code_search" type="text" class="form-control"
                                                   placeholder="لطفا کد پروژه را وارد کنید">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-3 mb-2">
                                        <div class="input-group">
                                            <input name="title" type="text" class="form-control"
                                                   placeholder="لطفا عنوان پروژه را وارد کنید">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-3 mb-2">
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
                                        <div class="col-12 col-sm-6 col-md-3 mb-2">
                                            <div class="col-12 mb-2">
                                                <button type="submit" class="btn btn-sm btn-primary btn-block">جستجو
                                                </button>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <a type="submit" href="{{route('real_project_completed')}}"
                                                   class="btn btn-sm btn-danger btn-block"
                                                   title="">حذف فیلتر
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-12 col-sm-6 col-md-3 mb-2">
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
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing8 text-center">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>کد پروژه</th>
                                            <th>عنوان پروژه</th>
                                            <th>کارفرما</th>
                                            <th>تاریخ پایان</th>
                                            <th>وظیفه</th>
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
                                            @if(strlen($project->subject) > 15)
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
                                                <td>
                                                    {{convertToPersianNumber($row)}}
                                                </td>
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
                                                    {{$project->employer->name}}
                                                    <br>
                                                    {{$project->employer->family}}
                                                </td>
                                                <td>
                                                    {{$project->end_date_jalali}}
                                                </td>
                                                <td class="progress_td">
                                                    <div
                                                        class="progress progress-xxs progress-transparent {{$project->color_css}} mb-0 mt-0">
                                                        <div class="my_tooltip">
                                                            <p class="mb-0">%{{$project->Percentage}}</p>
                                                            {{$project->second_title}}
                                                        </div>
                                                        <div class="progress-bar"
                                                             data-transitiongoal="{{$project->Percentage}}"
                                                             aria-valuenow="{{$project->Percentage}}"
                                                             style="width: {{$project->Percentage}}%;">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                <span class="{{$project->status_css}}">
                                                    <?= $project->s_title ?>
                                                </span>
                                                </td>
                                                <td class="position-relative">
                                                    <div onclick="show_dots_boxes(this)" class="actions_btn">
                                                        ...
                                                    </div>
                                                    <div class="actions_box">
                                                        @if($project->project_error->count())
                                                            <a href="{{route('legal_project_error_message', ['project' => $project->project_id])}}"
                                                               class="dis_a" title="پیام فوری">
                                                                <i class="fa fa-info-circle text-danger dis_i"></i>
                                                                <span class="span_title">
                                                                    پیام
                                                                    <br>
                                                                    فوری
                                                                </span>
                                                            </a>
                                                        @elseif($project->project_error->count() == 0)
                                                            <a href="#" class="my_a dis_a" title="پیام فوری">
                                                                <i class="fa fa-info-circle dis_i"></i>
                                                                <span class="span_title">
                                                                    پیام
                                                                    <br>
                                                                    فوری
                                                                </span>
                                                            </a>
                                                        @endif
                                                        @if($project->status == 2)
                                                            <a href="{{route('legal_contract_add', ['project_id' => $project->project_id])}}"
                                                               class="my_a dis_a" title="تنظیم قرارداد">
                                                                <i class="fa fa-tasks ml-2 text-danger dis_i"></i>
                                                                <span class="span_title">
                                                                    تنظیم
                                                                    <br>
                                                                    قرارداد
                                                                </span>
                                                            </a>
                                                        @else()
                                                            <a href="#"
                                                               class="my_a dis_a" title="تنظیم قرارداد">
                                                                <i class="fa fa-tasks ml-2 dis_i"></i>
                                                                <span class="span_title">
                                                                    تنظیم
                                                                    <br>
                                                                    قرارداد
                                                                </span>
                                                            </a>
                                                        @endif
                                                        @if($project->status == 7)
                                                            <a href="{{route('legal_contract_minot', ['project' => $project->project_id])}}"
                                                               class="my_a dis_a" title="امضا دیجیتال">
                                                                <i class="fa fa-edit ml-2 text-danger dis_i"></i>
                                                                <span class="span_title">
                                                                    امضا
                                                                    <br>
                                                                    دیجیتال
                                                                </span>
                                                            </a>
                                                        @else
                                                            <a href="#" class="my_a dis_a" title="امضا دیجیتال">
                                                                <i class="fa fa-edit ml-2 dis_i"></i>
                                                                <span class="span_title">
                                                                    امضا
                                                                    <br>
                                                                    دیجیتال
                                                                </span>
                                                            </a>
                                                        @endif
                                                        @if($project->status == 8)
                                                            <a href="{{route('legal_contract_signed_minot', ['project' => $project->project_id])}}"
                                                               class="my_a dis_a" title="نمایش مینوت">
                                                                <i class="fa fa-eye ml-2 text-dark dis_i"></i>
                                                                <span class="span_title">
                                                                    نمایش
                                                                    <br>
                                                                    مینوت
                                                                </span>
                                                            </a>
                                                        @else
                                                            <a href="#" class="my_a dis_a" title="نمایش مینوت">
                                                                <i class="fa fa-eye ml-2 dis_i"></i>
                                                                <span class="span_title">
                                                                    نمایش
                                                                    <br>
                                                                    مینوت
                                                                </span>
                                                            </a>
                                                        @endif
                                                        @if($project['reportable'])
                                                            <a href="{{route('legal_reports_details', ['project' => $project->project_id])}}"
                                                               class="my_a dis_a" title="ارسال گزارش">
                                                                <i class="fa fa-file-text ml-2 text-dark dis_i"></i>
                                                                <span class="span_title">
                                                                    ارسال
                                                                    <br>
                                                                    گزارش
                                                                </span>
                                                            </a>
                                                        @else
                                                            <a href="#" class="my_a dis_a" title="ارسال گزارش">
                                                                <i class="fa fa-file-text ml-2 dis_i"></i>
                                                                <span class="span_title">
                                                                    ارسال
                                                                    <br>
                                                                    گزارش
                                                                </span>
                                                            </a>
                                                        @endif
                                                    </div>
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
                                            پروژه ای یافت نشد
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
