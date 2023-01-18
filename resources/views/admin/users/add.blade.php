@extends('admin.layout.admin_layout')
@section('title')
    افزودن کاربر
@endsection
@section('css')
    <link rel="stylesheet" href="{{asset('public-admin/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css')}}">
    <style>

    </style>
@endsection
@section('js')
    <script src="{{asset('public-admin/assets/js/jquery.multi-select.js')}}"></script>
    <script src="{{asset('public-admin/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js')}}"></script>
    <script>
        $('#multiselect1').multiselect({

        });
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
                        <a href="{{route('admin_user_index')}}" class="btn btn-sm btn-primary" title="">
                            <i class="fa fa-arrow-right mr-4"></i>
                            بازگشت به کاربران
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
        <div class="row clearfix">
            <div class="card">
                <div class="card-header">
                    ورود اطلاعات کاربران
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('admin_user_create')}}" enctype="multipart/form-data">
                        <div class="row">
                            @csrf
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <label>تصویر پروفایل :</label>
                                        <div id="image_errors" class="error_validate text-danger"></div>
                                        <div class="card card_bottom">
                                            <div class="body">
                                                <input id="image" type="file" class="dropify"
                                                       name="image">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label>تصویر امضا :</label>
                                        <div id="Signature_img_errors" class="error_validate text-danger"></div>
                                        <div class="card card_bottom">
                                            <div class="body">
                                                <input id="Signature_img" type="file" class="dropify"
                                                       name="Signature_img">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label>تصویر کارت ملی :</label>
                                        <div id="national_code_img_errors" class="error_validate text-danger"></div>
                                        <div class="card card_bottom">
                                            <div class="body">
                                                <input id="national_code_img" type="file" class="dropify"
                                                       name="national_code_img">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <label>نام کاربر :</label>
                                        <input type="text" name="name" class="form-control" placeholder="نام کاربر *">
                                        <div id="name_errors" class="error_validate text-danger"></div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label>نام خانوادگی :</label>
                                        <input type="text" name="family" class="form-control" placeholder="نام خانوادگی *">
                                        <div id="family_errors" class="error_validate text-danger"></div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label>تلفن همراه :</label>
                                        <input type="text" name="mobile" class="form-control" placeholder="تلفن همراه *">
                                        <div id="mobile_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-12 col-md-4">
                                        <label>کد ملی :</label>
                                        <input type="text" name="national_code" class="form-control" placeholder="کدملی *">
                                        <div id="national_code_errors" class="error_validate text-danger"></div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label>کداختصاصی :</label>
                                        <input type="text" name="unique_code" class="form-control" placeholder="کداختصاصی *">
                                        <div id="unique_code_errors" class="error_validate text-danger"></div>
                                    </div>
                                    <div class="col-12 col-md-4 multiselect_div">
                                        <label>سطح دسترسی :</label>
                                        <select data-mdb-placeholder="Example placeholder" class="form-select form-control multiselect" multiple="multiple" name="roles[]" id="multiselect1">
                                            <option value="" disabled="disabled"> انتخاب سطح دسترسی *</option>
                                            <option value="3">کارفرما</option>
                                            <option value="2">مدیر طرح و برنامه</option>
                                            <option value="6">مدیر امور مالی</option>
                                            <option value="5">مدیرعامل</option>
                                            <option value="4">ناظر</option>
                                        </select>
                                        <div id="roles_errors" class="error_validate text-danger"></div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success mt-4 w-25">ثبت کاربر</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection
