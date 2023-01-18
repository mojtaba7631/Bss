@extends('tarhoBarname_manager.layout.tarhoBarname_layout')

@section('title',"پروفایل من")

@section('css')
@endsection

@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>پروفایل من</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">پروفایل من</li>
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
                            @if($user_info['type'] == 1)
                                <form method="post" enctype="multipart/form-data"
                                      action="{{route('tarhoBarname_profile_update')}}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-md-2">
                                            <div class="row">
                                                @if(file_exists($user_info['image']) and !is_dir($user_info['image']))
                                                    @php
                                                        $src = $user_info['image'];
                                                    @endphp
                                                @else
                                                    @php
                                                        $src = 'placeholder/signature_placeholder.png';
                                                    @endphp
                                                @endif
                                                <div class="col-12">
                                                    <label>تصویر پروفایل</label>
                                                    <input type="file" class="dropify" name="image"
                                                           data-default-file="{{asset($src)}}">
                                                </div>
                                                @if(file_exists($user_info['co_statute_image']) and !is_dir($user_info['co_statute_image']))
                                                    @php
                                                        $src = $user_info['co_statute_image'];
                                                    @endphp
                                                @else
                                                    @php
                                                        $src = 'placeholder/signature_placeholder.png';
                                                    @endphp
                                                @endif
                                                <div class="col-12 mt-4">
                                                    <label>تصویر اساسنامه شرکت</label>
                                                    <input type="file" class="dropify" name="co_statute_image"
                                                           data-default-file="{{asset($src)}}">
                                                </div>
                                                @if(file_exists($user_info['Signature_img']) and !is_dir($user_info['Signature_img']))
                                                    @php
                                                        $src = $user_info['Signature_img'];
                                                    @endphp
                                                @else
                                                    @php
                                                        $src = 'placeholder/signature_placeholder.png';
                                                    @endphp
                                                @endif
                                                <div class="col-12 mt-4">
                                                    <label>تصویر امضا</label>
                                                    <input type="file" class="dropify" name="Signature_img"
                                                           data-default-file="{{asset($src)}}">
                                                </div>
                                                @if(file_exists($user_info['stamp_img']) and !is_dir($user_info['stamp_img']))
                                                    @php
                                                        $src = $user_info['stamp_img'];
                                                    @endphp
                                                @else
                                                    @php
                                                        $src = 'placeholder/stamp_placeholder.png';
                                                    @endphp
                                                @endif
                                                <div class="col-12 mt-4">
                                                    <label>تصویر مهر</label>
                                                    <input type="file" class="dropify" name="stamp_img"
                                                           data-default-file="{{asset($src)}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <div class="row">
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>نام شرکت</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['co_name']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>شماره ثبت</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['co_reg_number']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>شماره موبایل</label>
                                                    <input type="text" class="form-control" name="mobile"
                                                           value="{{$user_info['mobile']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>شماره تماس</label>
                                                    <input type="text" class="form-control" name="phone"
                                                           value="{{$user_info['phone']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>پست الکترونیک</label>
                                                    <input type="text" class="form-control" name="email"
                                                           value="{{$user_info['email']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>شناسه ملی شرکت</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['co_national_id']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>تاریخ ثبت شرکت</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['start_date']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>کدپستی شرکت</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['co_post_code']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>نام مدیرکل شرکت</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['ceo_name']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>نام خانوادگی مدیرکل شرکت</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['ceo_family']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>کدملی مدیرکل شرکت</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['ceo_national_code']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>شماره شناسنامه مدیرکل شرکت</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['ceo_id_code']}}">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label>آدرس</label>
                                                    <input name="address" class="form-control"
                                                           value="{{$user_info['address']}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-12 text-right">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fa fa-edit mr-2"></i>
                                                ذخیره تغییرات
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <form method="post" enctype="multipart/form-data"
                                      action="{{route('tarhoBarname_profile_update')}}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-md-2">
                                            <div class="row">
                                                @if(file_exists($user_info['image']) and !is_dir($user_info['image']))
                                                    @php
                                                        $src = $user_info['image'];
                                                    @endphp
                                                @else
                                                    @php
                                                        $src = 'placeholder/image_placeholder.png';
                                                    @endphp
                                                @endif
                                                <div class="col-12">
                                                    <label>تصویر پروفایل</label>
                                                    <input type="file" class="dropify" name="image"
                                                           data-default-file="{{asset($src)}}">
                                                </div>
                                                @if(file_exists($user_info['national_code_img']) and !is_dir($user_info['national_code_img']))
                                                    @php
                                                        $src = $user_info['national_code_img'];
                                                    @endphp
                                                @else
                                                    @php
                                                        $src = 'placeholder/image_placeholder.png';
                                                    @endphp
                                                @endif
                                                <div class="col-12 mt-4">
                                                    <label>تصویر کارت ملی</label>
                                                    <input type="file" class="dropify" name="national_code_img"
                                                           data-default-file="{{asset($src)}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <div class="row">
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>نام</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['name']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>نام خانوادگی</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['family']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>تاریخ تولد</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['birth_date']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>جنسیت</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['sex'] == 1 ? 'مرد' : 'زن'}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>شماره شناسنامه</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['id_code']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>کدملی</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['national_code']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>شماره تماس</label>
                                                    <input type="text" class="form-control" name="phone"
                                                           value="{{$user_info['phone']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>شماره موبایل</label>
                                                    <input type="text" class="form-control" name="mobile"
                                                           value="{{$user_info['mobile']}}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 mb-4">
                                                    <label>نام کاربری</label>
                                                    <input disabled type="text" class="form-control"
                                                           value="{{$user_info['username']}}">
                                                </div>
                                                <div class="col-12c ol-sm-6 col-md-3 mb-4">
                                                    <label>پست الکترونیک</label>
                                                    <input type="text" name="email"
                                                           class="form-control">{{$user_info['email']}}
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label>آدرس</label>
                                                    <input name="address" class="form-control"
                                                           value="{{$user_info['address']}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-12 text-right">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fa fa-edit mr-2"></i>
                                                ذخیره تغییرات
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-12">
                    <div class="card">
                        <form action="{{route('tarhoBarname_change_pass')}}" method="post" class="body">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label>رمز فعلی</label>
                                    <input type="password" name="old_password" class="form-control">
                                </div>
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label>رمز جدید</label>
                                    <input type="password" name="new_password" class="form-control">
                                </div>
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label>تکرار رمز جدید</label>
                                    <input type="password" name="new_password_confirmation" class="form-control">
                                </div>
                                <div class="col-12 col-sm-6 col-md-3 pt-3">
                                    <button type="submit" class="btn btn-success mt-2">
                                        <i class="fa fa-edit mr-2"></i>
                                        تغییر رمز عبور
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
