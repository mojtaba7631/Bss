@extends('admin.layout.admin_layout')
@section('title')
    لیست تمام قراردادها
@endsection
@section('css')
    <style>
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
                        <h1>لیست تمام قراردادها</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">لیست تمام قراردادها</li>
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
                                            <input type="text" class="form-control" placeholder="لطفا کد پروژه را وارد کنید">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="لطفا عنوان پروژه را وارد کنید">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="لطفا کارفرما را انتخاب کنید">
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
                                            <th>کارفرما</th>
                                            <th>ناظر</th>
                                            <th>تاریخ اتمام</th>
                                            <th>وظیفه</th>
                                            <th>وضعیت</th>
                                            <th class="w100">اقدام</th>
                                        </tr>
                                        </thead>
                                        @php $row = (($projects->currentPage() - 1) * $projects->perPage() ) + 1; @endphp
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
                                                <td class="progress_td_subject position-relative">
                                                    {{mb_substr($project->p_title, 0, 110) . " " . $dots_t}}
                                                    @if($dots_t == "...")
                                                        <div class="my_tooltip_subject">
                                                            <p class="mb-0">{{$project->p_title}}</p>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$project->employer->name . ' ' . $project->employer->family}}
                                                </td>
                                                <td>
                                                    {{$project->supervisor->name . ' ' . $project->supervisor->family}}
                                                </td>
                                                <td>
                                                    {{$project->end_date_jalali}}
                                                </td>
                                                <td class="progress_td">
                                                    <div class="progress progress-xxs progress-transparent {{$project->color_css}} mb-0 mt-0">
                                                        <div class="my_tooltip">
                                                            <p class="mb-0">%{{$project->Percentage}}</p>
                                                            {{$project->second_title}}
                                                        </div>
                                                        <div class="progress-bar" data-transitiongoal="{{$project->Percentage}}"
                                                             aria-valuenow="{{$project->Percentage}}" style="width: {{$project->Percentage}}%;"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="{{$project->status_css}}">
                                                        <?= $project->s_title ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{route('admin_contract_signed_minot',['project' => $project->project_id])}}"
                                                       class="active_color" title="مشاهده قرارداد">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
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
                                            قراردادی یافت نشد
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
