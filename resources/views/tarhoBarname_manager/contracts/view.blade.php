@php
    $i=1;
@endphp
@extends('tarhoBarname_manager.layout.tarhoBarname_layout')
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
        .btn_fix_green:hover, .btn_fix_danger:hover {
            color: #fff !important;
        }

        * {
            font-family: "web-shabnam", sans-serif !important;
        }
    </style>
@endsection
@section('js')
    <script>
        var t_myModal = $('#t_myModal');
        var accept_modal = $('#accept_modal');
        var confirm_contract = $('#confirm_contract');

        function t_make_not_active() {
            let message = $("#t_inp_err").val();
            let _token = "{{ csrf_token() }}";
            t_myModal.modal('hide');
            $.post("{{route('tarhoBarname_notActive',['project'=>$project->id])}}",
                {
                    message,
                    _token
                },
                function (result) {
                    if (!result['error']) {

                        swal(result['errorMsg']);

                        console.log(result);
                        setTimeout(function () {
                            window.location.href = '{{route("tarhoBarname_contract_index")}}';
                        }, 2000)
                    }
                }
            );
        }

        function t_open_modal(project_id) {

            t_myModal.find('#t_project_id').val(project_id);
            t_myModal.modal('show');
        }

        var t_myModal_sign = $('#t_myModal_sign');

        function t_make_not_active_sign() {
            let message = $("#t_inp_err_sign").val();
            let _token = "{{ csrf_token() }}";
            t_myModal_sign.modal('hide');
            $.post("{{route('tarhoBarname_notActive',['project'=>$project->id])}}",
                {
                    message,
                    _token
                },
                function (result) {
                    if (!result['error']) {

                        swal(result['errorMsg']);

                        console.log(result);

                        {{--setTimeout(function () {--}}
                        {{--    window.location.href = '{{route("tarhoBarname_contract_index")}}';--}}
                        {{--}, 2000)--}}
                    }
                }
            );
        }
        $(".btn_fix_danger, .btn_fix_green").hover(
            function () {
                $(this).css('width', "250px");
            }, function () {
                $(this).css('width', "200px");
            }
        );

        function active_btn(tag) {
            confirm_contract.prop('disabled', !$(tag).val());
        }


        function accept_open_modal(project_id) {
            accept_modal.find('#project_id').val(project_id);
            accept_modal.modal('show');
        }
        function t_open_modal_sign(project_id) {

            t_myModal_sign.find('#t_project_id_sign').val(project_id);
            t_myModal_sign.modal('show');

        }

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
                         {{$project->title}}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 p-5">
                                <div class="row special_border">
                                    <h5 class="h_title">برآورد زمان و هزینه</h5>
                                    <div class="col-12">
                                        <label><b>کارفرما :</b></label>
                                        <p class="d-inline-block">{{$project->employer->name . ' ' . $project->employer->family}}</p>
                                    </div>
                                    <div class="col-12">
                                        <label><b>ناظر :</b></label>
                                        <p class="d-inline-block">تعیین نشده است</p>
                                    </div>
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
                                    <div class="col-12">
                                        <label><b>فایل پروپوزال :</b></label>
                                        <p class="d-inline-block">
                                            @if(isset($project->file))
                                                <a class="btn btn-info" href="{{url($project->file)}}">دانلود فایل
                                                    پروپوزال اولیه</a>
                                            @else
                                                <span>ندارد</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <label><b>توضیحات پروژه :</b></label>
                                        <p class="d-inline-block">
                                            {!! $project->comment !!}
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
                                    @if($key>0 || intval($project->prepayment) == 0)
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
                            @if($project->status == 5)
                                <div class="col-md-6">
                                        <div class="col-12">
                                            <button type="submit" onclick="accept_open_modal({{$project->id}})"
                                                    class="btn btn-success mt-3 btn_fix_green">تایید قرارداد
                                            </button>
                                        </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <button onclick="t_open_modal({{$project->id}})" class="btn btn-danger mt-3 btn_fix_danger"
                                            type="submit" style="color: white">عدم تایید قرارداد
                                    </button>

                                </div>
                            @elseif($project->status == 7 && $project->prepayment != 0)
                                <div class="col-md-6">
                                    <form method="post" action="{{route('tarhoBarname_verify_sign')}}">
                                        <div class="col-12">
                                            @csrf
                                            <input type="hidden" value="{{$project->id}}" name="project_id_sign">
                                            <button type="submit" class="btn btn-success mt-3 w-100">تایید واریز پیش پرداخت قرارداد
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-12 col-md-6">
                                    <button onclick="t_open_modal_sign({{$project->id}})" class="btn btn-danger mt-3 w-100"
                                            type="submit" style="color: white">عدم تایید واریز پیش پرداخت قرارداد
                                    </button>
                                    <textarea id="t_inp_err_sign" rows="4" placeholder="لطفا دلیل عدم تایید را بنویسید"
                                              style="display: block"
                                              class="form-control mt-2 w-100"></textarea>
                                </div>
                            @endif
                        </div>
            </div>
        </div>
        </div>
    </div>
    <!-- The Modal -->
    <div class="modal" id="t_myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">عدم تایید قرارداد</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <textarea id="t_inp_err" rows="4" placeholder="لطفا دلیل عدم تایید را بنویسید"
                              style="display: block"
                              class="form-control mt-2 w-100"></textarea>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" value="{{$project->id}}" name="t_project_id">
                    <button onclick="t_make_not_active()" type="button" class="btn btn-success">بله</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>

                </div>

            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal" id="t_myModal_sign">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">عدم تایید قرارداد</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    آیا از عدم تایید قرارداد مطمئن هستید؟
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" value="{{$project->id}}" name="t_project_id_sign">
                    <button onclick="t_make_not_active_sign()" type="button" class="btn btn-success">بله</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>

                </div>

            </div>
        </div>
    </div>

