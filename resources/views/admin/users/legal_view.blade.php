@extends('admin.layout.admin_layout')
@section('title')
    جزئیات اطلاعات کاربر
@endsection
@section('css')
    <style>
        .national_code_img {
            width: 200px;
            height: 100px;
        }

        .bank_img {
            width: 50px;
            height: 50px;
        }

        .inp_error {
            display: inline-block;
            width: 350px;
        }

        .light_version .table.table-custom thead th {
            background: cadetblue;
            color: #ffffff;
        }
    </style>
@endsection
@section('js')
    <script>
        var not_active_Modal = $('#not_active_Modal');

        function Send_not_active() {
            let message = $("#inp_err").val();
            let _token = "{{ csrf_token() }}";
            not_active_Modal.modal('hide');
            $.post("{{route('admin_user_notActive',['user'=>$user->id])}}",
                {
                    'message': message,
                    '_token': _token
                },
                function (result) {
                    if (!result['error']) {

                        swal(result['errorMsg']);

                        setTimeout(function () {
                            window.location.href = '{{route("admin_user_index")}}';
                        }, 2000)
                    }
                });
        }

        function open_not_avctive_modal(user_id) {

            not_active_Modal.find('#user_id').val(user_id);
            not_active_Modal.modal('show');

        }
    </script>
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>کاربران</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">کاربران</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right hidden-xs">
                        <a href="javascript:void(0);" class="btn btn-sm btn-primary" title=""><i
                                class="fa fa-users mr-4"></i>ایجاد اعضا</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <ul class="nav nav-tabs3 table-nav">
                            <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#company_info">
                                    مشخصات شرکت
                                </a>
                            </li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#personal_info">
                                    مشخصات اعضا
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#letter_information">
                                    دسترسی ارسال نامه
                                </a>
                            </li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#relations">
                                    ارتباطات</a></li>
                        </ul>
                        <div class="tab-content mt-0">
                            <div class="tab-pane show active" id="company_info">
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing8">
                                        <thead>
                                        <tr>
                                            <th>نام شرکت</th>
                                            <th>شماره ثبت</th>
                                            <th>شناسه ملی</th>
                                            <th>تاریخ ثبت</th>
                                            <th class="w60">تصویر اساسنامه</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                {{$user->co_name}}
                                            </td>
                                            <td>
                                                {{$user->co_reg_number}}
                                            </td>
                                            <td>
                                                {{$user->co_national_id}}
                                            </td>
                                            <td>
                                                {{$created_at_jalali}}
                                            </td>
                                            <td>
                                                <a href="{{asset($user->co_statute_image)}}" class="btn btn-warning">دانلود
                                                    اساسنامه</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane show " id="personal_info">
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing8">
                                        <thead>
                                        <tr>
                                            <th>نام مدیرعامل</th>
                                            <th>نام خانوادگی مدیرعامل</th>
                                            <th>کدملی مدیرعامل</th>
                                            <th>تلفن همراه مدیرعامل</th>
                                            <th>شماره شناسنامه مدیرعامل</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                {{$user->ceo_name}}
                                            </td>
                                            <td>
                                                {{$user->ceo_family}}
                                            </td>
                                            <td>
                                                {{$user->ceo_national_code}}
                                            </td>
                                            <td>
                                                {{$user->mobile}}
                                            </td>
                                            <td>
                                                {{$user->ceo_id_code}}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing8">
                                        <thead>
                                        <tr>
                                            <th>نام عضو هیات مدیره</th>
                                            <th>نام خانوادگی عضو هیات مدیره</th>
                                            <th>کدملی عضو هیات مدیره</th>
                                            <th>شماره شناسنامه عضو هیات مدیره</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($managers as $manager)
                                            <tr>
                                                <td>
                                                    {{$manager->manager_name}}
                                                </td>
                                                <td>
                                                    {{$manager->manager_family}}
                                                </td>
                                                <td>
                                                    {{$manager->manager_national_code}}
                                                </td>
                                                <td>
                                                    {{$manager->manager_id_code}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane show " id="relations">
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing8">
                                        <thead>
                                        <tr>
                                            <th>تلفن</th>
                                            <th>کدپستی</th>
                                            <th>آدرس</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                {{$user->phone}}
                                            </td>
                                            <td>
                                                {{$user->co_post_code}}
                                            </td>
                                            <td>
                                                {{$user->address}}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-hover table-custom spacing8">
                                        <thead>
                                        <tr>
                                            <th>بانک</th>
                                            <th>شماره حساب</th>
                                            <th>شماره شبا</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($accounts as $account)
                                            <tr>
                                                <td>
                                                    <span>{{$account->bank_info->bank_name}}</span>
                                                    <img src="{{asset($account->bank_info->bank_image)}}"
                                                         class="bank_img">
                                                </td>
                                                <td>
                                                    {{$account->account_number}}
                                                </td>
                                                <td>
                                                    {{$account->shaba_number}}
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane show " id="letter_information">
                                <form action="{{route('admin_letter_access')}}" method="post" class="card">
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                @foreach($other_users as $other_user)
                                                    <div class="form-check d-inline-block">
                                                        <label class="form-check-label">
                                                            @if($other_user['checked'] > 0)
                                                                <input name="users[]" type="checkbox" checked="checked"
                                                                       class="form-check-input" value="{{$other_user->id}}">
                                                            @else
                                                                <input name="users[]" type="checkbox"
                                                                       class="form-check-input" value="{{$other_user->id}}">
                                                            @endif

                                                            @if($other_user->type == 0)
                                                                {{$other_user->name . ' ' . $other_user->family}}
                                                            @else
                                                                {{$other_user->co_name}}
                                                            @endif
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-12 text-right">
                                                <button class="btn btn-success">
                                                    ذخیره تغییرات
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    وضعیت کاربر
                                </div>
                                <div class="card-body">
                                    @if($user->active == 0)
                                        <form method="post" action="{{route('admin_user_isActive')}}">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{$user->id}}">
                                            <button type="submit" class="btn btn-success text-light">تایید و فعال سازی
                                            </button>
                                        </form>
                                    @elseif($user->active == 1)
                                        {{--                                    <a name="my_btn" onclick="Send_not_active()" href="#" class="btn btn-danger text-light">--}}
                                        {{--                                        عدم تایید--}}
                                        {{--                                    </a>--}}
                                        <button onclick="open_not_avctive_modal({{$user->id}})" type="submit"
                                                class="btn btn-danger w-100">
                                            عدم تایید
                                        </button>
                                        <input id="inp_err" type="text" class="form-control inp_error"
                                               placeholder="لطفا دلیل عدم تایید را بنویسید">
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تایید کاربر</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    کاربر مورد نظر فعال گردید
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">متشکرم</button>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal" id="not_active_Modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">عدم تایید پروژه</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    آیا از عدم تایید پروژه مطمئن هستید؟
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" value="{{$user->id}}" name="user_id">
                    <button onclick="Send_not_active()" type="button" class="btn btn-success">بله</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>

                </div>

            </div>
        </div>
    </div>
@endsection


