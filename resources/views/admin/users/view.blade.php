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

        .card-body h4 {
            font-size: 12pt;
            margin-bottom: 15px;
        }

        .form-check-input {
            position: absolute;
            margin-top: 3px;
            margin-right: -1.25rem;
        }

        .form-check {
            margin-right: 1.25rem;
            margin-left: 0;
        }
    </style>
@endsection
@section('js')
    <script>
        function myFunction() {
            let message = $("#inp_err").val();
            $.post("{{route('admin_user_notActive',['user'=>$user->id])}}",
                {
                    message
                },
                function (result) {
                });
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
                <div class="col-12">
                    <div class="card">
                        <ul class="nav nav-tabs3 table-nav">
                            <li class="nav-item">
                                <a class="nav-link active show" data-toggle="tab"
                                   href="#Personal_info">
                                    مشخصات فردی
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#letter_information">
                                    دسترسی ارسال نامه
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#Relations">
                                    ارتباطات
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#Account_Information">
                                    اطلاعات مالی
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content mt-0">
                            <div class="tab-pane show active" id="Personal_info">
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing8">
                                        <thead>
                                        <tr>
                                            <th>نام</th>
                                            <th>نام خانوادگی</th>
                                            <th>کدملی</th>
                                            <th>شناسنامه</th>
                                            <th>جنسیت</th>
                                            <th>تاریخ تولد</th>
                                            <th class="w60">تصویر کدملی</th>
                                            <th>وضعیت</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                {{$user->name}}
                                            </td>
                                            <td>
                                                {{$user->family}}
                                            </td>
                                            <td>
                                                {{$user->national_code}}
                                            </td>
                                            <td>
                                                {{$user->id_code}}
                                            </td>
                                            <td>
                                                {{$user->sex === 0 ? 'زن' : 'مرد'}}
                                            </td>
                                            <td>
                                                {{$created_at_jalali}}
                                            </td>
                                            <td>
                                                <img
                                                    src="{{asset($user->national_code_img ?? 'placeholder/placeholder.png')}}"
                                                    class="national_code_img">
                                            </td>

                                            <td>
                                                @if($user->active == 1)
                                                    <div class="alert alert-success">
                                                        فعال
                                                    </div>
                                                @else
                                                    <div class="alert alert-danger">
                                                        غیر فعال
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <div class="card">
                                        <div class="card-body">
                                            <h4>
                                                تغییر وضعیت کاربر
                                            </h4>
                                            @if($user->active == 0)
                                                <form method="post" action="{{route('admin_user_isActive')}}">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                                    <button type="submit" class="btn btn-success text-light">تایید و
                                                        فعال سازی
                                                    </button>
                                                </form>
                                            @elseif($user->active == 1)

                                                <input id="inp_err" type="text" class="form-control inp_error"
                                                       placeholder="لطفا دلیل غیر فعال کردن کاربر را بنویسید">
                                                <button name="my_btn" onclick="myFunction()" href="#"
                                                        class="btn btn-danger text-light">
                                                    غیر فعال کن
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane show " id="Relations">
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing8">
                                        <thead>
                                        <tr>
                                            <th>مدرک</th>
                                            <th>رشته تحصیلی</th>
                                            <th>آدرس</th>
                                            <th>تلفن ثابت</th>
                                            <th>تلفن همراه</th>
                                            <th>تلفن فضای مجازی</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                @if($user->evidence === 1)
                                                    <span>فوق دکتری</span>
                                                @elseif($user->evidence == 2)
                                                    <span>دکتری</span>
                                                @elseif($user->evidence == 3)
                                                    <span>کارشناسی ارشد</span>
                                                @elseif($user->evidence == 4)
                                                    <span>کارشناسی </span>
                                                @elseif($user->evidence == 5)
                                                    <span>فوق دیپلم</span>
                                                @elseif($user->evidence == 6)
                                                    <span>دیپلم</span>
                                                @elseif($user->evidence == 7)
                                                    <span>حوزوی</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{$user->field_study}}
                                            </td>
                                            <td>
                                                {{$user->address}}
                                            </td>
                                            <td>
                                                {{$user->phone}}
                                            </td>
                                            <td>
                                                {{$user->mobile}}
                                            </td>
                                            <td>
                                                {{$user->social_no}}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane show " id="Account_Information">
                                <div class="table-responsive">
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
@endsection