{{--    <!-- The Accept Modal -->--}}
{{--    <div class="modal" id="accept_modal">--}}
{{--        <div class="modal-dialog">--}}
{{--            <form class="modal-content" method="post" action="{{route('tarhoBarname_verify')}}">--}}
{{--            @csrf--}}
{{--            <!-- Modal Header -->--}}
{{--                <div class="modal-header">--}}
{{--                    <h4 class="modal-title">تایید پروژه</h4>--}}
{{--                    <button type="button" class="close" data-dismiss="modal">&times;</button>--}}
{{--                </div>--}}

{{--                <!-- Modal body -->--}}
{{--                <div class="modal-body">--}}
{{--                    آیا قرارداد را تایید می فرمایید؟--}}
{{--                </div>--}}

{{--                <!-- Modal footer -->--}}
{{--                <div class="modal-footer">--}}
{{--                    <input type="hidden" value="{{$project->id}}" name="project_id">--}}
{{--                    <button type="submit" class="btn btn-success">بله</button>--}}
{{--                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>--}}

{{--                </div>--}}

{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}

    <!-- The Accept Modal -->
    <div class="modal" id="accept_modal">
        <div class="modal-dialog">
            <form class="modal-content" method="post" action="{{route('tarhoBarname_verify')}}">
            @csrf
            <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">تایید پروژه</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <select onchange="active_btn(this)" class="form-select form-control w-100" name="supervisor_id"
                            id="multiselect1">
                        <option value="">لطفا برای تایید پروژه ناظر را انتخاب کنید</option>
                        @foreach($super_users as $super_user)
                            <option
                                value="{{$super_user->id}}">{{$super_user->name . ' ' . $super_user->family}}</option>
                        @endforeach
                    </select>
                    <div id="supervisors_errors" class="error_validate text-danger"></div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
{{--                    <input type="hidden" value="{{$project->project_id}}" name="accept_project_id">--}}
                    <input type="hidden" value="{{$project->id}}" name="project_id">
                    <button id="confirm_contract" type="submit" class="btn btn-success" disabled="disabled">ثبت</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">انصراف</button>
                </div>

            </form>
        </div>
    </div>

@endsection
