@extends('legalUser.layout.legal_layout')
@section('title')
    ویرایش پروژه
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
                        <a href="{{route('legal_project_in_process')}}" class="btn btn-sm btn-danger">
                            <i class="fa fa-arrow-right mr-3"></i>
                            بازگشت
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
        <div class="container">
            <div class="row clearfix">
                <div class="card">
                    <div class="card-header">
                        ویرایش جدید
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('legal_project_update',['project'=> $project->id])}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-4">
                                <div class="col-12 col-md-3">
                                    <label>کارفرما</label>
                                    @error('employer')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                    <div class="form-group">
                                        <div id="employer_errors" class="error_validate text-danger"></div>
                                        <select id="employer" class="form-select form-control " name="employer"
                                                aria-label="Default select example" disabled="disabled">

                                            @foreach($employer_users as $employer_user)
                                                <option {{ $project->employer_id == $employer_user->user_id ? 'selected' : '' }} value="{{$employer_user->user_id}}">
                                                    {{$employer_user->name . ' ' . $employer_user->family}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="mt-3">بارگذاری فایل پروپوزال اولیه</label>
                                    <div id="image_errors" class="error_validate text-danger"></div>
                                    <div class="card card_bottom">
                                        <div class="body">
                                            <input id="file" type="file" class="dropify" name="file">
                                        </div>
                                        <a href="{{asset($project->file)}}" class="btn btn-warning w-100 mt-2">دانلود فایل پروپوزال</a>
                                    </div>
                                </div>
                                <div class="col-12 col-md-9">
                                    <div class="col-12">
                                        <label>عنوان پروژه</label>
                                        @error('title')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                        <input type="text" id="title" name="title" class="form-control"
                                               placeholder="عنوان پروژه *" value="{{$project->title}}">
                                        <div id="title_errors" class="error_validate text-danger"></div>
                                    </div>
                                    <div class="col-12 mt-5">
                                        <label>موضوع پروژه</label>
                                        @error('subject')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                        <textarea type="text" id="subject" name="subject" class="form-control"
                                                  placeholder="موضوع پروژه *">{{$project->subject}}</textarea>
                                        <div id="subject_errors" class="error_validate text-danger"></div>
                                    </div>
                                    <div class="col-12 mt-5">
                                        <label>توضیحات پروژه</label>
                                        <textarea type="text" id="comment" name="comment" class="form-control" rows="5"
                                                  placeholder="توضیحات پروژه ">{{$project->comment}}</textarea>
                                        <div id="comment_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-12 text-center mb-4">
                                    <button type="submit" class="btn btn-info w-25 mt-4">ثبت ویرایش</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection
