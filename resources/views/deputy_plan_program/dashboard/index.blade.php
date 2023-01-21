@extends('deputy_plan_program.layout.deputy_plan_program_layout')
@section('title',"داشبورد معاون طرح و برنامه")
@section('css')
    <style>
        .card-header {
            padding: 24px !important;
            margin-bottom: 15px;
            background-color: #fff !important;
            box-shadow: 0 0 15px rgb(0 0 0 / 20%);
            color: #000 !important;
        }
    </style>
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>فرم درخواست مرخصی</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#">نما</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    فرم درخواست مرخصی
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('js')
@endsection
