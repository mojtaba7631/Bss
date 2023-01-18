@extends('mali_manager.layout.mali_layout')
@section('title')
    پرداخت شده ها
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
        .i_make_sign{
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
                        <h1>پرداخت شده ها</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">پرداخت شده ها</li>
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
                        <div class="tab-content mt-0">
                            <div class="tab-pane show active" id="Users">
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing8">
                                        <thead>
                                        <tr>
                                            <th>عنوان پروژه</th>
                                            <th>کارفرما</th>
                                            <th>ناظر</th>
                                            <th>وظیفه</th>
                                            <th class="w100">عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($projects as $project)
                                            <tr>
                                                <td>
                                                    {{$project->title}}
                                                </td>
                                                <td>
                                                    {{$project->employer->name . ' ' . $project->employer->family}}
                                                </td>
                                                <td>
                                                    {{$project->supervisor->name . ' ' . $project->supervisor->family}}
                                                </td>
                                                <td>
                                                    @if($report->status === 0)
                                                        <span class="badge badge-warning">ثبت اولیه</span>
                                                    @elseif($report->status === 1)
                                                        <span class="badge badge-info">تایید اولیه کارفرما</span>
                                                    @elseif($report->status === 2)
                                                        <span class="badge badge-info">ثبت فرم قرارداد</span>
                                                    @elseif($report->status === 3)
                                                        <span class="badge badge-dark">تایید فرم قرارداد</span>
                                                    @elseif($report->status === 4)
                                                        <span class="badge badge-primary">تایید طرح و برنامه</span>
                                                    @elseif($report->status === 5)
                                                        <span class="badge badge-danger">تایید مدیرعامل</span>
                                                    @elseif($report->status === 6)
                                                        <span  class="badge badge-success">تایید مالی در انتظار امضا</span>
                                                    @elseif($report->status === 7)
                                                        <span class="badge badge-pill">درج امضای دیجیتال</span>
                                                    @elseif($report->status === 8)
                                                        <span class="badge badge-light">تحویل گزارش فاز</span>
                                                    @elseif($report->status === 9)
                                                        <span class="badge badge-secondary">تایید گزارش ناظر</span>
                                                    @elseif($report->status === 10)
                                                        <span class="badge badge-default">تایید گزارش کارفرما</span>
                                                    @elseif($report->status === 11)
                                                        <span class="badge badge-purple">تایید گزارش طرح و برنامه</span>
                                                    @elseif($report->status === 12)
                                                        <span class="badge badge-blue">تایید گزارش مدیرعامل</span>
                                                    @elseif($report->status === 13)
                                                        <span class="badge badge-red">تایید واریز مبلغ</span>
                                                    @elseif($report->status === 14)
                                                        <span class="badge badge-orange">پرداخت شده</span>
                                                    @elseif($report->status === 15)
                                                        <span class="badge badge-phosphor_green">بارگذاری سند مالی</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{route('maliManager_financial_detail',['project'=>$project->id])}}"
                                                       class="my_a" title="جزئیات پرداخت">
                                                        <i class="fa fa-calculator"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
