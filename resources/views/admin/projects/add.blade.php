@extends('admin.layout.admin_layout')
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
                        <form method="post" action="{{route('admin_user_create')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-8">
                                    <label>عنوان پروژه</label>
                                    <input type="text" name="title" class="form-control" placeholder="عنوان پروژه *">
                                    <div id="title_errors" class="error_validate text-danger"></div>
                                </div>
                                <div class="col-12 col-md-4"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
