@extends('employer.layout.employer_layout')
@section('title')
    لیست پروژه ها
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
            color: #17C2D7 !important;
            margin-right: 10px;
        }

        .my_a i {
            color: #17C2D7 !important;
        }

        a:hover {
            color: #007bff !important;
        }

        .info_a {
            color: red !important;
            margin-right: 10px;
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
                        <h1>پروژه ها</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">لیست پروژه ها</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                @if(!empty($projects->all()))
                    <div class="col-12">
                        <div class="card">
                            <div class="body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   placeholder="لطفا کد پروژه را وارد کنید">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   placeholder="لطفا عنوان پروژه را وارد کنید">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   placeholder="لطفا کارفرما را انتخاب کنید">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <a href="javascript:void(0);" class="btn btn-sm btn-primary btn-block"
                                           title="">جستجو</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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
                                            <th>مجری</th>
                                            <th>تاریخ ایجاد شده</th>
                                            <th>وظیفه</th>
                                            <th>وضعیت</th>
                                            <th class="w100">عملیات</th>
                                        </tr>
                                        </thead>
                                        @php $row = (($projects->currentPage() - 1) * $projects->perPage() ) + 1; @endphp
                                        <tbody>
                                        @foreach($projects as $project)
                                            @if(strlen($project->subject) > 35)
                                                @php
                                                    $dots = "...";
                                                @endphp
                                            @else
                                                @php
                                                    $dots = "";
                                                @endphp
                                            @endif
                                            @if(strlen($project->p_title) > 100)
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
                                                    @if($project->user->type == 1)
                                                        {{$project->user->co_name}}
                                                    @elseif($project->user->type == 0)
                                                        {{$project->user->name . ' ' . $project->user->family}}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$project->created_at_jalali}}
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
                                                <td>
                                                    <a href="{{route('employer_project_view', ['project' => $project->project_id])}}"
                                                       class="active_color " title="مشاهده پروژه">
                                                        <i class="fa fa-file-text-o"></i>
                                                    </a>
{{--                                                    @if($project->project_error->count())--}}
{{--                                                        <a href="{{route('employer_project_error_message', ['project' => $project->project_id])}}"--}}
{{--                                                           class="" title="علل عدم تایید پروژه">--}}
{{--                                                            <i class="fa fa-info-circle text-danger"></i>--}}
{{--                                                        </a>--}}
{{--                                                    @elseif($project->project_error->count() == 0)--}}
{{--                                                        <a href="#"--}}
{{--                                                           class="deactive_color" title="علل عدم تایید پروژه">--}}
{{--                                                            <i class="fa fa-info-circle"></i>--}}
{{--                                                        </a>--}}
{{--                                                    @endif--}}
                                                </td>
                                            </tr>
                                            @php $row++ @endphp
                                        @endforeach
                                        </tbody>
                                    </table>
                                    {{$projects->links()}}
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
