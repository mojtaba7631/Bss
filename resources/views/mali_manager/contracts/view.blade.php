@php
    $i=1;
@endphp
@extends('mali_manager.layout.mali_layout')
@section('title')
    مشاهده قرارداد
@endsection
@section('css')
    <style>
        .img_sign_img {
            display: block;
        }

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
            line-height: 35px;
        }
        * {
            font-family: "web-shabnam", sans-serif !important;
        }
        .btn_fix_green:hover, .btn_fix_danger:hover {
            color: #fff !important;
        }

    </style>
@endsection
@section('js')
    <script>
        var m_myModal = $('#m_myModal');
        var accept_modal = $('#accept_modal');

        function m_make_not_active() {
            let message = $("#err_message").val();
            let _token = "{{ csrf_token() }}";
            m_myModal.modal('hide');
            $.post("{{route('maliManager_notActive',['project'=>$project->id])}}",
                {
                    message,
                    _token
                },
                function (result) {
                    if (!result['error']) {

                        swal(result['errorMsg']);

                        console.log(result);
                        setTimeout(function () {
                            window.location.href = '{{route("maliManager_contract_index")}}';
                        }, 2000)
                    }
                }
            );
        }

        function accept_open_modal(project_id) {
            accept_modal.find('#project_id').val(project_id);
            accept_modal.modal('show');
        }

        function ma_open_modal(project_id) {
            m_myModal.find('#m_project_id').val(project_id);
            m_myModal.modal('show');
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
                        <a href="{{route('maliManager_contract_index')}}" class="btn btn-sm btn-outline-danger"
                           title="">
                            <i class="fa fa-retweet mr-4"></i>
                            بازگشت به قرارداد ها
                        </a>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12">
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
                    @if($project->signed_by_user == 1)
                        <div class="row">
                            <div class="col-12 col-md-4 text-center">
                                <label>مجری پروژه</label>
                                <img src="{{asset('digital_sign/01.png')}}" class="img_sign_img">

                            </div>
                            <div class="col-12 col-md-4 text-center">
                                <label>ناظر پروژه</label>
                                <img src="{{asset('digital_sign/02.png')}}" class="img_sign_img">
                            </div>
                            <div class="col-12 col-md-4 text-center">
                                <label>کارفرما پروژه</label>
                                <img src="{{asset('digital_sign/03.png')}}" class="img_sign_img">
                            </div>
                        </div>
                    @endif
                    <div class="card">
                        <div class="row">
                            <div class="col-12">
                                @if($project->status == 8)
                                    <a href="{{route('maliManager_minot',['project' => $project->id])}}"
                                       class="btn btn-success mt-3 w-50">تهیه مینوت</a>
                                @elseif($project->status == 4)
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <a onclick="accept_open_modal({{$project->id}})"
                                               class="btn btn-success mt-4 btn_fix_green text-white">تایید قرارداد</a>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <button id="ma_inp_err" onclick="ma_open_modal({{$project->id}})"
                                                    class="btn btn-danger mt-4 btn_fix_danger text-white"
                                                    type="submit">عدم تایید قرارداد
                                            </button>

                                        </div>
                                    </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal" id="m_myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">عدم تایید قرارداد</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <textarea id="err_message" class="form-control" rows="3" placeholder="لطفا علت عدم تایید را بنویسید"></textarea>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button onclick="m_make_not_active()" type="button" class="btn btn-success">بله</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>

                </div>

            </div>
        </div>
    </div>

    <!-- The Accept Modal -->
    <div class="modal" id="accept_modal">
        <div class="modal-dialog">
            <form class="modal-content" method="post" action="{{route('maliManager_accept')}}">
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
                    <input type="hidden" value="{{$project->id}}" name="project_id">
                    <button type="submit" class="btn btn-success">بله</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>

                </div>

            </form>
        </div>
    </div>


@endsection
