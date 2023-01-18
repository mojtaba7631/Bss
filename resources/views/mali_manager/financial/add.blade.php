@extends('mali_manager.layout.mali_layout')
@section('title')
    ثبت سند مالی
@endsection
@section('css')
    <style>
        .light_version .table tr td, .light_version .table tr th {
            background: none;
        }

        .light_version .table.table-custom tbody tr:nth-child(odd) {
            background: #e1f3ff !important;
        }

        .my_a {
            cursor: pointer;
            color: #948f8f !important;
        }

        a:hover {
            color: #007bff !important;
        }

        .i_make_contract {
            color: red !important;
        }

        .i_make_sign {
            color: red !important;
        }
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
                        <h1>ثبت سند مالی</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">ثبت سند مالی</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            سند مالی
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-3"></div>
                                <div class="col-12 col-md-6 text-center">
                                    <form method="post" action="{{route('maliManager_Payment',['report'=> $report_id])}}" enctype="multipart/form-data"
                                          id="frm_financial_doc">
                                        @csrf
                                        <div class="card card_bottom">
                                            <div class="body">
                                                <input type="hidden" value="{{$report_id}}" name="report">
                                                <input form="frm_financial_doc" id="payment_file" type="file" class="dropify"
                                                       name="payment_file">
                                            </div>
                                                <button id="btn_financial_doc" type="submit" class="btn btn-success w-50">ثبت پرداخت</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-12 col-md-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
