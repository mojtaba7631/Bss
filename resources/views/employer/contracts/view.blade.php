@php
    $i=1;
@endphp
@extends('employer.layout.employer_layout')
@section('title')
    مشاهده قرارداد
@endsection
@section('css')
    <style>
        .btn_fix_danger {
            position: fixed;
            top: 300px;
            bottom: 0;
            margin: auto;
            height: 50px;
            left: 0;
            width: 200px;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            transition: .3s;
        }

        .btn_fix_green {
            position: fixed;
            top: 250px;
            bottom: 0;
            margin: auto;
            height: 50px;
            left: 0;
            z-index: 100;
            width: 200px;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        #not_active_error {
            display: none;
        }

        .btn_fix_green:hover, .btn_fix_danger:hover {
            color: #fff !important;
        }
    </style>
@endsection
@section('js')
    <script>
        var e_myModal = $('#e_myModal');
        var accept_modal = $('#accept_modal');
        var not_active_error = $('#not_active_error');
        var confirm_contract = $('#confirm_contract');

        function e_make_not_active() {
            let message = $("#message_textarea").val();
            if (!message) {
                not_active_error.find('p').text('علت عدم تایید الزامی است.');
                not_active_error.slideDown();
                setTimeout(function () {
                    not_active_error.slideUp();
                }, 5000);
                return false;
            }
            let _token = "{{ csrf_token() }}";
            e_myModal.modal('hide');
            $.post("{{route('employer_contract_notActive')}}",
                {
                    project: '{{$project->project_id}}',
                    message: message,
                    _token: _token
                },
                function (result) {
                    if (!result['error']) {

                        swal(result['errorMsg']);

                        setTimeout(function () {
                            window.location.href = '{{route("employer_contract_index")}}';
                        }, 2000)
                    }
                }
            );
        }

        function active_btn(tag) {
            confirm_contract.prop('disabled', !$(tag).val());
        }

        function e_open_modal(project_id) {
            e_myModal.find('#e_project_id').val(project_id);
            e_myModal.modal('show');
        }

        function accept_open_modal() {
            accept_modal.modal('show');
        }

        $(".btn_fix_danger, .btn_fix_green").hover(
            function () {
                $(this).css('width', "250px");
            }, function () {
                $(this).css('width', "200px");
            }
        );
    </script>
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>قرارداد ها</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">قرارداد ها</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right hidden-xs">
                        <a href="{{route('employer_contract_index')}}" class="btn btn-sm btn-primary" title="">
                            <i class="fa fa-arrow-right mr-4"></i>
                            بازگشت به قراردادها
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @if(file_exists($user_img) and !is_dir($user_img))
                                @php
                                    $src = $user_img;
                                @endphp
                            @else
                                @php
                                    $src = 'placeholder/user_placeholder.png';
                                @endphp
                            @endif
                            <img class="img_logo_view" src="{{asset($src)}}">
                            {{$project->p_title}}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 p-5">
                                    <div class="row special_border">
                                        <h5 class="h_title">برآورد زمان و هزینه</h5>
                                        <div class="col-12">
                                            <label><b>تاریخ شروع پروژه :</b></label>
                                            <p class="d-inline-block"> {{convertToPersianNumber($project_start_date_jalali)}}</p>
                                        </div>
                                        <div class="col-12">
                                            <label><b>تاریخ پایان پروژه :</b></label>
                                            <p class="d-inline-block"> {{convertToPersianNumber($project_end_date_jalali)}}</p>
                                        </div>
                                        <div class="col-12">
                                            <label><b>مبلغ کل قرارداد پروژه :</b></label>
                                            <p class="d-inline-block">
                                                {{convertToPersianNumber(number_format($project->contract_cost))}} ریال
                                            </p>
                                        </div>
                                        <div class="col-12">
                                            <label><b>پیش پرداخت :</b></label>
                                            <p class="d-inline-block">
                                                @if($project->prepayment == 0)
                                                    ندارد
                                                @else
                                                    {{convertToPersianNumber(number_format($project->prepayment))}} ریال
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row special_border mt-5">
                                        <h5 class="h_title">شرح خدمات پروژه</h5>
                                        <div class="col-12">
                                            {!! $project->service_description !!}
                                        </div>
                                    </div>
                                    <div class="row special_border mt-5">
                                        <h5 class="h_title">خروجی های مورد انتظار</h5>
                                        <div class="col-12">
                                            {!! $project->required_outputs !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive p-15">
                                <table class="table table-bordered spacing8 attachment_tbl text-center">
                                    <thead>
                                    <tr style="box-shadow: none !important;">
                                        <th style="width: 100px">محور</th>
                                        <th>موضوع</th>
                                        <th>تاریخ شروع فاز</th>
                                        <th>تاریخ پایان فاز</th>
                                        <th>هزینه بخشی</th>
                                        <th>هزینه کل</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($phases as $key => $phase)
                                        @if($key > 0 || intval($project->prepayment) == 0)
                                            <tr style="box-shadow: none !important;">
                                                <td colspan="2" class="text-center">
                                                    <b>فاز</b><span> </span>{{convertToPersianNumber($phase->phase_number)}}
                                                    <b> پروژه :</b>
                                                    {{$phase->description}}
                                                </td>
                                                <td>
                                                    {{convertToPersianNumber($phase->start_date_jalali)}}
                                                </td>
                                                <td>
                                                    {{convertToPersianNumber($phase->end_date_jalali)}}
                                                </td>
                                                <td>
                                                    {{convertToPersianNumber(number_format($phase->cost))}} ریال
                                                </td>

                                                @if($key == 0 and intval($project->prepayment) == 0)
                                                    <td rowspan="{{count($phases)}}">
                                                        {{convertToPersianNumber(number_format($project->contract_cost))}}
                                                        ریال
                                                    </td>
                                                @endif

                                            </tr>

                                        @else
                                            <tr style="box-shadow: none !important;">
                                                <td colspan="2" class="text-center">
                                                    <b>پیش پرداخت</b>

                                                    {{$phase->description}}
                                                </td>

                                                <td colspan="2">
                                                    {{convertToPersianNumber($phase->start_date_jalali)}}
                                                </td>

                                                <td>
                                                    {{convertToPersianNumber(number_format($phase->cost))}} ریال
                                                </td>

                                                <td rowspan="{{count($phases)}}">
                                                    {{convertToPersianNumber(number_format($project->contract_cost))}}
                                                    ریال
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="col-12">
                                <button type="submit" onclick="accept_open_modal({{$project->id}})"
                                        class="btn btn-success mt-4  btn_fix_green text-white">تایید
                                    قرارداد
                                </button>
                                <button id="e_inp_err" onclick="e_open_modal({{$project->id}})"
                                        class="btn btn-danger mt-4 btn_fix_danger text-white"
                                        type="submit" style="color: white">عدم تایید قرارداد
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div class="modal" id="e_myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">عدم تایید پروژه</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div id="not_active_error" class="row">
                        <div class="col-12">
                            <p class="alert alert-danger">

                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <textarea id="message_textarea" name="e_inp_err" rows="4"
                                      placeholder="لطفا دلیل عدم تایید را بنویسید"
                                      class="form-control mt-2 w-100"></textarea>
                        </div>
                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" value="{{$project->project_id}}" name="e_project_id">
                    <button onclick="e_make_not_active()" type="button" class="btn btn-success">بله</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>
                </div>

            </div>
        </div>
    </div>


{{--    <!-- The Accept Modal -->--}}
{{--    <div class="modal" id="accept_modal">--}}
{{--        <div class="modal-dialog">--}}
{{--            <form class="modal-content" method="post" action="{{route('employer_verify')}}">--}}
{{--            @csrf--}}
{{--            <!-- Modal Header -->--}}
{{--                <div class="modal-header">--}}
{{--                    <h4 class="modal-title">تایید پروژه</h4>--}}
{{--                    <button type="button" class="close" data-dismiss="modal">&times;</button>--}}
{{--                </div>--}}

{{--                <!-- Modal body -->--}}
{{--                <div class="modal-body">--}}
{{--                    <select onchange="active_btn(this)" class="form-select form-control w-100" name="supervisor_id"--}}
{{--                            id="multiselect1">--}}
{{--                        <option value="">لطفا برای تایید پروژه ناظر را انتخاب کنید</option>--}}
{{--                        @foreach($users as $user)--}}
{{--                            <option--}}
{{--                                value="{{$user->id}}">{{$user->name . ' ' . $user->family}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                    <div id="supervisors_errors" class="error_validate text-danger"></div>--}}
{{--                </div>--}}

{{--                <!-- Modal footer -->--}}
{{--                <div class="modal-footer">--}}
{{--                    <input type="hidden" value="{{$project->project_id}}" name="accept_project_id">--}}
{{--                    <button id="confirm_contract" type="submit" class="btn btn-success" disabled="disabled">ثبت</button>--}}
{{--                    <button type="button" class="btn btn-danger" data-dismiss="modal">انصراف</button>--}}
{{--                </div>--}}

{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}

    <!-- The Accept Modal -->
    <div class="modal" id="accept_modal">
        <div class="modal-dialog">
            <form class="modal-content" method="post" action="{{route('employer_verify')}}">
            @csrf
            <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">تایید پروژه</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    آیا قرارداد را تایید می فرمایید؟
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" value="{{$project->project_id}}" name="accept_project_id">
                    <button type="submit" class="btn btn-success">بله</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>

                </div>

            </form>
        </div>
    </div>
@endsection
