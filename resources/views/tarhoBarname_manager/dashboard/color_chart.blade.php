@extends('tarhoBarname_manager.layout.tarhoBarname_layout')

@section('title')
    پروژه های
    @if($color_param == 'red')
        قرمز
    @elseif($color_param=='yellow')
        زرد
    @else
        سبز
    @endif
@endsection

@section('css')
    <style>
        .mw-200px {
            max-width: 150px !important;
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
    </style>
@endsection

@section('js')
    <script>
        var delete_contract_modal = $('#delete_contract_modal');
        var delete_project_modal = $('#delete_project_modal');

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

        function contract_delete_modal(project_id) {
            delete_contract_modal.find('#delete_contract_id').val(project_id);
            delete_contract_modal.modal('show');
        }

        function project_delete_modal(project_id) {
            delete_project_modal.find('#delete_project_id').val(project_id);
            delete_project_modal.modal('show');
        }

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
                        <h1>
                            پروژه های
                            @if($color_param == 'red')
                                قرمز
                            @elseif($color_param=='yellow')
                                زرد
                            @else
                                سبز
                            @endif
                        </h1>
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                پروژه های
                                @if($color_param == 'red')
                                    قرمز
                                @elseif($color_param=='yellow')
                                    زرد
                                @else
                                    سبز
                                @endif
                            </h2>
                            <ul class="header-dropdown dropdown">
                                <li>
                                    <a href="javascript:void(0);" class="full-screen">
                                        <i class="icon-frame"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            @if(!empty($projects))
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing8 text-center">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>کد پروژه</th>
                                            <th>عنوان پروژه</th>
                                            <th>کارفرما</th>
                                            {{--                                            <th>ناظر</th>--}}
                                            <th>تاریخ پایان پروژه</th>
                                            <th>مبلغ کل پروژه (ریال)</th>
                                            <th>وظیفه</th>
                                            <th>وضعیت</th>
                                            <th class="w100">عملیات</th>
                                        </tr>
                                        </thead>

                                        @php $row = 1; @endphp
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
                                            @if(strlen($project->p_title) >110)
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
                                                {{--                                                <td>--}}
                                                {{--                                                    {{$project->supervisor->name . ' ' . $project->supervisor->family}}--}}

                                                {{--                                                </td>--}}

                                                <td>
                                                    {{$project->end_date_jalali}}
                                                </td>
                                                <td class="text-center">
                                                    {{convertToPersianNumber(number_format($project->contract_cost))}}
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
                                                        <a href="{{route('tarhoBarname_contract_view',['project' => $project->project_id])}}"
                                                           class="active_color dis_a" title="مشاهده قرارداد">
                                                            <i class="fa fa-file-text-o dis_i"></i>
                                                            <span class="span_title">
                                                                   مشاهده
                                                                   <br>
                                                                    قرارداد
                                                                 </span>
                                                        </a>
                                                        @if($project->project_error->count())
                                                            <a href="{{route('tarhoBarname_error_message', ['project' => $project->project_id])}}"
                                                               class="active_color dis_a" title="پیام فوری">
                                                                <i class="fa fa-info-circle dis_i"></i>
                                                                <span class="span_title">
                                                                   پیام
                                                                   <br>
                                                                    فوری
                                                                 </span>
                                                            </a>
                                                        @elseif($project->project_error->count() == 0)
                                                            <a href="#" class="deactive_color dis_a"
                                                               title="پیام فوری">
                                                                <i class="fa fa-info-circle dis_i"></i>
                                                                <span class="span_title">
                                                                   پیام
                                                                   <br>
                                                                    فوری
                                                                 </span>
                                                            </a>
                                                        @endif
                                                        @if($project->rejected_by_main_manager == 1)
                                                            <a onclick="contract_delete_modal({{$project->project_id}})"
                                                               class="margin_icon text-danger dis_a" title="حذف قرارداد">
                                                                <i class="fa fa-trash dis_i"></i>
                                                                <span class="span_title">
                                                                   حذف
                                                                   <br>
                                                                    قرارداد
                                                                 </span>
                                                            </a>
                                                            <a onclick="project_delete_modal({{$project->project_id}})"
                                                               class="margin_icon text-danger dis_a" title="حذف پروژه">
                                                                <i class="fa fa-trash-o dis_i"></i>
                                                                <span class="span_title">
                                                                   حذف
                                                                   <br>
                                                                    پروژه
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

    <div class="modal" id="delete_contract_modal">
        <div class="modal-dialog">
            <form class="modal-content" method="post" action="{{route('tarhoBarname_contract_delete')}}">
            @csrf
            <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">حذف قرارداد</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    آیا از حذف قرارداد مطمئن هستید؟
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input id="delete_contract_id" type="hidden" name="delete_contract_id">
                    <button type="submit" class="btn btn-success">بله</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>
                </div>

            </form>
        </div>
    </div>
    <div class="modal" id="delete_project_modal">
        <div class="modal-dialog">
            <form class="modal-content" method="post" action="{{route('tarhoBarname_project_delete')}}">
            @csrf
            <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">حذف پروژه</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    آیا از حذف پروژه مطمئن هستید؟
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input id="delete_project_id" type="hidden" name="delete_project_id">
                    <button type="submit" class="btn btn-success">بله</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>
                </div>

            </form>
        </div>
    </div>
@endsection
