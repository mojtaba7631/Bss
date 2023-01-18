@extends('main_manager.layout.main_manager_layout')
@section('title')
    قراردادهای تایید شده
@endsection
@section('css')
    <style>

        .margin_icon {
            margin-right: 10px;
            border: none;
            background: none;
            cursor: pointer;
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
            height: 60px;
            background: #fff;
            box-shadow: 0 0 15px rgb(0 0 0 / 20%);
            position: absolute;
            right: -140px;
            margin: auto;
            top: 26px;
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

                <div class="col-12">
                    <div class="card">
                        <div class="body">
                            <form method="post" action="{{route('mainManager_contract_accept_search')}}">
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
                                            <select id="status" class="form-select form-control " name="status"
                                                    aria-label="Default select example ">
                                                <option selected value="0">لطفا وضعیت را انتخاب کنید</option>
                                                @foreach($projects_status as $project_status)

                                                    <option value="{{$project_status->id}}">
                                                        {{$project_status->title}}
                                                    </option>
                                                @endforeach

                                                @foreach($phases_status as $phase_status)

                                                    <option value="{{$phase_status->id}}">
                                                        {{$phase_status->title}}
                                                    </option>
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
                                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                                        <div class="input-group">
                                            <select id="user" class="form-select form-control" name="user"
                                                    aria-label="Default select example">
                                                <option selected value="0">لطفا مجری را انتخاب کنید</option>
                                                @foreach($users as $user)

                                                    @if($user->type == 0)
                                                        <option
                                                            @if(isset($search_info['user']) and $search_info['user'] == $user->userId) selected
                                                            @endif value="{{$user->userId}}">
                                                            {{$user->name . ' ' . $user->family}}
                                                        </option>
                                                    @elseif($user->type == 1)
                                                        <option
                                                            @if(isset($search_info['user']) and $search_info['user'] == $user->userId) selected
                                                            @endif  value="{{$user->userId}}">
                                                            {{$user->co_name}}
                                                        </option>
                                                    @endif
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
                                            <a type="submit" href="{{route('mainManager_Accept_contract')}}"
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
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing8 text-center tbl_font_size">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>کد پروژه</th>
                                            <th>عنوان پروژه</th>
                                            <th>کارفرما</th>
                                            <th>ناظر</th>
                                            <th>مجری</th>
                                            <th>تاریخ اتمام</th>
                                            <th>مبلغ کل</th>
                                            <th>پرداخت شده</th>
                                            <th>باقیمانده</th>
                                            <th>وظیفه</th>
                                            <th>وضعیت</th>
                                            <th class="w100">عملیات</th>
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
                                                @if(!empty($project->supervisor))
                                                    <td>
                                                        {{$project->supervisor->name}}
                                                        <br>
                                                        {{$project->supervisor->family}}
                                                    </td>
                                                @else
                                                    <td>
                                                        -
                                                    </td>
                                                @endif
                                                <td>
                                                    {{$project->name_info}}
                                                </td>
                                                @if(!empty($project->supervisor))
                                                    <td>
                                                        {{$project->end_date_jalali}}
                                                    </td>
                                                @else
                                                    <td>
                                                        -
                                                    </td>
                                                @endif

                                                <td>
                                                    {{@number_format($project->contract_cost)}}
                                                </td>
                                                <td>
                                                    {{@number_format($project->payed)}}
                                                </td>
                                                <td>
                                                    {{@number_format($project->reminding)}}
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
                                                             style="width: {{$project->Percentage}}%;"></div>
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
                                                        <a href="{{route('mainManager_download_file',['project' => $project->project_id])}}"
                                                           class="active_color dis_a" title="دانلود پروپوزال">
                                                            <i class="fa fa-download dis_i"></i>
                                                            <span class="span_title">
                                                            دانلود
                                                          <br>
                                                            پروپوزال
                                                         </span>
                                                        </a>
                                                        @if($project->status > 4 and $project->status !=12)
                                                            <a href="{{route('mainManager_contract_signed_minot',['project' => $project->project_id,'fl'=> true])}}"
                                                               class="active_color dis_a" title="مشاهده مینوت">
                                                                <i class="fa fa-eye dis_i"></i>
                                                                <span class="span_title">
                                                            مشاهده
                                                          <br>
                                                            مینوت
                                                         </span>
                                                            </a>
                                                        @else
                                                            <a href="#" class="deactive_color dis_a">
                                                                <i class="fa fa-eye dis_i"></i>
                                                                <span class="span_title">
                                                            مشاهده
                                                          <br>
                                                            مینوت
                                                         </span>
                                                            </a>
                                                        @endif
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
            <form class="modal-content" method="post" action="{{route('main_manager_contract_delete')}}">
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
            <form class="modal-content" method="post" action="{{route('main_manager_project_delete')}}">
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
