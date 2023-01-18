@extends('legalUser.layout.legal_layout')
@section('title')
    تعریف پروژه
@endsection
@section('css')
    <style>

    </style>
@endsection
@section('js')
    <script>

    </script>
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>پروژه ها</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">پروژه ها</li>
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
        <div class="container">
            <div class="row clearfix">
                <div class="card">
                    <div class="card-header">
                        ثبت پروژه جدید
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('legal_project_create')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-8">
                                    <div class="col-12">
                                        <label>عنوان پروژه</label>
                                        <input type="text" id="title" name="title" class="form-control"
                                               placeholder="عنوان پروژه *">
                                        <div id="title_errors" class="error_validate text-danger"></div>
                                    </div>
                                    <div class="col-12 mt-5">
                                        <label>موضوع پروژه</label>
                                        <input type="text" id="subject" name="subject" class="form-control"
                                               placeholder="موضوع پروژه *">
                                        <div id="subject_errors" class="error_validate text-danger"></div>
                                    </div>
                                    <div class="col-12 mt-5">
                                        <label>موضوع پروژه</label>
                                        <textarea type="text" id="comment" name="comment" class="form-control"
                                                  placeholder="توضیحات پروژه *"></textarea>
                                        <div id="comment_errors" class="error_validate text-danger"></div>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-info w-50 mt-4">ثبت پروژه</button>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label>کارفرما</label>
                                    <div class="form-group">
                                        <div id="employer_errors" class="error_validate text-danger"></div>

                                        <select id="employer" class="form-select form-control " name="employer"
                                                aria-label="Default select example ">
                                            <option selected value="0">لطفا کارفرما را انتخاب کنید *</option>
                                            @foreach($users as $user)

                                                <option value="{{$user[0]->id}}">
                                                    {{$user[0]->name . ' ' . $user[0]->family}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="mt-3">بارگذاری فایل پروپوزال اولیه</label>
                                    <div id="image_errors" class="error_validate text-danger"></div>
                                    <div class="card card_bottom">
                                        <div class="body">
                                            <input id="file" type="file" class="dropify"
                                                   name="file">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
